<?php

include 'dbh.php';
session_start();

class unfriend extends db {
	public function act() {
		
		$fuid = $_POST['key'];
		$muid = $_SESSION['UID'];
		$friends = count(json_decode($_POST['friends'], true))-1;


		$query = db::mconnect("usr_".$fuid)->prepare("SELECT COUNT(*) as count FROM `friends`");
		$query->execute();
		
		$friend_count_friend = (int)($query->fetch()['count'][0])-1;

		$dbm = "usr_".$muid;
		$dbf = "usr_".$fuid;

		try {

			$query = db::pconnect()->prepare("UPDATE `users` SET `friend_count`='$friends' WHERE `uid`='$muid'");
			$query->execute();

			$query = db::pconnect()->prepare("UPDATE `users` SET `friend_count`='$friend_count_friend' WHERE `uid`='$fuid'");
			$query->execute();

			$query = db::mconnect($dbm)->prepare("DELETE FROM `friends` WHERE `uid`='$fuid'");
			$query->execute();

			$query = db::mconnect($dbf)->prepare("DELETE FROM `friends` WHERE `uid`='$muid'");
			$query->execute();

			return 1;

		}
		catch(\Exception $e) {
			return $e->getMessage();
		}

		// ------

	}
}

$obj = new unfriend;
echo $obj->act();