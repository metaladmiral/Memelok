<?php 

include 'dbh.php';

class signup {
	
	public $db;
	
	public function act() {

		$this->db = new db;

		$fullname = stripslashes(strip_tags($_POST['fullname']));
		$username = stripslashes(strip_tags($_POST['username']));
		$pic = stripslashes(strip_tags($_POST['pic']));
		$email = stripslashes(strip_tags($_POST['email']));;
		$gender = stripslashes(strip_tags($_POST['gender']));
		$bio = stripslashes(strip_tags($_POST['bio']));
		$birthday = $_POST['day']."/".$_POST['month']."/".$_POST['year'];
		$password = $_POST['password'];
		

		$tags = json_decode($_POST['tags'], true);

		if(sizeof($tags)==0) {
			$tags = "random";
		}
		else {
			$tags = json_encode($tags);
		}
		

		$split = str_split(strtolower($fullname));
		$prefix = $split[0].$split[1].strlen($username).strlen($pic).strlen($password);
		$uid = uniqid($prefix, true);
		$split_uid = explode('.', $uid);
		$uid = $split_uid[0].rand(1, 99).$split_uid[1];

		$lid = md5($uid);

		$password = md5($password);

		if($pic!=0) {
			$pic_def = "def_".$pic;
			if(copy("/opt/lampp/htdocs/temp_uploads/$pic", "/opt/lampp/htdocs/img_users/$pic_def")) {
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/optimize-userdp?imlink=$pic_def&rname=$pic");
				curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

				$output = curl_exec($ch);
				curl_close($ch);
			}
			else {
				$pic = "male_avatar.jpg";
			}
		}
		else {
			$pic = "male_avatar.jpg";
		}

		if(empty($fullname)==true OR empty($username)==true OR empty($pic)==true OR empty($email)==true OR empty($gender)==true OR empty($bio)==true OR empty($birthday)==true OR empty($password)==true) {
			throw new Exception("2");
		}
		else if($this->checkemail($email)==true){
			throw new Exception("3");
		}
		else{
			if(filter_var($email, FILTER_VALIDATE_EMAIL)) {

				try {
					$query = $this->db->pconnect()->prepare("INSERT INTO users(lid, uid, fullname, username, pic, email, gender, bio, birthday, password, interests) VALUES(:lid, :uid, :fullname, :username, :pic, :email, :gender, :bio, :birthday, :password, :interests)
						");		

					$query->bindParam(":lid", $lid);
					$query->bindParam(":uid", $uid);
					$query->bindParam(":fullname", $fullname);
					$query->bindParam(":username", $username);
					$query->bindParam(":pic", $pic);
					$query->bindParam(":email", $email);
					$query->bindParam(":gender", $gender);
					$query->bindParam(":bio", $bio);
					$query->bindParam(":birthday", $birthday);
					$query->bindParam(":password", $password);
					$query->bindParam(":interests", $tags);

					$query->execute();

					$dbname = "usr_".$uid;
					$dbquery = $this->db->connect()->prepare("CREATE DATABASE ".$dbname.";");
					$dbquery->execute();

					$mdbquery = $this->db->mconnect($dbname)->prepare("

						CREATE TABLE `saved`(
							`mid` VARCHAR(250) NOT NULL
						);

						CREATE TABLE `notifications`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`read` INT(2) DEFAULT 0,
							`time` VARCHAR(250) NOT NULL,
							`content` VARCHAR(250) NOT NULL, 
							`s_UID` VARCHAR(250) NOT NULL,
							`s_dp` VARCHAR(250) NOT NULL,
							`link` VARCHAR(350) DEFAULT NULL
						);

						CREATE TABLE `pagesfollowing` (
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`pid` VARCHAR(250) NOT NULL
						);

						CREATE TABLE `mypages`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`PID` VARCHAR(250) NOT NULL,
							`p_name` VARCHAR(250) NOT NULL,
							`p_dp` VARCHAR(250) NOT NULL
						);

						CREATE TABLE `friends`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`uid` VARCHAR(90) NOT NULL
						);

						CREATE TABLE `friend_requests`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`uid` VARCHAR(90) NOT NULL
						);

						CREATE TABLE `friend_requests_sent`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`uid` VARCHAR(90) NOT NULL
						);

						CREATE TABLE `likes`(
							`mid` VARCHAR(40) NOT NULL
						);

						CREATE TABLE `chathistory`(
							`id` INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
							`uid` VARCHAR(50) NOT NULL,
							`db` VARCHAR(90) NOT NULL, 
							`last_message` VARCHAR(100),
							`last_read` INT(1)
						);

					");

					$mdbquery->execute();

				}
				catch(\Exception $e) {
					throw new Exception($e->getMessage());					
				}

			}
			else {
				throw new Exception("An error occured.");		
			}
		}

	}
 
	public function checkemail($email) {

		$query = $this->db->pconnect()->prepare("SELECT * FROM `users` WHERE `email`='$email'");
		$query->execute();

		if($query->rowCount()>0) {
			return true;
		}
		else {
			return false;
		}

	}

}	

$obj = new signup;
try {
	$obj->act();
	echo '1';
}
catch(\Exception $e) {
	if($e->getMessage()=='3') {
		echo "The email you entered already exists.";
	}
	else {
		echo $e->getMessage();
	}
}