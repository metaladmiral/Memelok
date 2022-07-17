<?php 

session_start();
include '../actions/dbh.php';

class chat extends db {
	public function loadchats($uid_f, $uid_m) {

		$offset = $_POST['offset'];
		$limit = $_POST['limit'];
		$allmesscount = $_POST['allmesscount'];
		

		$ord_f = ord($uid_f);
		$ord_m = ord($uid_m);

		if($ord_f>$ord_m) {
			$param_f = $uid_f;
			$param_s = $uid_m;
		}else if($ord_m>$ord_f){
			$param_f = $uid_m;
			$param_s = $uid_f;
		}
		else {

			/* ord_f */

				$strf_f = ord(substr($uid_f, 6, 1));
				$strf_s = ord(substr($uid_f, 7, 1));
				$ch_f = $strf_f+$strf_s;

			/* ---------- */ 
	

			/* ord_m */

				$strm_f = ord(substr($uid_m, 6, 1));
				$strm_s = ord(substr($uid_m, 7, 1));
				$ch_m = $strm_f+$strm_s;

			/* ---------- */ 		

			if($ch_f>$ch_m) {
				$param_f = $uid_f;
				$param_s = $uid_m;
			}else{
				$param_f = $uid_m;
				$param_s = $uid_f;
			}

		}

		$dbname = $param_f."".$param_s;
		$dbname = "ch_".md5($dbname);

		try {
			$query = db::connect()->prepare("USE `".$dbname."`");
			$query->execute();
		}
		catch(PDOException $e) {
			$query = db::connect()->prepare("CREATE DATABASE `".$dbname."`");
			$query->execute();
			$query = db::mconnect($dbname)->prepare("

				CREATE TABLE `chats`(
					`id` INT(50) PRIMARY KEY  NOT NULL AUTO_INCREMENT,
					`uid` VARCHAR(80) NOT NULL,
					`message` VARCHAR(300) NOT NULL,
					`type` VARCHAR(250) NOT NULL,
					`time` VARCHAR(60)
				)
				
			");

			$query->execute();

		}

		$query = db::mconnect($dbname)->prepare("SELECT * from `chats` ORDER BY `id` DESC LIMIT $offset, $limit");
		$query->execute();

		if($allmesscount!=0 || !is_null($allmesscount)) {
			$countquery = db::mconnect($dbname)->prepare("SELECT COUNT(*) as count FROM `chats` ORDER BY `id` DESC");
			$countquery->execute();
			$allmesscount = $countquery->fetch(PDO::FETCH_ASSOC)['count'];
		}

		$rowcount = $query->rowCount();

		if($rowcount==0) {
			?>	
			<br><div style="width: auto;height: auto;transform: rotate(180deg);direction: ltr;"><span style="color: gray;font-size: 12px;position: relative;top: 8px;">Start a conversation with <?php echo $_POST['username']; ?>. Say Hi! </span></div>
			<?php
		}
		else {
			while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
				$message = $row['message'];
				$type = $row['type'];
				$time = $row['time'];
				$uid = $row['uid'];

				if($uid==$_SESSION['UID']) {

				?>
				
				<li><div class="mess m_" data-time='<?php echo $time; ?>'><span><?php echo $message; ?></span></div></li>


				<?php

				}
				else {
	
				?>

				<li><span><div class="mess f_" data-time='<?php echo $time; ?>'><span><?php echo $message; ?></span></div></li>

				<?php
	
				}

			}

			if($allmesscount-$limit>0) {
				
				$offset += 15;
				$limit += 15;
				$rand = uniqid('loadid_');

				?>
				
				<br><center><div id='<?php echo $rand; ?>' style="transform: rotate(180deg);"><span style="color: blue;text-decoration: underline;font-size: 12px;cursor: pointer;" onclick="loadchat('<?php echo $uid_f; ?>', '<?php echo $uid_m; ?>', '<?php echo $_POST["username"]; ?>', '<?php echo $offset; ?>', '<?php echo $limit; ?>', '<?php echo $allmesscount; ?>', '<?php echo $rand; ?>')">Load More</span></div></center>

				<?php
			}

		}

	}
}

$obj = new chat;
$obj->loadchats($_POST['uid_f'], $_POST['uid_m']);