<?php 

session_start();
include '../actions/dbh.php';

class load extends db {
	public function get() {

		if(is_null($_SESSION['friends']) OR empty($_SESSION['friends']) OR strtolower($_SESSION['friends'])=='null' OR count(json_decode($_SESSION['friends']))==0) {
			?>
			
			<center><span style="color: gray;font-size: 13px;position: relative;top: 10px;margin: 0 auto;">You do not have any friends currently.</span></center>

			<?php
		}
		else {

			$friends = json_decode($_SESSION['friends'], true);
			$offset = $_POST['offset'];

			$nfriends = array();
			$counter = 0;

			for($i=$offset;$i<=count($friends)-1;$i++) {
				if($counter<=10) {
					$counter++;
					array_push($nfriends, $friends[$i]);
				}
				else {
					exit;
				}
				
			}

			$offset += 10;

			$imp = "'".implode("', '", $nfriends)."'";
			$query = db::pconnect()->prepare("SELECT pic, username, fullname, uid FROM `users` WHERE `uid` IN(".$imp.")");
			$query->execute();

			while($row=$query->fetch(PDO::FETCH_ASSOC)) {
				$username= $row['username'];
				$uid = $row['uid'];
				$fullname = $row['fullname'];
				$dp = $row['pic'];

				?>
				
				<div class="friends_item" id="id<?php echo $username; ?>"><div class="dp"><img src="img_users/<?php echo $dp; ?>" alt=""></div><div class="details"><div class="username"><span><?php echo $username; ?></span></div><div class="fullname"><span><?php echo $username; ?></span></div></div><div class="action"><i class="fas fa-times" onclick="unfriend('<?php echo $uid; ?>', '<?php echo $username; ?>');" style="color: #333;font-size: 18px;cursor: pointer;"></i></div><div class="spinner loader"></div><button class="unfriend_confirm" id="btn">Unfriended &#10004;</button></div>
				

				<?php

			}

			if(count($friends)-$offset>0) {
				?>
				
				<br>
				<center><span style="text-decoration: underline;color: blue;font-size: 13px;cursor: pointer;" onclick="loadfriends('<?php echo $offset; ?>', '1')">Load More</span></center>
				
				<?php
			}

		}

	}
}

$load = new load;
echo $load->get();	