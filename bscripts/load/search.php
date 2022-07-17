<?php 

session_start();
include '../actions/dbh.php';


class load extends db {

	public function search($which) {
				
		$val = $_POST['query'];
		$html = "";

		if(is_null($_SESSION['friends']) OR empty($_SESSION['friends']) OR strtolower($_SESSION['friends'])=='null') {
			$mfriends = array();
		}
		else {
			$mfriends = json_decode($_SESSION['friends'], true);
		}

		if($which=='people') {

			$offset = $_POST['offset'];
			$limit = $offset+10;

			$query = db::pconnect()->prepare("SELECT uid, fullname, username, pic, bio, birthday, socialmedia FROM `users` WHERE MATCH(username, fullname) AGAINST('+*".$val."*' IN BOOLEAN MODE) LIMIT $offset, $limit");
			$query->execute();
			
			$offset += 10;

			if($query->rowCount()>0) {

		
				while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
					$uid = $row['uid'];
					$fullname = $row['fullname'];
					$username = $row['username'];
					$pic = $row['pic'];
					$bio = $row['bio'];
					$birthday = $row['birthday'];
					$social = json_decode($row['socialmedia'], true);
					$facebook = $social['facebook'];
					$instagram = $social['instagram'];
					$twitter = $social['twitter'];

					$id = uniqid('');

					?>
					
					<div class="search_people_bar" id='spb_<?php echo $id; ?>'>
						
						<div class="dp">
							
							<div class="usr_profile_<?php echo $id; ?>" id='qkprofile' onmouseover="document.querySelector('.usr_profile_<?php echo $id; ?>').style.display = 'block';" onmouseout="document.querySelector('.usr_profile_<?php echo $id; ?>').style.display = 'none';">
								
								<div class="top">
									<img src="img_users/<?php echo $pic; ?>" alt="">
									<ul style="list-style: none;margin-left: 5px;">
										<li><b style="font-size: 15px;font-family: 'Signika';"><?php echo $username; ?></b></li>
										<li><span style="color: gray;font-size: 11px;">(<?php echo $fullname; ?>)</span></li>
									</ul>
								</div>		

								<div class="social">
									<center>
									
										<ul>
											<?php 

											if($facebook!='-') {$f_style='cursor: pointer;';}

											 ?>
											<li style="<?php echo $f_style; ?>"><div class="facebook"><i class="fab fa-facebook-f"></i></div></li>
											<li><div class="instagram"><i class="fab fa-instagram"></i></div></li>
											<li><div class="twitter"><i class="fab fa-twitter"></i></div></li>
									
										</ul>
									
									</center>
								</div>

								<div class="bio">
									<span style="font-size: 12px;color: gray"><?php echo $bio; ?></span>
								</div>

								<div class="birthday">
									<center>
									<i class="fad fa-birthday-cake" style="color: #ff4d6a;font-size: 19px;"></i> &nbsp; <span style="color: #222;"><?php echo $birthday; ?></span>
								</center>
								</div>

							</div>
							
							<img src="img_users/<?php echo $pic; ?>" onmouseover="document.querySelector('.usr_profile_<?php echo $id; ?>').style.display = 'block';" onmouseout="document.querySelector('.usr_profile_<?php echo $id; ?>').style.display = 'none';" alt="">
						
						</div>

						<div class="details">
						
							<p class="username" style="font-size: 13px;font-weight: bold;color: #222;letter-spacing: 0.1px;display: block;"><?php echo $username; ?></>

								<br>
							
							<p class="fullname" style="font-size: 12px;color:gray;">(<?php echo $fullname; ?>)</span>

						</div>
						
						<?php if(!in_array($uid, $mfriends)) { ?>

						<div class="send_req">
							<i class="fas fa-user-plus" style="font-size: 20px;cursor: pointer;color: #222;position: absolute;right:0px;" onclick="send_freq('<?php echo $id; ?>', '<?php echo $uid; ?>');"></i>
							<div class="spinner loader" style="width:10px;height: 10px;position: absolute;right:5px;"></div>
							<button id='friend_req_sent' style="display: none;">Friend Request Sent <i class="fas fa-check" style="font-size: 11px;"></i></button>
						</div>

						<?php } ?>

					</div>

					<?php

					if($query->rowCount()>$limit) {
						?>

						<br>
						<center><span style="text-decoration: underline;color: blue;font-size: 13px;" onclick="search('<?php echo $_POST['query']; ?>', 'people', '<?php echo $offset; ?>', '1');">Load More</span><center>

						<?php

					}

				}
		
			}	
			else {
				?> <center><span style="position: relative;top: 10px;-webkit-font-smoothing: antialiased;">No Results Found.</span></center> <?php
			}

		}
		else if($which=='pages'){
			

			$offset = $_POST['offset'];
			$limit = $offset+10;

			$query = db::pageconnect()->prepare("SELECT pid, pic, page_name, followers, social FROM `info` WHERE MATCH(page_name) AGAINST('+*".$val."*' IN BOOLEAN MODE) LIMIT $offset, $limit");
			$query->execute();

			$offset += 10;

			if($query->rowCount()>0) {

				if(is_null($_POST['mypagesarr']) OR empty($_POST['mypagesarr'])) {
					$mypagesarr = array();
				}
				else {
					$mypagesarr = json_decode($_POST['mypagesarr'], true);
				}				


				if(is_null($_POST['pagesfollowing']) OR empty($_POST['pagesfollowing'])) {
					$pagesfollowing = array();
				}
				else {
					$pagesfollowing = json_decode($_POST['pagesfollowing'], true);
				}


				while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
			
				$pid = $row['pid'];
				$pic = $row['pic'];
				$page_name = $row['page_name'];
				$followers_count = $row['followers'];
				$social = json_decode($row['social'], true);

				$id = uniqid().rand(0, 99999);

				?>
				
					<div class="search_page_bar" id='spageb_<?php echo $id; ?>'>
						
						<div class="dp">
							<img src="img_pages/<?php echo $pic; ?>" alt="">
						</div>

						<div class="details">
						
							<div class="name">
								<a href="/page/<?php echo $page_name; ?>" target="_blank" style='text-decoration: none;'><p class="username" style="font-size: 13px;font-weight: bold;color: #222;letter-spacing: 0.1px;display: block;text-decoration: none;"><?php echo $page_name; ?></p></a>
							</div>

							<div class="social">
								<?php 

								if($social['facebook']=='-') {
									echo '<i class="fab fa-facebook-f"></i>';
								}
								else {
									echo '<i class="fab fa-facebook-f" name="avail"></i>';
								}

								?>

								<?php 

								if($social['twitter']=='-') {
									echo '<i class="fab fa-twitter"></i>';
								}
								else {
									echo '<i class="fab fa-twitter" name="avail"></i>';
								}

								?>	

								<?php 

								if($social['instagram']=='-') {
									echo '<i class="fab fa-instagram"></i>';
								}
								else {
									echo '<i class="fab fa-instagram" name="avail"></i>';
								}

								?>

							</div>
						
						</div>

						<div class="followicon">
							
							<div class="main" style="">
								<span class="followcount" style="color: gray;font-size: 13px;"><?php echo $followers_count; ?></span> &nbsp;&nbsp; 

								<?php if(!in_array($pid, $mypagesarr)) { if(!in_array($pid, $pagesfollowing)) { ?> <span style="font-size: 10px;">&#9679;</span> &nbsp;&nbsp;<i class="far fa-wifi" style="font-size: 14px;color: #333;cursor: pointer;transform: rotate(25deg);position: relative;top: 1.25px;" onclick="followpage('<?php echo $pid; ?>', '<?php echo $id; ?>');"></i><?php }else { ?><span style="font-size: 10px;">&#9679;</span> &nbsp;&nbsp;<i class="far fa-wifi" onclick="unfollowpage('<?php echo $pid; ?>', '<?php echo $id; ?>');" style="font-size: 14px;color: var(--hover-color);font-weight: bold;cursor: pointer;transform: rotate(25deg);position: relative;top: 1.25px;"></i><?php } } ?>
							</div>

							<div class="loader spinner"></div>

						</div>

					</div>
					
					<?php

					if($query->rowCount()>$offset) {

					?>
					<br>

					<center><span style="color: blue;font-size: 13px;text-decoration: underline;cursor: pointer;" onclick="search('<?php echo $val;  ?>', 'pages', '<?php echo $offset; ?>', '1');">Load More</span></center>
						
					<?php

					}

				}

			}	
			else {
				?> <center><span style="position: relative;top: 8px;-webkit-font-smoothing: antialiased;color: gray;font-size: 13px;">No Results Found.</span></center> <?php
			}			

		}


	}
}

$obj=new load;
echo $obj->search($_POST['which']);
