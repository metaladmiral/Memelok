<?php 

session_start();
include '../actions/dbh.php';

class chat extends db {
	public function getcid() {
		$uid = $_POST['uid'];
		$query = db::pconnect()->prepare("SELECT chatid as cid FROM `users` WHERE `uid`='$uid'");
		$query->execute();

		$fetch = $query->fetch(PDO::FETCH_ASSOC);
		$cid = $fetch['cid'];

		$query = db::pconnect()->prepare("SELECT COUNT(*) as count FROM people.`users` WHERE last_activity > DATE_SUB(NOW(), INTERVAL 7 SECOND) AND `uid`='$uid'");
		$query->execute();

		$count = $query->fetch(PDO::FETCH_ASSOC)['count'];
		if($count>0) {
			$isonline = 1;
		}
		else {
			$isonline = 0;
		}

		$resp = array("online"=>$isonline, "cid"=>$cid);		

		return json_encode($resp);

	}
}

$chat = new chat;
echo $chat->getcid();