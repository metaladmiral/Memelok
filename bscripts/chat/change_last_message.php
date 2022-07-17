<?php 

session_start();
include '../actions/dbh.php';

class chat extends db {
	public function change_last_message() {
		$uid_f = $_POST['uidf'];
		$uid_m = $_POST['uidm'];
		$message = $_POST['message'];
		$last_read_f = $_POST['last_read_f'];
		$last_read_m = 1;

		$dbf = "usr_".$uid_f;
		$dbm = "usr_".$uid_m;

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

				$strf_f = ord(substr($ord_f, 6, 1));
				$strf_s = ord(substr($ord_f, 7, 1));
				$ch_f = $strf_f+$strf_s;

			/* ---------- */ 
	

			/* ord_m */

				$strm_f = substr($ord_m, 6, 1);
				$strm_s = substr($ord_m, 7, 1);
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
		$db = "ch_".md5($dbname);

		/* update(m)*/

		$query = db::mconnect($dbm)->prepare("DELETE FROM `chathistory` WHERE `uid`='$uid_f'");
		$query->execute();

		$query = db::mconnect($dbm)->prepare("INSERT INTO `chathistory`(uid, db, last_message, last_read) VALUES(:uid, :db, :last_message, :last_read)");
		$query->bindParam(':uid', $uid_f);
		$query->bindParam(':db', $db);
		$query->bindParam(':last_message', $message);
		$query->bindParam(':last_read', $last_read_m);
		$query->execute();

		/* --------------------------- */

		/* update(f) */
		
		$query = db::mconnect($dbf)->prepare("DELETE FROM `chathistory` WHERE `uid`='$uid_m'");
		$query->execute();

		$query = db::mconnect($dbf)->prepare("INSERT INTO `chathistory`(uid, db, last_message, last_read) VALUES(:uid, :db, :last_message, :last_read)");
		$query->bindParam(':uid', $uid_m);
		$query->bindParam(':db', $db);
		$query->bindParam(':last_message', $message);
		$query->bindParam(':last_read', $last_read_f);
		$query->execute();		

		/* --------------------------- */

	}
	public function change_last_read() {
		$uid_m = $_POST['uidm'];
		$uid_f = $_POST['uidf'];
		$dbname = "usr_".$uid_m;

		$query = db::mconnect($dbname)->prepare("UPDATE `chathistory` SET `last_read`='1' WHERE `uid`='$uid_f'");
		$query->execute();

	}
}

$obj = new chat;
if(isset($_POST['updatelmess'])) {
	echo $obj->change_last_read();
}else {
	echo $obj->change_last_message();
}