<?php 

ob_start();
session_start();
include '../actions/dbh.php';

class load extends db {
	public function getonline() {
		$mdb = "usr_".$_SESSION['UID'];

		$query = db::mconnect($mdb)->prepare("SELECT t1.`username`, t1.`uid`, t1.`chatid`, t1.`pic`, t1.`fullname` FROM people.`users` as t1, $mdb.friends as t2 WHERE t1.`uid`=t2.`uid` AND t1.`last_activity` > DATE_SUB(NOW(), INTERVAL 7 SECOND)");

		$query->execute();

		$counta = $query->rowCount();

		if($counta>0) {

			$count = 1;
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$username = $row['username'];
				$uid = $row['uid'];
				$chatid = $row['chatid'];
				$pic = $row['pic'];
				$fullname = $row['fullname'];
				$rand = uniqid('_', false);

				if($count%2==1) {
					
					?>
					
					<div class="friend_stat_online" id="<?php echo $rand; ?>" style="background: #f7f7f7;" data_cid="<?php echo $chatid; ?>" onclick="openchat('<?php echo $username; ?>', '<?php echo $uid; ?>', '<?php echo $pic; ?>', '<?php echo $fullname; ?>', '<?php echo $rand; ?>');">

						<div class="dp">
							<img src="img_users/<?php echo $pic; ?>" alt="">
						</div>
						
						<div class="name" style="">
							<p><span style="font-size: 13px;"><?php echo $username; ?></span></p>

							<p><span style="color: gray;font-size: 11px;">(<?php echo $fullname; ?>)</span></p>
						</div>

						<div class="online"><ins>&bull;</ins></div>

					</div>

					<?php

				}
				else {
					
					?>
						
					<div class="friend_stat_online" id="<?php echo $rand; ?>" style="background: white;" data_cid="<?php echo $chatid; ?>" onclick="openchat('<?php echo $username; ?>', '<?php echo $uid; ?>', '<?php echo $pic; ?>', '<?php echo $fullname; ?>', '<?php echo $rand; ?>');">
					
						<div class="dp">
							<img src="img_users/<?php echo $pic; ?>" alt="">
						</div>
						<div class="name">
							<p><span style="font-size: 13px;"><?php echo $username; ?></span></p>

							<p><span style="color: gray;font-size: 11px;">(<?php echo $fullname; ?>)</span></p>
						</div>
						<div class="online"><ins>&bull;</ins></div>
					
					</div>

					<?php

				}

				$count += 1;

			}

			$data = ob_get_contents();
			ob_clean();
			
			$resp = array("count"=>$counta, "data"=>$data);
			echo json_encode($resp);

			ob_end_flush();
		}		
		else { 

			echo "<span style='color: gray;font-size: 13px;'>None of your friends are online currently.</span>";

		}

	}
}

$obj = new load;
$obj->getonline();

?>





