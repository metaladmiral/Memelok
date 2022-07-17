<?php 

session_start();
include './dbh.php';
/*
$server = explode('.', $_SERVER['SERVER_NAME']);
$domain = count($server)-1;
$host = count($server)-2;
$server = $server[$host].".".$server[$domain];
*/
$server = "localhost/memelok";
class getprofile extends db {
	public function getinfo($getpagename) {
		$query = db::pageconnect()->prepare("SELECT pid, pic, about, creator_username, creator_uid, creation_date, page_name, followers, posts_count, email, social FROM `info` WHERE `page_name`=:pagename");
		$query->bindParam(':pagename', $getpagename);
		$query->execute();
		$data = $query->fetch(PDO::FETCH_ASSOC);

		if(!empty($_SESSION['UID'])) {
			$pagesfollowing = json_decode($_SESSION['pagesfollowingarr'], true);
			$mypagesarr = json_decode($_SESSION['mypagesarr'], true);

			if(in_array($data['pid'], $mypagesarr)) {
				$data['display'] = '0';
 			}
 			else {
				if(in_array($data['pid'], $pagesfollowing)) {
					$data['display'] = '1';
					$data['following'] = '1';
				}
				else {
					$data['display'] = '1';
					$data['following'] = '0';
				}
 			}
		}
		else {
			$data['display'] = 0;
		}

		if($query->rowCount()>0) {
			return $data;
		}
		else {
			return 0;
		}

	}
}

if(isset($_GET['pname'])) {
	$obj = new getprofile;
	$ret = $obj->getinfo($_GET['pname']);
}
else {
	$ret = 0;
}

