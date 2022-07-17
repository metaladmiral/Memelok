<?php 

ob_start();
session_start();
include '../actions/dbh.php';

class chat extends db {
	public function loadchathistory() {
		$uidm = $_SESSION['UID'];
		$dbname = "usr_".$uidm;

		$offset = $_GET['offset'];
		$limit = $_GET['limit'];
		$total = $_GET['total'];

		if($total=='null') {
			$query = db::mconnect($dbname)->prepare("SELECT ch.`uid`, ch.`db`, ch.`last_message`, ch.`last_read`, pt.`pic`, pt.`username` FROM `chathistory` as ch, people.`users` as pt WHERE pt.`uid`=ch.`uid`");
			$query->execute();
			$total = $query->rowCount();

		}

		if($total>0) {

		$query = db::mconnect($dbname)->prepare("SELECT ch.`uid`, ch.`db`, ch.`last_message`, ch.`last_read`, pt.`pic`, pt.`username`, pt.`chatid`, pt.`fullname` FROM `chathistory` as ch, people.`users` as pt WHERE pt.`uid`=ch.`uid` ORDER BY ch.`id` DESC LIMIT $offset, $limit");
		$query->execute();

		if($query->rowCount() > 0) {

			while($row = $query->fetch(PDO::FETCH_ASSOC)) {
				$uid = $row['uid'];
				$db = $row['db'];
				$last_message = $row['last_message'];
				$last_read = $row['last_read'];
				$dp = $row['pic'];
				$username = $row['username'];
				$fullname = $row['fullname'];
				$cid = $row['chatid'];

				$rand = uniqid('chath_');

				?>
				
				<div class="item_chat" id='<?php echo $rand; ?>' cid='<?php echo $cid; ?>' uid='<?php echo $uid; ?>' onclick='openchat("<?php echo $username; ?>", "<?php echo $uid; ?>", "<?php echo $dp; ?>", "<?php echo $fullname; ?>", "<?php echo $rand; ?>");'><div class="dp"><img src="img_users/<?php echo $dp; ?>" alt=""></div><div class="details"><ul><li><span class="username"><?php echo $username; ?></span></li><li class="last_message"><?php echo $last_message; ?></li></ul></div>
				<?php if($last_read==0) { ?><div class="unread"><span style="color: var(--hover-color);font-size: 22px;">&bull;</span></div><?php } ?></div>

				<?php
			}

			if($total-$limit>0) {

				
				$offset += 12;
				$limit += 12;

				?>
				<br>
				<center><span style="font-size: 12px;color: blue;text-decoration: underline;cursor: pointer;" onclick="loadchathistory(<?php echo $offset; ?>, <?php echo $limit; ?>, <?php echo $total; ?>);">Load More</span></center>
				<?php
			}

		}
		else {
			echo "done";
		}

		}
		else {
			echo 0;
		}


	}

	function loadlastmessagechk() {

		$uidm = $_SESSION['UID'];
		$dbname = "usr_".$uidm;

		$lmess = rawurldecode($_GET['lmess']);
		$query = db::mconnect($dbname)->prepare("SELECT ch.`uid`, ch.`db`, ch.`last_message`, ch.`last_read`, pt.`pic`, pt.`username`, pt.`chatid`, pt.`fullname` FROM `chathistory` as ch, people.`users` as pt WHERE pt.`uid`=ch.`uid` ORDER BY ch.`id` DESC LIMIT 0,1");
		$query->execute();
		$row = $query->fetch(PDO::FETCH_ASSOC);

		if($row['last_message']==$lmess) {
			echo 0;
		}
		else {

			$uid = $row['uid'];
			$db = $row['db'];
			$last_message = $row['last_message'];
			$last_read = $row['last_read'];
			$dp = $row['pic'];
			$username = $row['username'];
			$fullname = $row['fullname'];
			$cid = $row['chatid'];

			$rand = uniqid('chath_');

			?>
			
			<div class="item_chat" id='<?php echo $rand; ?>' cid='<?php echo $cid; ?>' uid='<?php echo $uid; ?>' onclick='openchat("<?php echo $username; ?>", "<?php echo $uid; ?>", "<?php echo $dp; ?>", "<?php echo $fullname; ?>", "<?php echo $rand; ?>");'><div class="dp"><img src="img_users/<?php echo $dp; ?>" alt=""></div><div class="details"><ul><li><span class="username"><?php echo $username; ?></span></li><li class="last_message"><?php echo $last_message; ?></li></ul></div>
				<?php if($last_read==0) { ?><div class="unread"><span style="color: var(--hover-color);font-size: 22px;">&bull;</span></div><?php } ?></div>

			<?php

			$ob_prev_data = ob_get_contents();
			ob_clean();

			$resp = array("html"=>$ob_prev_data, "uid"=>$uid);
			echo json_encode($resp);

			ob_end_flush();



		}

	}

}

$obj = new chat;
if(isset($_GET['lmess'])) {
	$obj->loadlastmessagechk();
}	
else {
	$obj->loadchathistory();
}