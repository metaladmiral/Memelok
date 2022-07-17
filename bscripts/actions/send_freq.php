<?php 

session_start();
include 'dbh.php';

class action extends db {
	public function sendreq() {
		$fuid = $_POST['uid'];
		$muid = $_SESSION['UID'];

		$freq_sent_m = $_SESSION['friend_requests_sent'];

		$query = db::pconnect()->prepare("SELECT friend_requests FROM `users` WHERE `uid`='$fuid'");
		$query->execute();

		$row = $query->fetch(PDO::FETCH_ASSOC);
		$friend_requests_f = $row['friend_requests'];

		if(is_null($friend_requests_f) OR empty($friend_requests_f) OR strtolower($friend_requests_f)=='null') {
			$friend_requests_f = array();
		}
		else {
			$friend_requests_f = json_decode($friend_requests_f, true);
		}

		if(is_null($freq_sent_m) OR empty($freq_sent_m) OR strtolower($freq_sent_m)=='null') {
			$freq_sent_m = array();
		}
		else {
			$freq_sent_m = json_decode($freq_sent_m, true);
		}

		if($fuid==$muid) {
			return 3;
		}
		else {

			if(in_array($fuid, $freq_sent_m) OR in_array($muid, $friend_requests_f)) {
				return 2;
			}
			else {

				array_push($friend_requests_f, $muid);
				$friend_requests_f[$muid] = array();
				array_push($freq_sent_m, $fuid);

				$friend_requests_f = json_encode($friend_requests_f);
				$freq_sent_m = json_encode($freq_sent_m);

				try {

					$query = db::pconnect()->prepare("UPDATE `users` SET `friend_requests`='$friend_requests_f' WHERE `uid`='$fuid'");
					$query->execute();

					$query = db::pconnect()->prepare("UPDATE `users` SET `friend_requests_sent`='$freq_sent_m' WHERE `uid`='$muid'");
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

$obj = new action;
echo $obj->sendreq();

