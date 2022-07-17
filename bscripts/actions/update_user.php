<?php 

include 'dbh.php';
session_start();

class update extends db {
	public function act() {
		$which = $_POST['which'];
		$uid=$_SESSION['UID'];

		$query = "";
		// general
		if($which=='general') {
			$username = stripslashes(strip_tags($_POST['username']));
			$bio = stripslashes(strip_tags($_POST['bio']));
			$birthday = $_POST['birthday'];
			$toupdateusername = $_POST['toupdateusername'];

			try {

				if($toupdateusername=='1') {

					if($username!=$_SESSION['username']) {
						$query = db::pconnect()->prepare("UPDATE `users` SET `username`='$username' WHERE uid='$uid'");
						$query->execute();
					}

					$_SESSION['username'] = $username;

				}

				if($bio!=$_SESSION['bio']) {
					$query = db::pconnect()->prepare("UPDATE `users` SET `bio`='$bio' WHERE uid='$uid'");
					$query->execute();
					$_SESSION['bio'] = $bio;
				}

				if($bio!=$_SESSION['birthday']) {
					$query = db::pconnect()->prepare("UPDATE `users` SET `birthday`='$birthday' WHERE uid='$uid'");
					$query->execute();
					$_SESSION['birthday'] = $birthday;
				}

				return $toupdateusername;

			}catch(PDOException $e) {
				return $e->getMessage();
			}

		}
		// social
		else if($which=='social'){

			$social = json_decode($_SESSION['socialmedia'], true);

			$facebook_curr = $social["facebook"];
			$twitter_curr = $social['twitter'];
			$instagram_curr = $social['instagram'];

			$facebook = $_POST['facebook'];
			$twitter = $_POST['twitter'];
			$instagram = $_POST['instagram'];

			$socialmedia_new = array("facebook"=>"", "twitter"=>"", "instagram"=>""); 

			if(filter_var($facebook, FILTER_VALIDATE_URL))  {
				if($facebook!=$facebook_curr) {
					$socialmedia_new["facebook"] = $facebook;
				}
				else {
					$socialmedia_new["facebook"] = "-";	
				}

			}
			else {
				$socialmedia_new["facebook"] = "-";	
			}

			if(filter_var($twitter, FILTER_VALIDATE_URL))  {
				if($twitter!=$twitter_curr) {
					$socialmedia_new["twitter"] = $twitter;	
				}
				else {
					$socialmedia_new["twitter"] = "-";
				}
			}
			else {
				$socialmedia_new["twitter"] = "-";
			}

			if(filter_var($instagram, FILTER_VALIDATE_URL))  {
				if($instagram!=$instagram_curr) {
					$socialmedia_new["instagram"] = $instagram;
				}
				else {
					$socialmedia_new["instagram"] = "-";
				}
			}
			else {
				$socialmedia_new["instagram"] = "-";
			}

			$socialmedia_new = json_encode($socialmedia_new, JSON_UNESCAPED_SLASHES);
			$_SESSION['socialmedia'] = $socialmedia_new;

			try {
				$query = db::pconnect()->prepare("UPDATE `users` SET socialmedia='$socialmedia_new' WHERE `uid`='$uid'");
				$query->execute();
				return '1';
			}
			catch(PDOException $e) {
				return $e->getMessage();
			}

		}	
		// security
		else if($which=='security') {

			$current = md5(stripslashes($_POST['current']));
			$new = md5(stripslashes($_POST['new']));

			if($current==$_SESSION['password']) {
				try {
					$query = db::pconnect()->prepare("UPDATE `users` SET password='$new' WHERE `uid`='$uid'");
					$query->execute();
					$_SESSION['password'] = $new;
					return '1';
				}
				catch(PDOException $e) {
					return $e->getMessage();
				}
			}
			else {
				return 'no';
			}

		}

	}
}


$obj = new update;
echo $obj->act();