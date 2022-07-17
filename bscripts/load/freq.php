<?php

include '../actions/dbh.php';
session_start();

class freq {
	public function getfreqs() {
		$uid = $_SESSION['UID'];
		$dbm = "usr_".$uid;
		$friend_reqs = array();

		$offset = $_POST['offset'];
		$lim = $offset+6;

		$querymc = db::mconnect($dbm)->prepare("SELECT uid FROM `friend_requests` LIMIT $offset, $lim");
		$querymc->execute();
		$offset += 6;

		$html = "";

		if($querymc->rowCount()<=0) {
			return "<span style='position: absolute;top: 10px;left: 50%;color: gray;font-size: 13px;transform: translate(-50%, 0);'>No Friend Requests.</span>";
		}
		else {
			
			while($row=$querymc->fetch(PDO::FETCH_ASSOC)) {
				array_push($friend_reqs, $row['uid']);
			}

		if(count($friend_reqs)>0) {

			$nfriend_reqs = "'".implode("', '", $friend_reqs)."'";
			
			$query = db::pconnect()->prepare("SELECT pic, uid, username, fullname FROM `users` WHERE `uid` IN (".$nfriend_reqs.")");
			$query->execute();

			while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
				$freqs[$row['uid']] = array($row['pic'], $row['username'], $row['fullname']);
			}

			foreach($freqs as $key => $val) {

				$img = $freqs[$key][0];
				$username = $freqs[$key][1];
				$fullname = $freqs[$key][2];

				$split = str_split(strtolower($fullname));

				$id = uniqid('').rand(0, 9900).$split[0].$split[1];

				?>

					<div class='freq-items' id='freq-items_<?php echo $id ?>'>

						<button type='button' onclick='accept_request("<?php echo $key; ?>", "<?php echo $id; ?>")' id='accept_request'>Accept</button>

						<div class='dp'>

							<img src='img_users/<?php echo $img; ?>' alt=''>

						</div>

						<div class='user_details'>
							
							<div class='top_name'><span><?php echo $username; ?></span></div>

							<div class='bottom_name'><span><?php echo $fullname; ?></span></div>

						</div>

						<button type='button' onclick='decline_request("<?php echo $key; ?>", "<?php echo $id; ?>");' id='decline_request'>Decline</button>

						<div class='overlay_freq' id='overlay_<?php echo $id; ?>'>
							<div class='spinner_freq loader'></div>
						</div>

					</div>

					<?php 

					if($querymc->rowCount()>$lim) {
						?>

						<br>
						<center><span style="color: blue;text-decoration: underline;font-size: 13px;" onclick="load_freq(<?php echo $offset; ?>, '1');">Load More</span></center>						

						<?php
					}

			}

		}
		else {
			return "<span style='position: absolute;top: 10px;left: 50%;color: gray;font-size: 13px;transform: translate(-50%, 0);'>No Friend Requests.</span>";
		}

		}

	}

	public function freqaction($action) {
		if($action=='accept') {

			$uid_f = $_POST['key'];
			$uid_m = $_SESSION['UID'];
			
			$dbm = "usr_".$uid_m;
			$dbf = "usr_".$uid_f;

			try {

				$dbmc = db::mconnect($dbm);

				$query = $dbmc->prepare("SELECT COUNT(*) as count FROM `friends`");
				$query->execute();
				$countm = (int)($query->fetch()['count'][0])+1;

				$query = $dbmc->prepare("DELETE FROM `friend_requests` WHERE `uid`='$uid_f'");
				$query->execute();

				$query = db::pconnect()->prepare("UPDATE `users` SET `friend_count`='$countm' WHERE uid='$uid_m'");	
				$query->execute();

				$query = $dbmc->prepare("INSERT INTO `friends`(`uid`) VALUES('$uid_f')");
				$query->execute();



				$dbfc = db::mconnect($dbf);

				$query = $dbfc->prepare("SELECT COUNT(*) as count FROM `friends`");
				$query->execute();
				$countf = (int)($query->fetch()['count'][0])+1;
				
				$query = $dbfc->prepare("DELETE FROM `friend_requests_sent` WHERE `uid`='$uid_m'");
				$query->execute();

				$query = db::pconnect()->prepare("UPDATE `users` SET `friend_count`='$countf' WHERE uid='$uid_f'");	
				$query->execute();
				
				$query = $dbfc->prepare("INSERT INTO `friends`(`uid`) VALUES('$uid_m')");
				$query->execute();

				return 1;

			}
			catch(\Exception $e) {
				return $e->getMessage();
			}

			// ----------------

		}
		else {

			$uid_f = $_POST['key'];
			$uid_m = $_SESSION['UID'];
			$dbm = "usr_".$uid_m;
			$dbf = "usr_".$uid_f;

			try {

				$query = db::mconnect($dbm)->prepare("DELETE FROM `friend_requests` WHERE `uid`='$uid_f'");
				$query->execute();

				$query = db::mconnect($dbf)->prepare("DELETE FROM `friend_requests_sent` WHERE `uid`='$uid_m'");
				$query->execute();
		
				return 1;

			}
			catch(\Exception $e) {
				return $e->getMessage();
			}

		}
	}

	public function send_req() {
		$fuid = $_POST['uid'];
		$muid = $_SESSION['UID'];

		$dbf = "usr_".$fuid;
		$dbm = "usr_".$muid;

		$query = db::mconnect($dbm)->prepare("SELECT uid FROM `friend_requests_sent` WHERE `uid`='$fuid'");
		$query->execute();

		if($fuid==$muid) {
			return 3;
		}
		else {

			if($query->rowCount()>0) {
				return 2;
			}
			else {

				try {

					$query = db::mconnect($dbm)->prepare("INSERT INTO `friend_requests_sent`(uid) VALUES('$fuid')");
					$query->execute();

					$query = db::mconnect($dbf)->prepare("INSERT INTO `friend_requests`(uid) VALUES('$muid')");
					$query->execute();

					return 1;

				}
				catch(PDOException $e) {
					return $e->getMessage();
				}

			}
		
		}

	}

}

$obj = new freq;
if($_POST['which']=='getfreqs') {
	echo $obj->getfreqs();
}
else if($_POST['which']=='accept' OR $_POST['which']=='decline') {
	echo $obj->freqaction($_POST['which']);
} 
else if($_POST['which']=='sendreq') {
	echo $obj->send_req();
}