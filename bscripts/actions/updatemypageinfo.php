<?php 

session_start();
include 'dbh.php';

class update extends db {
	public function act() {
		$nname = stripslashes(strip_tags($_POST['name']));
		$nabout = stripslashes(strip_tags($_POST['about']));
		$nphoto = stripslashes(strip_tags($_POST['photo']));
		$nemail = stripslashes(strip_tags($_POST['email']));
		$nsocial = stripslashes(strip_tags($_POST['social']));
		$pid = $_POST['pid'];

		$uid = $_SESSION['UID'];

		try {
			$nphoto = explode('/', $nphoto)[1];

			copy("../../temp_uploads/$nphoto", "../../img_pages/$nphoto");

		}
		catch(\Exception $e) {
			$nphoto = $_POST['photo'];
		}

		$getquery = db::pageconnect()->prepare("SELECT email FROM `info` WHERE `pid`='$pid'");
		$getquery->execute();
		$fetch = $getquery->fetch(PDO::FETCH_ASSOC);

		$cemail = $fetch['email'];

		$ret = "";
		$dbname = "usr_".$uid;

		if($cemail==$nemail) {

			$query = db::pageconnect()->prepare("UPDATE `info` SET `page_name`=:name, `about`=:about, `pic`=:pic, `social`=:social WHERE `pid`='$pid'");
			$query->bindParam(":name", $nname);
			$query->bindParam(":about", $nabout);
			$query->bindParam(":pic", $nphoto);
			$query->bindParam(":social", $nsocial);
			$query->execute();

			$querym = db::mconnect($dbname)->prepare("UPDATE `mypages` SET `p_name`=:name, `p_dp`=:pic WHERE `pid`='$pid'");
			$querym->bindParam(":name", $nname);
			$querym->bindParam(":pic", $nphoto);
			$querym->execute();

			$ret = 1;

		}
		else {
			if($this->checkemail($nemail)) {
				$ret = 'Email is already registered for a page.';
			}
			else {
				$query = db::pageconnect()->prepare("UPDATE `info` SET `page_name`=:name, `about`=:about, `pic`=:pic, `social`=:social, `email`=:email WHERE `pid`='$pid'");
				$query->bindParam(":name", $nname);
				$query->bindParam(":about", $nabout);
				$query->bindParam(":pic", $nphoto);
				$query->bindParam(":social", $nsocial);
				$query->bindParam(":email", $nemail);
				$query->execute();
				

				$ret = 1;

			}
		}

		return $ret;

	}

	public function checkemail($email) {

		$query = db::pageconnect()->prepare("SELECT * FROM `info` WHERE `email`='$email'");
		$query->execute();

		if($query->rowCount()>0) {
			return true;
		}
		else {
			return false;
		}

	}

}

$update = new update;
echo $update->act();