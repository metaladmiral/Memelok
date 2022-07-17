<?php 

session_start();
include 'dbh.php';

class actions extends db {
	public function chkid() {
		$uid = $_SESSION['UID'];
		$query = db::pconnect()->prepare("SELECT sessid FROM `users` WHERE `uid`='$uid'");
		$query->execute();

		$fetch = $query->fetch(PDO::FETCH_ASSOC);

		return $fetch['sessid'];

	}
}

$obj = new actions;
echo $obj->chkid();