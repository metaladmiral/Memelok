<?php 

session_start();
include '../actions/dbh.php';


class chat extends db {
	public function sendmess() {
		$mess = $_POST['mess'];
		$uid_f = $_POST['uidf'];
		$uid_m = $_SESSION['UID'];
		$type = $_POST['type'];

		$ord_f = ord($uid_f);
		$ord_m = ord($uid_m);

		if($ord_f>$ord_m) {
			$param_f = $uid_f;
			$param_s = $uid_m;
		}else if($ord_m>$ord_f){
			$param_f = $uid_m;
			$param_s = $uid_f;
		}
		else {

			/* ord_f */

				$strf_f = ord(substr($uid_f, 6, 1));
				$strf_s = ord(substr($uid_f, 7, 1));
				$ch_f = $strf_f+$strf_s;

			/* ---------- */ 
	

			/* ord_m */

				$strm_f = ord(substr($uid_m, 6, 1));
				$strm_s = ord(substr($uid_m, 7, 1));
				$ch_m = $strm_f+$strm_s;

			/* ---------- */ 		

			if($ch_f>$ch_m) {
				$param_f = $uid_f;
				$param_s = $uid_m;
			}else{
				$param_f = $uid_m;
				$param_s = $uid_f;
			}

		}

		$dbname = $param_f."".$param_s;
		$dbname = "ch_".md5($dbname);

		$time = date("h:i a");

		try {
			$query = db::mconnect($dbname)->prepare("INSERT INTO `chats`(`uid`, `message`, `type`, `time`) VALUES(:uidm, :message, :type, :time)");
			$query->bindParam(":uidm", $uid_m);
			$query->bindParam(":message", $mess);
			$query->bindParam(":type", $type);
			$query->bindParam(":time", $time);
			$query->execute();
			return 1;
		}
		catch(PDOException $e) {
			return $e->getMessage();
		}

	}	
}

$obj = new chat;
echo $obj->sendmess();