<?php 

session_start();
include 'dbh.php';

class createpage extends db {
	public function act() {
		$facebook = strip_tags($_POST['facebook']);
		$instagram = strip_tags($_POST['instagram']);
		$twitter = strip_tags($_POST['twitter']);
		$social = array("facebook"=>$facebook, "instagram"=>$instagram, "twitter"=>$twitter);

		$name = strip_tags(stripslashes($_POST['name']));

		if(strlen($name)>4 and strlen($name)<19) {

		if(preg_match("/[!@^%\$()=?;+#\/*\[\]\{\}<>|,' -\"]/", $name)) {
			return "char_err";
		}
		else {

		$email = strip_tags(stripslashes($_POST['email']));
		$pic = $_POST['pic'];

		$_SESSION['picaasa'] = $pic;

		$cname = $_SESSION['username'];
		$cuid = $_SESSION['UID'];
		$social = json_encode($social);

		$pre = strlen($name).strlen($email).strlen($cname);
		$pid = uniqid($pre, true);
		$pid = explode('.', $pid);
		$pid = $pid[0].rand(0, 999).$pid[1];

		$date = date("j:n:Y");

		$about = $_POST['about'];

		if((is_null($name) OR empty($name)) OR (is_null($email) OR empty($email)) OR (is_null($facebook) OR empty($facebook)) OR (is_null($instagram) OR empty($instagram)) OR (is_null($twitter) OR empty($twitter)) OR (is_null($about) OR empty($about))) {
			return 'err';
		}
		else if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
			return 'email_err';
		}
		else {

			if($this->checkemail($email)==false) {

				if($pic=='-' OR is_null($pic) OR empty($pic)) {

				}
				else {
					$pic_def = "def_".$pic;
					
					if(copy("../../temp_uploads/$pic", "../../img_pages/$pic_def")) {
						$ch = curl_init();
						curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/optimize-pagedp?imlink=$pic_def&rname=$pic");
						curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

						$output = curl_exec($ch);
						curl_close($ch);
					}



				}


				try {
					$query = db::pageconnect()->prepare("INSERT INTO `info`(pid, pic, about, page_name, creator_username, creator_uid, creation_date, email, social) VALUES(:pid, :pic, :about, :pagename, :creatorusername, :creatoruid, :creatordate, :email, :social)");
					$query->bindParam(":pid", $pid);
					$query->bindParam(":pic", $pic);
					$query->bindParam(":about", $about);
					$query->bindParam(":pagename", $name);
					$query->bindParam(":creatorusername", $cname);
					$query->bindParam(":creatoruid", $cuid);
					$query->bindParam(":creatordate", $date);
					$query->bindParam(":email", $email);
					$query->bindParam(":social", $social);

					$query->execute();

					$dbname = "usr_".$cuid;
					$query = db::mconnect($dbname)->prepare("INSERT INTO `mypages`(PID, p_name, p_dp) VALUES(:pid, :name, :pic)");
					$query->bindParam(":pid", $pid);
					$query->bindParam(":name", $name);
					$query->bindParam(":pic", $pic);
					$query->execute();


					$query = db::connect()->prepare("CREATE DATABASE `pg_$pid`");
					$query->execute();

					$query = db::mconnect('pg_'.$pid)->prepare("CREATE TABLE `posts`(
					
					id INT(20) AUTO_INCREMENT PRIMARY KEY,
					mid VARCHAR(250) NOT NULL
			
					)");
					$query->execute();

					$query = db::mconnect('pg_'.$pid)->prepare("CREATE TABLE `followers`(
					
					id INT(20) AUTO_INCREMENT PRIMARY KEY,
					uid VARCHAR(250) NOT NULL
			
					)");
					$query->execute();

					return 1;

				}
				catch(\Exception $e) {
					return $e->getMessage();
				}

			}
			else {
				return 'nemail_err';
			}

			}

		}
		else {
			return 'len_err';
		}

		}

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

$obj = new createpage;
echo $obj->act();