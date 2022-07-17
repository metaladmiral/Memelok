<?php 

session_start();
include 'dbh.php';

class action extends db {
	public function follow() {
		$pid = $_POST['pid'];
		$uid = $_SESSION['UID'];

		$db = "pg_".$pid;

		$pagesfollowingarr = json_decode($_SESSION['pagesfollowingarr'], true);

		if(!in_array($pid, $pagesfollowingarr)) {

		if(!in_array($pid, json_decode($_POST['mypagesarr'], true))) { 

			try {
				$query = db::mconnect($db)->prepare("INSERT INTO `followers`(uid) VALUES('$uid')");
				$query->execute();

				$query = db::mconnect($db)->prepare("SELECT COUNT(*) as count FROM `followers`");
				$query->execute();
				$ncountflwrspg = $query->fetch(PDO::FETCH_ASSOC)['count'];

				$query = db::pageconnect()->prepare("UPDATE `info` SET `followers`='$ncountflwrspg' WHERE pid='$pid'");
				$query->execute();

				/* ---------------- */

				$mydb =db::mconnect("usr_".$uid);

				$query = $mydb->prepare("INSERT INTO `pagesfollowing`(pid) VALUES('$pid')");
				$query->execute();

				$query = $mydb->prepare("SELECT COUNT(*) as count FROM `pagesfollowing`");
				$query->execute();

				$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

				$query = db::pconnect()->prepare("UPDATE `users` SET `pages_following_count`='$count' WHERE `uid`='$uid'");
				$query->execute();

				array_push($pagesfollowingarr, $pid);
				$pagesfollowingarr = json_encode($pagesfollowingarr);
				$_SESSION['pagesfollowingarr'] = $pagesfollowingarr;

				return 1;

			}
			catch(\Exception $e) {
				return 0;
			}

		}
		else {
			return "You cannot follow your own page.";
		}

		}
		else {
			return "You already follow this page.";
		}

	}

	public function unfollow() {

		$pid = $_POST['pid'];
		$uid = $_SESSION['UID'];

		$db = "pg_".$pid;

		$pagesfollowingarr = json_decode($_SESSION['pagesfollowingarr'], true);

		if(in_array($pid, $pagesfollowingarr)) {
		
		try {
			
			$query = db::mconnect($db)->prepare("DELETE FROM `followers` WHERE `uid`='$uid'");
			$query->execute();

			$query = db::mconnect($db)->prepare("SELECT COUNT(*) as count FROM `followers`");
			$query->execute();
			$ncountflwrspg = $query->fetch(PDO::FETCH_ASSOC)['count'];

			$query = db::pageconnect()->prepare("UPDATE `info` SET `followers`='$ncountflwrspg' WHERE pid='$pid'");
			$query->execute();

			/* ---------------- */
			$mydb =db::mconnect("usr_".$uid);

			$query = $mydb->prepare("DELETE FROM `pagesfollowing` WHERE `pid`='$pid'");
			$query->execute();

			$query = $mydb->prepare("SELECT COUNT(*) as count FROM `pagesfollowing`");
				$query->execute();
	
			$count = $query->fetch(PDO::FETCH_ASSOC)['count'];

			$query = db::pconnect()->prepare("UPDATE `users` SET `pages_following_count`='$count' WHERE `uid`='$uid'");
			$query->execute();


			$key = array_search($pid, $pagesfollowingarr);
			unset($pagesfollowingarr[$key]);
			$pagesfollowingarr = array_values($pagesfollowingarr);
			$pagesfollowingarr = json_encode($pagesfollowingarr);
			$_SESSION['pagesfollowingarr'] = $pagesfollowingarr;
			return 1;

		}
		catch(\Exception $e) {
			return 0;
		}	
		
		}
		else {
			return "You already don't follow this page.";
		}

	}

}


$obj = new action;
if($_POST['action']=='follow') {
	echo $obj->follow();
}
else {
	echo $obj->unfollow();
}