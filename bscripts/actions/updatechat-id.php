<?php 

session_start();
include 'dbh.php';

class actions extends db {
	public function updatechatid() {
		$id = $_POST['chatid'];
		$uid = $_SESSION['UID'];

		$query = db::pconnect()->prepare("UPDATE `users` SET `chatid`='$id' WHERE `uid`='$uid'");
		$query->execute();

		$_SESSION['chatid'] = $id;

	}
}

$obj = new actions;
$obj->updatechatid();