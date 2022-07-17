<?php 

session_start();
include 'dbh.php';

class action extends db {

	public function reactlike() {
		try {
			
			$mid = $_POST['mid'];
			$db = "usr_".$_SESSION['UID'];
			$query = db::mconnect($db)->prepare("INSERT INTO `likes`(mid) VALUES('$mid')");
			$query->execute();

			$query = db::postsconnect()->prepare("SELECT like_count as lc FROM `meme` WHERE `mid`='$mid'");
			$query->execute();
			$lc = (int)$query->fetch(PDO::FETCH_ASSOC)['lc']+1;

			$query = db::postsconnect()->prepare("UPDATE `meme` SET like_count='$lc' WHERE `mid`='$mid'");
			$query->execute();

			return 1;

		}catch(\Exception $e) {
			return $e->getMessage();
		}

	}

	public function reactdislike() {
		try {
			$mid = $_POST['mid'];
			$db = "usr_".$_SESSION['UID'];
			$query = db::mconnect($db)->prepare("INSERT INTO `likes`(mid) VALUES('$mid')");
			$query->execute();

			$query = db::postsconnect()->prepare("SELECT dislike_count as lc FROM `meme` WHERE `mid`='$mid'");
			$query->execute();
			$lc = (int)$query->fetch(PDO::FETCH_ASSOC)['lc']+1;

			$query = db::postsconnect()->prepare("UPDATE `meme` SET dislike_count='$lc' WHERE `mid`='$mid'");
			$query->execute();
			
			return 1;

		}
		catch(\Exception $e) {
			return $e->getMessage();
		}
	}

}


$obj = new action;
if($_POST['which']=='positive') {
	echo $obj->reactlike();
}
else {
	echo $obj->reactdislike();
}