if($ret==0) {
	header('HTTP/1.1 404 Not Found');
	require_once('errors/notfound.html');
	exit();
}
else {
	?>


<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title><?php echo $ret['page_name']; ?> - Page | Memes by <?php echo $ret['page_name']; ?> | MeMeLok</title>
	<link rel="stylesheet" href="http://<?php echo $server; ?>/css/pageprofile.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://<?php echo $server; ?>/fontawesome/css/all.min.css">
	<meta name="description" content="Profile of <?php echo $ret['page_name']; ?>. Get the Best memes created by <?php echo  $ret['page_name']; ?>. <?php echo $ret['page_name'] ?> is already followed by <?php echo $ret['followers']; ?> people on Memelok. Follow this page to get his new memes in your feed.">

	<meta name="keywords" content="<?php echo $ret['page_name']; ?>, <?php echo $ret['page_name']; ?>'s Profile, <?php echo $ret['page_name']; ?>'s Page, memes by <?php echo $ret['page_name']; ?>, profile of <?php echo $ret['page_name']; ?>, Page profile of <?php echo $ret['page_name']; ?>, memes uploaded by <?php echo $ret['page_name']; ?>">

	<meta name="robots" content="index, follow">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="English">

	<script>
		document.addEventListener('readystatechange', event => {
			
		    if (event.target.readyState === "complete") {
		    	document.querySelector('.spinner').style.display = "block";
		    	
		    	setTimeout(function() {
		    		loadposts();
		    	}, 800);

		    }
	
		});
		function alertmain(cont) {
			document.querySelector('.alert_main .info_content span').innerHTML = cont;
			document.querySelector('.alert_main').style.display = "block";
			setTimeout(function(){
				document.querySelector('.alert_main').style.display = "none";
			}, 4200);
		}
	</script>

</head>
<body>
	
	<div class="nav_main">
		<center><h1>LOGO</h1></center>
	</div>	

	<div class="alert_main">
		<div class="icon">

			<i class="far fa-info-circle"></i>

		</div>

		<div class="info_content">
			<span>Server Error. Please Try Again Later.</span>
		</div>
	</div>

	<div class="left">
		
		<div class="profile_info_container">
			
			<div class="dp_container">
				
				<div class="dp">
					<img src="http://<?php echo $server; ?>/img_pages/<?php echo $ret['pic']; ?>" alt="">
				</div>

			</div>

			<div class="name_details">
				<div class="page_name" data-creatorname="Creator Profile"><span><?php echo $ret['page_name']; ?></span> 
				
					<?php if($ret['display']==1) { ?>
				
					 	<?php if($ret['following']==1) { ?> <i style="color: #ffc61a;font-weight: bold;" class="fal fa-wifi" onclick="unfollowpage('<?php echo $ret['pid']; ?>')" style="color: #ffc61a;"></i> <?php }else { ?> <i class="fal fa-wifi" style="color: black;" onclick="followpage('<?php echo $ret['pid']; ?>');"></i> <?php } ?>

					<?php } ?>

				</div> 
			
			</div>

			<div class="social_media">
				
				<div class="facebook">
					<i class="fab fa-facebook-f"></i>
				</div>

				<div class="insta">
					<i class="fab fa-instagram"></i>
				</div>

				<div class="twitter">
					<i class="fab fa-twitter"></i>
				</div>

			</div>

			<div class="about">
				<span style="font-size: 12px;color: gray;"><?php echo $ret['about']; ?></span>
			</div>

			<div class="info">
				
				<div class="followers">
					<ul>
						<li><span class="heading">Followers</span></li>
						<li><center><span class="count"><?php echo $ret['followers']; ?></span></center></li>
					</ul>
				</div>

				<div class="posts_count">
					<ul>
						<li><span class="heading">Posts</span></li>
						<li><center><span class="count"><?php echo $ret['posts_count']; ?></span></center></li>
					</ul>
				</div>

			</div>

		</div>

	</div>

	<div class="right">
		
		<center><h1>Posts</h1></center>

		<script>
			function loadposts(offset, limit, total, lm) {
				if(lm!=undefined) {
					lm.style.display = "none";
				}
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.status==200 && this.readyState==4) {
						var resp = this.responseText;
						document.querySelector('.spinner').style.display = "none";
						document.querySelector('.right .posts').innerHTML += resp;
					}
				}
				var formdata = new FormData();
				if(offset==undefined) {
					offset = 0;
				}

				if(limit==undefined) {
					limit = 12;
				}

				if(total==undefined) {
					total = 'null';
				}

				formdata.append('offset', offset);
				formdata.append('limit', limit);
				formdata.append('total', total);
				formdata.append('pid', '<?php echo $ret['pid']; ?>');

				xml.open('POST', '../../getpageposts.php');
				xml.send(formdata);

			}
		</script>
		
		<div class="posts">
			
			<center><div class="spinner loader"></div></center>

		</div> 

	</div>

	<script>
		function followpage(which) {
			if(!localStorage.getItem('freqs_count')) {
				location.reload(true);
			}
			else {
	
				document.querySelector('.fa-wifi').style.color = "#ffc61a";
				document.querySelector('.fa-wifi').style.fontWeight = "bold";
				document.querySelector('.fa-wifi').removeAttribute('onclick');
				document.querySelector('.fa-wifi').addEventListener('click', function() {
					unfollowpage('<?php echo $ret['pid']; ?>');
				});
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							if(resp==1) {
								alertmain('You are now following this page.');
							}
							else if(resp==0){
								alertmain("Server Error! Please Try again later!");
							}
							else {
								alertmain(resp);
							}
						}, 40);
					}
				}
				var formdata = new FormData();
				formdata.append("pid", which);
				formdata.append("mypagesarr", localStorage.getItem('mypagesarr'));
				formdata.append("pagesfollowing", localStorage.getItem('pagesfollowing'));
				formdata.append('action', 'follow');
				xml.open("POST", "http://<?php echo $server; ?>/bscripts/actions/followpage.php");
				xml.withCredentials = true;
				xml.send(formdata);
			
			}

		}

		function unfollowpage(which) {
			if(!localStorage.getItem('freqs_count')) {
				location.reload(true);
			}
			else {
				document.querySelector('.fa-wifi').style.color = "black";
				document.querySelector('.fa-wifi').style.fontWeight = "normal";
				document.querySelector('.fa-wifi').removeAttribute('onclick');
				document.querySelector('.fa-wifi').addEventListener('click', function() {
					followpage('<?php echo $ret['pid']; ?>');
				});
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							if(resp==1) {
								alertmain('You have unfollowed this page.');
							}
							else if(resp==0){
								alertmain("Server Error! Please Try again later!");
							}
							else {
								alertmain(resp);
							}
						}, 40);
					}
				}
				var formdata = new FormData();
				formdata.append("pid", which);
				formdata.append("mypagesarr", localStorage.getItem('mypagesarr'));
				formdata.append("pagesfollowing", localStorage.getItem('pagesfollowing'));
				formdata.append('action', 'unfollow');
				xml.open("POST", "http://<?php echo $server; ?>/bscripts/actions/followpage.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}
		}
	</script>

</body>
</html>




	<?php
}
