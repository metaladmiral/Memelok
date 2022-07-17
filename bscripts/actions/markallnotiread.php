<?php 

session_start();
include 'dbh.php';

class actions extends db {
	public function markallread() {

		$dbname = "usr_".$_SESSION['UID'];
		try {
			$query = db::mconnect($dbname)->prepare("UPDATE `notifications` SET read='1' ");
			$query->execute();
			return 1;
		}
		catch(PDOException $e) {
			return $e->getMessage();
		}
	}
}

$obj = new actions;
echo $obj->markallread();

