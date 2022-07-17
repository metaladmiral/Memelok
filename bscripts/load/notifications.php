<?php 

session_start();
include '../actions/dbh.php';

class app extends db {
	protected $server = "localhost/memelok";
	public function gettime($fulltime) {

		$mtime = "";
		$fulltime = explode('/', $fulltime);

		$curr_year = (int)date("Y");
		$curr_month = (int)date("n");
		$curr_date = (int)date("j");
		$curr_hour = (int)date("G");
		$curr_min = (int)date("i");

		$date = $fulltime[0];
		$time = $fulltime[1];

		$noti_year = (int)explode(':', $date)[2];
		$noti_month = (int)explode(':', $date)[1];
		$noti_date = (int)explode(':', $date)[0];
		$noti_hour = (int)explode(':', $time)[0];
		$noti_min = (int)explode(':', $time)[1];

		if($curr_year-$noti_year==0) {

			if($curr_month-$noti_month==0) {

				if($curr_date-$noti_date==0) {

					if($curr_hour-$noti_hour==0) {


						if($curr_min-$noti_min==0) {

							$mtime = "Some seconds ago.";

						}
						else {

							if($curr_min-$noti_min>1) {
								$slot = $curr_min-$noti_min;
								$mtime = $slot." mins. ago.";
							} 
							else {
								$mtime = "1 min. ago";
							}

						}
						

					}
					else {

						if($curr_hour-$noti_hour>1) {
							$slot = $curr_hour-$noti_hour;
							$mtime = $slot." hours ago.";
						} 
						else {
							$mtime = "1 hour ago";
						}

					}
					

				}
				else {

					if($curr_date-$noti_date>1) {
						$slot = $curr_date-$noti_date;
						$mtime = $slot." days ago.";
					} 
					else {
						$mtime = "1 day ago";
					}

				}

			}
			else {
				if($curr_month-$noti_month>1) {
					$slot = $curr_month-$noti_month;
					$mtime = $slot." months ago.";
				}
				else {
					$mtime = "A month ago.";
				}
			}

		}
		else {
			if($curr_year-$noti_year>1) {
				$slot=$curr_year-$noti_year;
				$mtime = $slot." years ago";
			}
			else {
				$mtime = "A year ago.";
			}
		}

		return $mtime;

	}

	public function getnoti($offset, $limit, $total) {

		$uid = $_SESSION['UID'];
		
		$db = "usr_".$uid;

		if($total=='null') {
			$query = db::mconnect($db)->prepare("SELECT COUNT(*) as count FROM `notifications`");
			$query->execute();
			$fetch = $query->fetch(PDO::FETCH_ASSOC);
			$total = $fetch['count'];
		}

		if($total>0) {

		$query = db::mconnect($db)->prepare("SELECT * FROM `notifications` ORDER BY `id` DESC LIMIT $offset, $limit");
		$query->execute();


		$html="";

		if($query->rowCount() > 0) {
			
			while ($res = $query->fetch(PDO::FETCH_ASSOC)) {
				$read = $res['read'];
				$content = $res['content'];
				$time = $res['time'];
				$suid = $res['s_UID'];
				$dp = $res['s_dp'];
				$link = $res['link'];
				$id = uniqid('').rand(0, 9900);

				$mtime = app::gettime($time);
				
				if($read==0) {
					
					?>

						<div class='noti_<?php echo $id; ?> noti_content'>	
							<div class='dp'>
								
								<img src='http://<?php echo $this->server; ?>/img_users/<?php echo $dp; ?>' alt=''>
								<div class='cover'></div>

							</div>

							<div class='noti_details'>

								<div class='content'>
									<span><?php echo $content; ?></span>
								</div>

								<div class='time'>
									<span><?php echo $mtime; ?></span>
								</div>
				
							</div>

							<div class='unread'>
								<ins>&bull;</ins>
							</div>


						</div>

					<?php

				}
				else {

					?>

						<div class='noti_<?php echo $id; ?> noti_content'>	
							
							<div class='dp'>
								
								<img src='http://<?php echo $this->server; ?>/img_users/<?php echo $dp; ?>' alt=''>
								<div class='cover'></div>

							</div>

							<div class='noti_details'>

								<div class='content'>
									<span><?php echo $content; ?></span>
								</div>

								<div class='time'>
									<span><?php echo $mtime; ?></span>
								</div>
				
							</div>

						</div>
					
					<?php

				}


			}

			if($total-$limit>0) {
				?> 
				<center>
					<a style="position:relative;font-size: 13px;top: 5px;padding-bottom: 5px;color: blue;cursor: pointer;" onclick='load_noti("<?php echo $offset+8; ?>", "<?php echo $limit+8; ?>", "<?php echo $total; ?>", this);return false;'>Load More</a>
				</center>
				<?php
			}

		}
		else {
			return "<span style='position: absolute;top: 10px;left: 50%;color: gray;font-size: 13px;transform: translate(-50%, 0);'>You have no notifications yet.</span>";
		}

		}
		else {
			return "<span style='position: absolute;top: 10px;left: 50%;color: gray;font-size: 13px;transform: translate(-50%, 0);'>You have no notifications yet.</span>";	
		}
	
	}

	public function sendnoti($key, $content) {
		$uid_f = $key;

		$dbname = "usr_".$uid_f;

		$dp_m=$_SESSION['pic'];
		$uidm = $_SESSION['UID'];
		$username = $_SESSION['username'];
		$link = 'none';
		
		$date = date("j:n:Y");
		$hour = (int)date("G");
		$minsec = (int)date("i:s");

		$time = $hour.":".$minsec; 

		$mtime = $date."/".$time;

		try {
			$query = db::mconnect($dbname)->prepare("INSERT INTO `notifications`(`time`, content, s_UID, s_dp, link) VALUES('$mtime', '$content', '$uidm', '$dp_m', '$link')");
			$query->execute();
			return 1;
		}
		catch(PDOException $e) {
			return $e->getMessage();
		}

	}

}

$app = new app;
if($_POST['which']=='getnoti') {
	echo $app->getnoti($_POST['offset'], $_POST['limit'], $_POST['total']);
}
else if($_POST['which']=='sendnoti') {
	echo $app->sendnoti($_POST['key'], $_POST['content']);
}