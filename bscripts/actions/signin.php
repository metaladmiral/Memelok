<?php

include 'dbh.php';

session_start();


class signin extends db {
	public function act() {
		if($_SERVER['SERVER_NAME']=='::1') {
			$server = "localhost";
		}
		else {
			$server = $_SERVER['SERVER_NAME'];
		}
		$login_cred = strip_tags($_POST['login_cred']);
		$login_pass = md5(strip_tags($_POST['login_pass']));

		$query = db::pconnect()->prepare("SELECT * FROM `users` WHERE email='$login_cred'");
		$query->execute();

		if($query->rowCount()>0) {

			while($result = $query->fetch(PDO::FETCH_ASSOC)) {
				$lid = $result['lid'];
				$uid = $result['uid'];
				$pass = $result['password'];
				$pic = $result['pic'];
				$bio = $result['bio'];
				$birthday = $result['birthday'];
				$username = $result['username'];
				$email = $result['email'];
				$friends_count = $result['friend_count'];
				$pages_following_count = $result['pages_following_count'];
				$dmode = $result['darkmode_toggle'];
				$socialmedia = $result['socialmedia'];
				$fullname = $result['fullname'];
				$interests = $result['interests'];
			}

			if($pass == $login_pass) {
				setcookie('LID', $lid, time()+(86400*61), "/", $server, false, true);
				$_SESSION['UID'] = $uid;
				$_SESSION['pic'] = $pic;

				$_SESSION['socialmedia'] = $socialmedia;
				$_SESSION['password'] = $pass;
				$_SESSION['bio'] = $bio;
				$_SESSION['birthday'] = $birthday;
				$_SESSION['username'] = $username;
				$_SESSION['email'] = $email;
				$_SESSION['fullname'] = $fullname;
				$_SESSION['pages_following_count'] = $pages_following_count;
				$_SESSION['darkmode_toggle'] = $dmode;
				$_SESSION['interests'] = $interests;
				$_SESSION['friends_count'] = $friends_count;
				return 1;
			}
			else {
				return 0;
			}


		}
		else {
			return 0;
		}

	}
}


$obj = new signin;
echo $obj->act();