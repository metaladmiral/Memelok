<?php

session_start();
ob_start();

include './dbh.php';

if(strpos('Android',$_SERVER['HTTP_USER_AGENT'])==true) {
	header('Location: http://mobile.localhost/memelok');
}

$dbname = 'usr_'.$_SESSION['UID'];

/*$server = explode('.', $_SERVER['SERVER_NAME']);
$domain = count($server)-1;
$host = count($server)-2;
$server = $server[$host].".".$server[$domain];*/
$server = "localhost/memelok";

if(isset($_COOKIE['LID'])) {
	$lid = $_COOKIE['LID'];
	if(empty($lid)) {
		header('Location: http://'.$server.'/login');
	}
	else {
		$uid = $_SESSION['UID'];
		if(empty($uid)) {
			header('Location: http://'.$server.'/logout.php');
		}
	}
}
else {
	header('Location: http://'.$server.'/login');
}

/*
function getUserIP()
{
    // Get real visitor IP behind CloudFlare network
    if (isset($_SERVER["HTTP_CF_CONNECTING_IP"])) {
              $_SERVER['REMOTE_ADDR'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
              $_SERVER['HTTP_CLIENT_IP'] = $_SERVER["HTTP_CF_CONNECTING_IP"];
    }
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}

$ip = getUserIP();
*/

$query_f_mess = db::mconnect($dbname)->prepare("SELECT count(*) as count FROM `chathistory` WHERE `last_read`=0");
$query_f_mess->execute();
$mess_count = $query_f_mess->fetch(PDO::FETCH_ASSOC)['count'];

?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Home - Memelok | Memes | Best Indian memes | Best Memes Collection</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="http://<?php echo $server; ?>/fontawesome/css/all.min.css">
	<script defer src="http://<?php echo $server; ?>/scripts/jquery.js"></script>
	<meta name="description" content="Log in or Create an account on Memelok. Get the best Indian memes and funny memes from best memers around India, chat with your friends">
	<meta name="keywords" content="login, memelok login, login page, sign in, sign up, create an account, memelok, memes, meme, best indian memes, best memes collection, funny memes, best memes, adult memes, memes in hindi, exam memes, funny memes in hindi, latest memes, trending memes, dark memes, instagram memes, got memes, the office memes, life memes, tik tok memes, best memes ever, single memes, popular memes, sarcastic memes, hilarious memes, comedy memes, bollywood memes, new memes, funny friend memes, funny love memes, cartoon memes, food memes, english memes, science memes, physics memes, college memes, donald trump memes, savage memes, cute memes, doctor memes, facebook memes, programming memes, football memes">
	<meta name="robots" content="index, follow">
	<meta name="revisit-after" content="1 days">
	<meta name="author" content="Prakhar Sinha, Pratham Garg">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="English">

	<link rel="apple-touch-icon" sizes="57x57" href="http://<?php echo $server; ?>/icon/apple-icon-57x57.png"><link rel="apple-touch-icon" sizes="60x60" href="http://<?php echo $server; ?>/icon/apple-icon-60x60.png"><link rel="apple-touch-icon" sizes="72x72" href="http://<?php echo $server; ?>/icon/apple-icon-72x72.png"><link rel="apple-touch-icon" sizes="76x76" href="http://<?php echo $server; ?>/icon/apple-icon-76x76.png"><link rel="apple-touch-icon" sizes="114x114" href="http://<?php echo $server; ?>/icon/apple-icon-114x114.png"><link rel="apple-touch-icon" sizes="120x120" href="http://<?php echo $server; ?>/icon/apple-icon-120x120.png"><link rel="apple-touch-icon" sizes="144x144" href="http://<?php echo $server; ?>/icon/apple-icon-144x144.png"><link rel="apple-touch-icon" sizes="152x152" href="http://<?php echo $server; ?>/icon/apple-icon-152x152.png"><link rel="apple-touch-icon" sizes="180x180" href="http://<?php echo $server; ?>/icon/apple-icon-180x180.png"><link rel="icon" type="image/png" sizes="192x192"  href="http://<?php echo $server; ?>/icon/android-icon-192x192.png"><link rel="icon" type="image/png" sizes="32x32" href="http://<?php echo $server; ?>/icon/favicon-32x32.png"><link rel="icon" type="image/png" sizes="96x96" href="http://<?php echo $server; ?>/icon/favicon-96x96.png"><link rel="icon" type="image/png" sizes="16x16" href="http://<?php echo $server; ?>/icon/favicon-16x16.png">

	<script>if(window.innerWidth<=650){window.location = 'http://mobile.localhost/memelok';}</script>

</head>
<body>

	<!-- nav dropdowns !-->
		<a class="downloadmeme"></a>

		<div class="overlay_responsive">
			
		</div>

		<audio id="message_noti">
		  <source src="http://<?php echo $server; ?>/sounds/insight.ogg" type="audio/ogg">
		</audio>

		<div class="search_resp_dropdown nav_dropdown" style="display: none;">
			<i class="far fa-angle-up"></i>
			<div class="mcont">
				<input type="search" class="resp_search_input" id='search' onfocus="focussearchbar()" onfocusout="focussoutsearchbar();" placeholder="Search for people, pages.." spellcheck="false" onkeyup="entercapture(event, this, this.value);">
				<div class="highlgtr"><span class="far fa-search" style="background: transparent;font-size:17.5px;color: white;"></span></div>
			</div>
		</div>

		<div class="nav_dropdown freq_dropdown" style="display: none;">
			<i class="far fa-angle-up"></i>
			<div class="mcont">
				
				<div class="top">
					<span id="heading">Friend Requests</span>
				</div>

				<div class="bottom">
					
					<div class="spinner loader"></div>

					<div class="reqs">

						<script>
							function load_freq(offset, withload) {
								document.querySelector('.freq_dropdown .mcont .bottom .spinner').style.display = "block";
								document.querySelector('.freq_dropdown .mcont .bottom .reqs').innerHTML = "";
								var xml = new XMLHttpRequest();
								xml.onreadystatechange= function() {
									if(this.readyState==4 && this.status==200) {
										var resp = this.responseText;
										setTimeout(function(){
											document.querySelector('.freq_dropdown .mcont .bottom .spinner').style.display = "none";
											if(withload==undefined) {
												document.querySelector('.freq_dropdown .mcont .bottom .reqs').innerHTML = resp;
											}
											else {
												document.querySelector('.freq_dropdown .mcont .bottom .reqs').innerHTML += resp;
											}
										}, 250);
									}
								}
								xml.open("POST", "http://<?php echo $server; ?>/bscripts/load/freq.php");
								xml.withCredentials = true;
								var formdata = new FormData();
								formdata.append("which", "getfreqs");
								if(offset==undefined) {
									formdata.append('offset', 0);
								}
								else {
									formdata.append('offset', offset);	
								}
								xml.send(formdata);
							}
						</script>

					</div>

				</div>

			</div>
		</div>

		<div class="nav_dropdown noti_dropdown" style="display: none;">
			<i class="far fa-angle-up"></i>
			<div class="mcont">
				
				<div class="top">
					<span id='heading'>Notifications</span>
					<div class="more"><a href="" onclick="markallnotiread();return false;">Mark all as Read</a></div>
				</div>

				<script>
					function markallnotiread() {
						var xml = new XMLHttpRequest();
						xml.onreadystatechange = function() {
							if(this.readyState==4 && this.status==200) {
								var resp = this.responseText;
								if(resp==1) {
									alertmain("Done!");
								}
								else {
									alertmain(resp);
								}
							}
						}
						xml.open("POST", "http://<?php echo $server; ?>/bscripts/actions/markallnotiread.php");
						xml.withCredentials = true;
						xml.send();
					}
				</script>	

				<div class="bottom">
					
					<div class="loader spinner"></div>

					<script>
						function load_noti(offset, limit, total, dom) {
							try {
								dom.style.display = "none";
							}
							catch(err) {
								
							}
							document.querySelector('.noti_dropdown .mcont .bottom .spinner').style.display = "block";
							var xml = new XMLHttpRequest();
							xml.onreadystatechange= function() {
								if(this.readyState==4 && this.status==200) {
									var resp = this.responseText;
									setTimeout(function(){
										document.querySelector('.noti_dropdown .mcont .bottom .spinner').style.display = "none";
										document.querySelector('.noti_dropdown .mcont .bottom .noti_main').innerHTML += resp;
									}, 250);
								}
							}
							xml.open("POST", "http://<?php echo $server; ?>/bscripts/load/notifications.php");
							xml.withCredentials = true;
							var formdata = new FormData();
							formdata.append("which", "getnoti");
							if(offset==undefined) {
								offset = 0;
							}

							if(limit==undefined) {
								limit = 8;
							}

							if(total==undefined) {
								total = 'null';
							}

							formdata.append('offset', offset);
							formdata.append('total', total);
							formdata.append('limit', limit);
							xml.send(formdata);
						}
					</script>

					<div class="noti_main">

						
					</div>

				</div>

			</div>
		</div>
		<div class="nav_dropdown usrstngs_dropdown" style="display: none;">
			<i class="far fa-angle-up"></i>
			<div class="mcont">
				
				<ul>
					<li onclick="dropdownaction('settings');">Settings</li>
					<li onclick="dropdownaction('createpage');">Create a Page</li>
					<li onclick="dropdownaction('mypages');">My Pages</li>
					<li onclick="dropdownaction('logout');">Log Out</li>
				</ul>

			</div>
		</div>

		<!-- ---------------- !-->

		<div class="alert_main">
			<div class="icon">

				<i class="far fa-info-circle"></i>

			</div>

			<div class="info_content">
				<span>Server Error. Please Try Again Later.</span>
			</div>
		</div>

	<div class="left_divider">	
		
		<div class="top_heading" state="enabled">
			<h2>CHATBOX</h2>
		</div>

		<div class="more_">
			
			<div class="left_more_" onclick="disablechat()" cdata='1'>
				<i style="cursor: pointer;" class="far fa-power-off"></i>
			</div>

			<div class='center_more_' style="color: #00b300;">&bull;</div>

			<div class="right_more_" onclick="chatanimation();"><i class="far fa-exchange-alt" id='<?php if($mess_count>0) { ?>active<?php } ?>' style="cursor: pointer;"></i></div>

		</div>

		<div class="onlinefrnds">
			<div id="line" data-value="Online Friends (0)" f_count="" data-change='1'></div>
		</div>

		<div class="onlinefrndsmain">

			<div class='loader' id='offonchatspin'></div>

			<div class="chathistory">
			
				
			</div>

			<div class="onnfrnds">
				
			</div>

			<div class="chatwindow" data-username="" data-fullname="" data-uid="" data-pic="" data-cid="" data-which="">
				
				<div class="top">

					<i class="fas fa-arrow-left" onclick="removechatwindow(document.querySelector('.chatwindow').getAttribute('data-which'));"></i>
					
					<div class="dp"><img src="" alt="User pic" style='object-fit:cover;'></div>
					&nbsp;&nbsp;
					<span style="font-family: ;font-size: 13px;" id='chat_username'>Username</span>
					&nbsp;&nbsp;
					<span style="color: #00b300;font-size: 20px;" class="onlinestatus" style="display: none;">&bull;</span>
                	&nbsp;&nbsp;
                	<span style="float: right;color: green;font-size: 11px;display: none;" id='user_typing'>Typing...</span>

				</div>

				<div class="mainchat">
					
					<div class="loader spinner"></div>
                

					<div class="maindata" onscroll="">
						<ul>
							
						</ul>
						
					</div>

				</div>				

				<div class="bottom_chatm">
						
					<input type="text" placeholder="Enter a Message" id='chat_mess_input' onkeyup="messevent(event);" onfocusout="send_typing(0);">

					<script>
						function messevent(e) {
                        	send_typing(1);
							if(e.code=='Enter') {
								sendmess();
								document.querySelector('#chat_mess_input').value = "";
							}
						}

					</script>

					<i class="fas fa-paper-plane" style="color: white;position: absolute;right: 10px;font-size: 22px;cursor: pointer;" onclick="sendmess();"></i>

				</div>

			</div>

			<script>
				loadchatinterval = setInterval(function(){
					loadlazychat();
				}, 2500);
			</script>			

		</div>
		
	</div>

	<div class='right_divider' id='autofocus'>

		<div class="nav_main">

			<div class="logo">
				<img src="http://<?php echo $server; ?>/img/logo_dev1.png" alt="">
			</div>

			<div class="main_spinner">
				<div class="loader spinner"></div>
			</div>

			<div class="search">
				
				<div class="search_bar" style="">
					<input type="search" id='search' onfocus="focussearchbar()" onfocusout="focussoutsearchbar();" placeholder="Search for people, pages.." spellcheck="false" onkeyup="entercapture(event, this, this.value);">
					<i class="far fa-times" style="" onclick="document.querySelector('.ico_search').style.display='block';document.querySelector('.search_bar').style.display='none';"></i>
					<div class="highlgtr"><i class="far fa-search"></i></div>
				</div>

				<div class="ico_search" style="" onclick="searchbar_toggle('open');"><i class="far fa-search nav_icon"></i><div class="hghlgtr"></div></div>
				

			</div>

			<div class="right">
				<!--
				<div class="ico"><i class="far fa-users nav_icon_hover nav_icon" id='nav_friendreq' aria-hidden='true'></i></div>

				<div class="ico"><i class="far fa-bells nav_icon" id='nav_noti'></i></div>

				<div class="ico"><i class="far fa-users-cog nav_icon" id='nav_usersettings'></i></div>
				----------------------------
				<i class="far fa-users nav_icon_hover nav_icon" id='nav_friendreq' onclick="open_nav_dropdown(this)" aria-hidden='true'></i>
				
				<i class="far fa-bells nav_icon" id='nav_noti'></i>
				
				<i class="far fa-users-cog nav_icon" id='nav_usersettings'></i>
				--------------------------
				
				<div class="ico" id='ico1'><i onmouseover="document.querySelector('#ico1').style.border = '2px solid #ffc61a';" class="far fa-users nav_icon_hover nav_icon" id='nav_friendreq' onclick="open_nav_dropdown(this)" onmouseout="document.querySelector('#ico1').style.border = '2px solid gray';" aria-hidden='true'></i></div>

				<div class="ico" id='ico2'><i class="far fa-bells nav_icon" id='nav_noti' onmouseover="document.querySelector('#ico2').style.border = '2px solid #ffc61a';" onmouseout="document.querySelector('#ico2').style.border = '2px solid gray';"></i></div>

				<div class="ico" id='ico3'><i class="far fa-users-cog nav_icon" id='nav_usersettings' onmouseover="document.querySelector('#ico3').style.border = '2px solid #ffc61a';" onmouseout="document.querySelector('#ico3').style.border = '2px solid gray';"></i></div>

				!-->

				<div class="ico" id='ico1' cdata='1' onclick="open_nav_dropdown('freq');" onmouseover="hover_ico('ico1');" onmouseout="remove_hover_ico('ico1');"><i class="far fa-users nav_icon_hover nav_icon" id='nav_friendreq' aria-hidden='true'></i><div class="hghlgtr"></div></div>
				
				<div class="ico" id='ico2' cdata='1' onclick="open_nav_dropdown('noti');" onmouseover="hover_ico('ico2');" onmouseout="remove_hover_ico('ico2');"><i class="far fa-bells nav_icon" id='nav_noti'></i><div class="hghlgtr"></div></div>
				
				<div class="ico" id='ico3' cdata='1' onclick="open_nav_dropdown('usrsttngs');" onmouseover="hover_ico('ico3');" onmouseout="remove_hover_ico('ico3');"><i class="far fa-users-cog nav_icon" id='nav_usersettings'></i><div class="hghlgtr"></div></div>

			</div>

		</div>

		<div class="right_main">

			<script>
				function loadlazyposts(id) {

					var f_data = undefined;
					var s_data = undefined;
					var t_data = undefined;
					var shown = undefined;
					var not = undefined;

					try {
	 					f_data = document.querySelector('.loadmore').getAttribute('data-fdata');
	 					s_data = document.querySelector('.loadmore').getAttribute('data-sdata');
	 					t_data = document.querySelector('.loadmore').getAttribute('data-tdata');
	 					shown = document.querySelector('.loadmore').getAttribute('data-shown');
	 					not = document.querySelector('.loadmore').getAttribute('data-not');

					}
					catch(err) {
						 
					}

					var xml = new XMLHttpRequest();
					xml.onreadystatechange = function(){
						if(this.readyState == 4 && this.status == 200) {
							var resp = this.responseText;
							document.querySelector('.postloader').style.display = "none";
							try {
								document.querySelector(id).style.display = "none";
							}
							catch(err) {
							}


							if(resp==0) {
								document.querySelector('#feed').innerHTML += "<center><span style='color: gray;font-size: 13px;'>Follow more pages to add more memes or wait for some more time.</span></center><br><br>";					
							}
							else {
								document.querySelector('#feed').innerHTML += resp;
							}

						}
					}
					xml.open("POST", "http://<?php echo $server; ?>/bscripts/load/loadposts.php");
					xml.withCredentials = true;
					var formdata = new FormData();
					formdata.append('px', window.innerWidth+"x"+window.innerHeight);
					if(f_data==undefined || f_data==0) {
						f_data = {limit:8, offset:0, datebefore:2, do:1};
						formdata.append('f_data', JSON.stringify(f_data));
					}
					else {
						formdata.append('f_data', f_data);
					}	

					if(s_data==undefined || s_data==0) {
						s_data = {limit:8, offset:0, datebefore:9, do:1};
						formdata.append('s_data', JSON.stringify(s_data));
					}
					else {
						formdata.append('s_data', s_data);	
					}

					if(t_data==undefined || t_data==0) {
						t_data = {limit:8, offset:0, datebefore:9};
						formdata.append('t_data', JSON.stringify(t_data));
					}
					else {
						formdata.append('t_data', t_data);	
					}

					if(shown==undefined || shown==0) {
						shown = [];
						formdata.append('shown', JSON.stringify(shown));
					}
					else {
						formdata.append('shown', shown);
					}

					if(not==undefined || not==0) {
						not = [];
						formdata.append('not', JSON.stringify(not));
					}
					else {
						formdata.append('not', not);
					}

					xml.send(formdata);
				}
			</script>
			

			<div id="feed">

				<div class="loader postloader"></div>	

			</div>

			<div class="extras">
				
				<div class="pgsugg">
					
					<h1>Page Suggestions</h1>
					
					<hr style="visibility: hidden;">
					<hr style="visibility: hidden;">
					<hr style="visibility: hidden;">	

					<div class="main_bottom">
					
						<div class="loader spinner" style="display: block;width: 12px;height: 12px;margin-left: 5px;"></div>

						<ul style="list-style: none;">
							
						</ul>

					</div>

				</div>
				
				<br>

				<div class="ad">
					
					<div id='morelinks'><a href="/about-us" target="_blank" style="text-decoration: none;"><span>About</span></a> &bull; <a href="/terms" target="_blank" style="text-decoration: none;"><span>Terms</span></a> &bull; <a href="/help-us-to-improve" target="_blank" style="text-decoration: none;"><span style="">Give us a Feedback</span></a></div>
					<span style="font-size: 12px;color: gray;">Memelok &copy; <?php echo date('Y'); ?>. </span>	
					<br>
					<span style="font-size: 12px;color: #b3b3b3;">All rights reserved.</span>

					<br>
					<hr style="visibility: hidden;">
					<br>
					<b style="letter-spacing: 0.1px;color: #222;font-size: 12.8px;">Common queries - </b>
					<br>
					<hr style="visibility: hidden;">
					<hr style="visibility: hidden;">

					<a href="/how-to-upload-a-meme" target="_blank" style="color: #222;font-size: 12px;">How to upload a meme?</a>
					<br>
					<a href="/how-to-create-a-page" target="_blank" style="color: #222;font-size: 12px;">How to create a page?</a>

				</div>

			</div>

		</div>

		<div class="overlay">
			
		</div>


		<div class="overlay search_overlay">

			<div class="back_arrow">

				<i class="fas fa-arrow-left" style="" onclick='document.querySelector(".search_overlay").style.display="none";'></i>

			</div>

			<div class="main_search">

				<div class="top_togglebtw">
					
					<h1 style="font-size: 20px;font-family: 'Poppins', sans-serif;position: relative;left: 25px;">Search</h1>

					<div class="typetoggle">
						<label style="font-size: 13px;color:gray;" for="toggle">Type: </label>
						<select id="toggle" onchange="search(document.querySelector('#search').value, this.value);">
							<option value="people">People</option>
							<option value="pages">Pages</option>
						</select>
					</div>

				</div>

				<div class="bottom">
					
					<div class="spinner loader"></div>

					<div class="content"></div>

				</div>
				
			</div>

		</div>

	</div>
	
	<script>function dropdownaction(e){var t="",n=document.querySelector(".main_spinner"),o=e;n.style.display="flex";try{document.querySelector(".search_overlay").style.display="none"}catch(e){}if("logout"==e)t="logout.php",clearInterval(updateconteinterval),localStorage.clear(),setTimeout(function(){n.style.display="none",window.location="http://localhost/memelok/logout.php"},900);else{t="http://<?php echo $server; ?>/bscripts/"+e+".php","profile"==e&&(t="http://<?php echo $server; ?>/bscripts/"+e+".php?id=<?php echo $_SESSION['UID']; ?>");var r=new XMLHttpRequest;r.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var t=this.responseText;setTimeout(function(){"settings"==e&&window.innerWidth<950&&window.innerWidth>650?(document.querySelector(".overlay_responsive").innerHTML="",document.querySelector(".overlay_responsive").style.display="flex",n.style.display="none",document.querySelector(".overlay_responsive").innerHTML=t):(document.querySelector(".overlay").innerHTML="",document.querySelector(".overlay").style.display="block",n.style.display="none",document.querySelector(".overlay").innerHTML=t,"mypages"==o&&load_mypages(),setTimeout(function(){document.querySelector("#ico3 i").style.color="rgba(0, 0, 0, 0.5)",document.querySelector("#ico3 .hghlgtr").style.background="gray",document.querySelector(".usrstngs_dropdown").style.display="none",document.querySelector(".nav_main").style.borderBottom="1px solid rgba(0, 0, 0, 0.20)",document.querySelector("#ico3").setAttribute("cdata","1"),document.querySelector("#ico3").setAttribute("onmouseout","remove_hover_ico('ico3')")},4500))},1225)}},r.open("POST",t),r.withCredentials=!0,r.send()}}function usrncheck(){document.querySelector(".right_1 .spinner").style.display="none",document.querySelector(".right_1 #usrnavl").style.display="none",document.querySelector(".right_1 #usrnnavl").style.display="none";var e=document.querySelector(".right_1 .username input").value;if("<?php echo $_SESSION['username']; ?>"==e)document.querySelector(".right_1 .username input").setAttribute("toupdate","0");else if(document.querySelector(".right_1 #usrnavl").style.display="none",document.querySelector(".right_1 .spinner").style.display="none",document.querySelector(".right_1 .username input").setAttribute("toupdate","0"),document.querySelector(".right_1 #usrnnavl").style.display="none",e.length>=7&&e.length<=18){document.querySelector(".right_1 .spinner").style.display="block";var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;"1"==e?(document.querySelector(".right_1 .spinner").style.display="none",document.querySelector(".right_1 #usrnnavl").style.display="none",document.querySelector(".right_1 .username input").setAttribute("toupdate","1"),document.querySelector(".right_1 #usrnavl").style.display="block"):"0"==e?(document.querySelector(".right_1 #usrnavl").style.display="none",document.querySelector(".right_1 .spinner").style.display="none",document.querySelector(".right_1 .username input").setAttribute("toupdate","0"),document.querySelector(".right_1 #usrnnavl").style.display="block"):(document.querySelector(".right_1 #usrnavl").style.display="none",document.querySelector(".right_1 .spinner").style.display="none",document.querySelector(".right_1 #usrnnavl").style.display="block",document.querySelector(".right_1 .username input").setAttribute("toupdate","0"),alertmain(e),console.log(e))}},t.open("POST","http://<?php echo $server; ?>/bscripts/usrncheck.php"),t.withCredentials=!0;var n=new FormData;n.append("value",e),t.send(n)}else document.querySelector(".right_1 .username input").setAttribute("toupdate","0")}function accept_request(e,t){document.querySelector("#overlay_"+t).style.display="flex";var n=new XMLHttpRequest;n.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var n=this.responseText;if(1==n){setTimeout(function(){document.querySelector("#freq-items_"+t+" #accept_request").innerHTML="Accepted &#10004;",document.querySelector("#freq-items_"+t+" #accept_request").style.width="70px",document.querySelector("#freq-items_"+t+" #accept_request").setAttribute("disabled","true"),document.querySelector("#freq-items_"+t+" #accept_request").style.cursor="auto",document.querySelector("#freq-items_"+t+" #decline_request").setAttribute("disabled","true"),document.querySelector("#freq-items_"+t+" #decline_request").style.cursor="auto",document.querySelector("#freq-items_"+t+" #accept_request").removeAttribute("onclick"),document.querySelector("#freq-items_"+t+" #decline_request").removeAttribute("onclick"),document.querySelector("#overlay_"+t).style.display="none"},650);var o=new XMLHttpRequest;o.onreadystatechange=function(){4==this.readyState&&this.status};var r=new FormData;r.append("key",e),r.append("which","sendnoti"),r.append("content","<?php echo $_SESSION['username']; ?> accepted your friend request."),o.open("POST","http://<?php echo $server; ?>/bscripts/load/notifications.php"),o.withCredentials=!0,o.send(r)}else alertmain("An error Occured!"),console.log(n)}},n.open("POST","http://<?php echo $server; ?>/bscripts/load/freq.php"),n.withCredentials=!0;var o=new FormData;o.append("which","accept"),o.append("key",e),n.send(o)}function openchat(e,t,n,o,r){var s=document.querySelector("#"+r).getAttribute("data_cid");document.querySelector(".chatwindow .top .dp img").setAttribute("src","http://<?php echo $server; ?>/img_users/"+n),document.querySelector(".chatwindow .top #chat_username").innerHTML=e,document.querySelector(".chatwindow").setAttribute("data-username",e),document.querySelector(".chatwindow").setAttribute("data-uid",t),document.querySelector(".chatwindow").setAttribute("data-fullname",o),document.querySelector(".chatwindow").setAttribute("data-cid",s),document.querySelector(".chatwindow").style.display="block",document.querySelector(".onlinefrnds #line").setAttribute("data-value","Chat window"),document.querySelector(".chatwindow .mainchat .maindata ul").innerHTML="",loadchat(t,"<?php echo $_SESSION['UID']; ?>",e);var a=new XMLHttpRequest;a.onreadystatechange=function(){200==this.status&&this.readyState};var c=new FormData;c.append("uidf",t),c.append("uidm","<?php echo $_SESSION['UID']; ?>"),c.append("updatelmess","1"),a.open("POST","http://<?php echo $server; ?>/bscripts/chat/change_last_message.php"),a.withCredentials=!0,a.send(c),getcidinterval=setInterval(function(){var e=new XMLHttpRequest,t=document.querySelector(".chatwindow").getAttribute("data-uid");e.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=JSON.parse(this.responseText),t=e.cid,n=e.online;document.querySelector(".chatwindow").setAttribute("data-cid",t),1==n?document.querySelector(".chatwindow .top .onlinestatus").style.display="block":(document.querySelector(".chatwindow .top .onlinestatus").style.display="none",document.querySelector("#user_typing").style.display="none");}};var n=new FormData;n.append("uid",t),e.open("POST","http://<?php echo $server; ?>/bscripts/chat/getcid.php"),e.withCredentials=!0,e.send(n)},800),updatelmesinterval=setInterval(function(){var e=new XMLHttpRequest;e.onreadystatechange=function(){200==this.status&&this.readyState};var n=new FormData;n.append("uidf",t),n.append("uidm","<?php echo $_SESSION['UID']; ?>"),n.append("updatelmess","1"),e.open("POST","http://<?php echo $server; ?>/bscripts/chat/change_last_message.php"),e.withCredentials=!0,e.send(n)},3500)}document.addEventListener("readystatechange",e=>{"complete"===e.target.readyState&&(document.querySelector("#offonchatspin").style.display="block",document.querySelector(".postloader").style.display="block",localStorage.setItem("uid","<?php echo $_SESSION['UID']; ?>"),setTimeout(function(){loadlazyposts()},800),setTimeout(function(){loadlazychat()},900),setTimeout(function(){loadlazypgsugg()},900),setInterval(function(){var e=new XMLHttpRequest;e.onreadystatechange=function(){if(4==this.readyState&&200==this.status&&"<?php echo $_COOKIE['PHPSESSID']; ?>"!=this.responseText){confirm("A computer has loggedin from your account. You are being logged out.");window.location="http://localhost/memelok/logout.php"}},e.open("GET","http://<?php echo $server; ?>/bscripts/actions/chksessid.php"),e.withCredentials=!0,e.send()},1e4),update_freqs())});</script><script>
 	var dp_change, getcidinterval,updatelmesinterval,loadchatinterval,loadchathistoryinterval,updateconteinterval;function update_freqs(e){var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var t=this.responseText;null!=e&&t!=localStorage.getItem("freqs_count")&&document.querySelector("#ico1 i").classList.add("active"),localStorage.setItem("freqs_count",t)}},t.open("GET","http://<?php echo $server; ?>/bscripts/load/update_freqs.php"),t.withCredentials=!0,t.send()}function update_content(){var e=new XMLHttpRequest;e.onreadystatechange=function(){if(this.status=4==this.readyState){var e=this.responseText,t=JSON.parse(e);t.noti_count>t.oldnoticount&&document.querySelector("#ico2 i").classList.add("active")}};var t=document.querySelector(".left_divider .top_heading").getAttribute("state"),o=new FormData;o.append("state",t),e.open("POST","http://<?php echo $server; ?>/bscripts/load/update_content.php"),e.withCredentials=!0,e.send(o)}function hover_ico(e){if("ico1"==e){document.querySelector("#ico1");var t=document.querySelector("#ico1 i"),o=document.querySelector("#ico1 .hghlgtr")}else if("ico2"==e)document.querySelector("#ico2"),t=document.querySelector("#ico2 i"),o=document.querySelector("#ico2 .hghlgtr");else document.querySelector("#ico3"),t=document.querySelector("#ico3 i"),o=document.querySelector("#ico3 .hghlgtr");t.style.color="var(--hover-color)",o.style.background="var(--hover-color)"}function remove_hover_ico(e){if("ico1"==e){document.querySelector("#ico1");var t=document.querySelector("#ico1 i"),o=document.querySelector("#ico1 .hghlgtr")}else if("ico2"==e)document.querySelector("#ico2"),t=document.querySelector("#ico2 i"),o=document.querySelector("#ico2 .hghlgtr");else document.querySelector("#ico3"),t=document.querySelector("#ico3 i"),o=document.querySelector("#ico3 .hghlgtr");t.style.color="rgba(0, 0, 0, 0.5)",o.style.background="gray"}function loadlazypgsugg(){var e=new XMLHttpRequest;document.querySelector(".pgsugg .spinner").style.display="none",e.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;document.querySelector(".pgsugg ul").innerHTML=e}};var t=new FormData;e.open("POST","http://<?php echo $server; ?>/bscripts/load/pgsugg.php"),e.withCredentials=!0,e.send(t)}function alertmain(e){document.querySelector(".alert_main .info_content span").innerHTML=e,document.querySelector(".alert_main").style.display="block",setTimeout(function(){document.querySelector(".alert_main").style.display="none"},4200)}function enablebtn(){try{document.querySelector(".right_2 #editbtn").removeAttribute("disabled"),document.querySelector(".right_2 #editbtn").style.cursor="pointer"}catch(e){console.error(e)}}function open_nav_dropdown(e){var t="";if("freq"==e){var o=document.querySelector("#ico1"),r=document.querySelector("#ico1 i"),n=document.querySelector("#ico1 .hghlgtr"),a=document.querySelector(".freq_dropdown");document.querySelector("#ico2").setAttribute("cdata","1"),document.querySelector("#ico3").setAttribute("cdata","1"),document.querySelector("#ico2").setAttribute("onmouseout","remove_hover_ico('ico2')"),document.querySelector("#ico3").setAttribute("onmouseout","remove_hover_ico('ico3')"),t="ico1",document.querySelector("#ico1 i").classList.remove("active"),a.style.width="350px",a.style.height="445px"}else if("noti"==e){o=document.querySelector("#ico2"),r=document.querySelector("#ico2 i"),n=document.querySelector("#ico2 .hghlgtr"),a=document.querySelector(".noti_dropdown");document.querySelector("#ico1").setAttribute("cdata","1"),document.querySelector("#ico3").setAttribute("cdata","1"),document.querySelector("#ico1").setAttribute("onmouseout","remove_hover_ico('ico1')"),document.querySelector("#ico3").setAttribute("onmouseout","remove_hover_ico('ico3')"),t="ico2",document.querySelector("#ico2 i").classList.remove("active"),a.style.width="350px",a.style.height="395px"}else{o=document.querySelector("#ico3"),r=document.querySelector("#ico3 i"),n=document.querySelector("#ico3 .hghlgtr"),a=document.querySelector(".usrstngs_dropdown");document.querySelector("#ico2").setAttribute("cdata","1"),document.querySelector("#ico1").setAttribute("cdata","1"),document.querySelector("#ico2").setAttribute("onmouseout","remove_hover_ico('ico2')"),document.querySelector("#ico1").setAttribute("onmouseout","remove_hover_ico('ico1')"),t="ico3"}var i=o.getBoundingClientRect(),c=window.innerWidth-i.x-30.5;a.style.right=c.toString()+"px",document.querySelector("#ico1 i").style.color="rgba(0, 0, 0, 0.5)",document.querySelector("#ico1 .hghlgtr").style.background="gray",document.querySelector(".freq_dropdown").style.display="none",document.querySelector("#ico2 i").style.color="rgba(0, 0, 0, 0.5)",document.querySelector("#ico2 .hghlgtr").style.background="gray",document.querySelector(".noti_dropdown").style.display="none",document.querySelector("#ico3 i").style.color="rgba(0, 0, 0, 0.5)",document.querySelector("#ico3 .hghlgtr").style.background="gray",document.querySelector(".usrstngs_dropdown").style.display="none";var s=parseInt(o.getAttribute("cdata"));if(s%2==1){r.style.color="var(--hover-color)",n.style.background="var(--hover-color)",document.querySelector(".nav_main").style.borderBottom="2px solid var(--hover-color)";var l=s+1;o.setAttribute("cdata",l.toString()),a.style.display="block",o.removeAttribute("onmouseout"),"ico1"==t?load_freq():"ico2"==t&&(load_noti(),document.querySelector(".noti_dropdown .mcont .bottom .noti_main").innerHTML="")}else{r.style.color="rgba(0, 0, 0, 0.5)",n.style.background="gray",document.querySelector(".nav_main").style.borderBottom="1px solid rgba(0, 0, 0, 0.20)";l=s+1;o.setAttribute("cdata",l.toString()),a.style.display="none";var u="remove_hover_ico('"+t+"');";o.setAttribute("onmouseout",u)}}function signup_ifocus(e){document.querySelector("."+e);document.querySelector("."+e+" .highlighter").style.background="var(--hover-color)"}function remove_signup_ifocus(e){document.querySelector("."+e);document.querySelector("."+e+" .highlighter").style.background="rgba(0, 0, 0, 0.60)"}function dp_change(){var e=document.querySelector("#dpchange").files[0];if(e.size<=6291456){document.querySelector(".main_spinner").style.display="flex";var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".main_spinner").style.display="none","nai_er"==e?alertmain("The File is not an Image."):"isset_er"==e?alertmain("Server Error! Please Try again later."):document.querySelector(".main_settings .left .dp .img img").setAttribute("src","http://<?php echo $server; ?>/img_users/"+e)},800)}};var o=new FormData;o.append("file",e),t.open("POST","http://<?php echo $server; ?>/bscripts/temp/dpchange.temp.php"),t.withCredentials=!0,t.send(o)}else alertmain("The File is too Big. (Max-6mB)")}function animate_settings_toggle(e){var t,o=$(".toggle_settings ul .angle");4==(e=parseInt(e))&&loadfriends(),t=1==e?15:2==t?55:40*(e-1)+15,o.animate({top:t},180,"swing"),document.querySelector('.toggle_settings ul li[id="selected"]').setAttribute("id",""),document.querySelector('.toggle_settings ul li[name="'+e+'"]').setAttribute("id","selected"),document.querySelector(".main_settings .right #selected").setAttribute("id",""),document.querySelector(".main_settings .right .right_"+e).setAttribute("id","selected")}function togglepass(e,t){"show"==e?"current"==t?(document.querySelector(".current .fa-eye").style.display="none",document.querySelector(".current .fa-eye-slash").style.display="block",document.querySelector(".current input").setAttribute("type","text")):(document.querySelector(".new .fa-eye").style.display="none",document.querySelector(".new .fa-eye-slash").style.display="block",document.querySelector(".new input").setAttribute("type","text")):"current"==t?(document.querySelector(".current .fa-eye").style.display="block",document.querySelector(".current .fa-eye-slash").style.display="none",document.querySelector(".current input").setAttribute("type","password")):(document.querySelector(".new .fa-eye").style.display="block",document.querySelector(".new .fa-eye-slash").style.display="none",document.querySelector(".new input").setAttribute("type","password"))}function agechk(e,t,o){var r=new Date,n=r.getDate(),a=r.getMonth()+1,i=r.getFullYear();return i-o>10?1:i-o==10&&n-e>=0&&a-t>=0?1:0}function updategeneral(){document.querySelector(".main_spinner").style.display="flex";var e=document.querySelector(".right_1 .username input").value,t=document.querySelector(".right_1 .bio textarea").value,o=document.querySelector(".right_1 .birthday select[name=day]").value.toString(),r=document.querySelector(".right_1 .birthday select[name=month]").value.toString(),n=document.querySelector(".right_1 .birthday select[name=year]").value.toString(),a=o+"/"+r+"/"+n;if(agechk(o,r,n)){var i=document.querySelector(".right_1 .username input").getAttribute("toupdate"),c=new XMLHttpRequest;c.onreadystatechange=function(){if(4==this.readyState&&200==this.status){this.responseText;setTimeout(function(){document.querySelector(".main_spinner").style.display="none",alertmain("Account Updated!")},300)}};var s=new FormData;s.append("username",e),s.append("bio",t),s.append("birthday",a),s.append("toupdateusername",i),s.append("which","general"),c.open("POST","http://<?php echo $server; ?>/bscripts/actions/update_user.php"),c.withCredentials=!0,c.send(s)}else document.querySelector(".main_spinner").style.display="none",alertmain("Your age should be atleast 10 years.")}function updatesocial(){var e=document.querySelector(".right_2 .facebook input").value,t=document.querySelector(".right_2 .instagram input").value,o=document.querySelector(".right_2 .twitter input").value;document.querySelector(".main_spinner").style.display="flex";var r=new XMLHttpRequest;r.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;"1"==e?setTimeout(function(){document.querySelector(".main_spinner").style.display="none",alertmain("Your social media accounts are Updated!")},500):console.log(e)}};var n=new FormData;n.append("facebook",e),n.append("instagram",t),n.append("twitter",o),n.append("which","social"),r.open("POST","http://<?php echo $server; ?>/bscripts/actions/update_user.php"),r.withCredentials=!0,r.send(n)}function password_btn_toggle(){document.querySelector(".right_3 .new input").value.length>=8?(document.querySelector(".right_3 .change_pass_btn").removeAttribute("disabled"),document.querySelector(".right_3 .change_pass_btn").style.cursor="pointer"):(document.querySelector(".right_3 .change_pass_btn").setAttribute("disabled","true"),document.querySelector(".right_3 .change_pass_btn").style.cursor="auto")}function updatepassword(){var e=document.querySelector(".right_3 .current input").value,t=document.querySelector(".right_3 .new input").value;document.querySelector(".main_spinner").style.display="flex";var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".main_spinner").style.display="none",alertmain("1"==e?"Password Changed!":"no"==e?"Current Password is wrong!":"Server Error! Please try again later.")},650)}};var r=new FormData;r.append("current",e),r.append("new",t),r.append("which","security"),o.open("POST","http://<?php echo $server; ?>/bscripts/actions/update_user.php"),o.withCredentials=!0,o.send(r)}function loadfriends(e,t){var o=new XMLHttpRequest;o.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;try{var o=JSON.parse(e);document.querySelector(".right_4 .top h1 span").innerHTML="("+o.count+")",setTimeout(function(){null==t?document.querySelector(".right_4 .bottom .main").innerHTML=o.data:document.querySelector(".right_4 .bottom .main").innerHTML+=o.data,document.querySelector(".right_4 .spinner_main").style.display="none"},250)}catch(t){document.querySelector(".right_4 .top h1 span").innerHTML="(0)",document.querySelector(".right_4 .bottom .main").innerHTML=e}}};var r=new FormData;null==e?r.append("offset",0):r.append("offset",e),o.open("POST","http://<?php echo $server; ?>/bscripts/load/getfriendfromstorage.php"),o.withCredentials=!0,o.send(r)}function unfriend(e,t){var o=document.querySelector("#id"+e+" .spinner"),r=document.querySelector("#id"+e+" i");o.style.display="block",r.style.display="none";var n=new XMLHttpRequest;n.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var z=this.responseText;1==z?setTimeout(function(){document.querySelector("#id"+e+" .unfriend_confirm").style.display="block",o.style.display="none"},500):(o.style.display="none",alertmain("An error Occured!"),console.log(z))}};var a=new FormData;a.append("key",e),n.open("POST","http://<?php echo $server; ?>/bscripts/actions/unfriend.php"),n.withCredentials=!0,n.send(a)}function decline_request(e,t){document.querySelector("#overlay_"+t).style.display="flex";var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;1==e?setTimeout(function(){document.querySelector("#freq-items_"+t+" #decline_request").innerHTML="Declined &#10004;",document.querySelector("#freq-items_"+t+" #decline_request").style.width="70px",document.querySelector("#freq-items_"+t+" #decline_request").setAttribute("disabled","true"),document.querySelector("#freq-items_"+t+" #decline_request").style.cursor="auto",document.querySelector("#freq-items_"+t+" #accept_request").setAttribute("disabled","true"),document.querySelector("#freq-items_"+t+" #accept_request").style.cursor="auto",document.querySelector("#freq-items_"+t+" #accept_request").removeAttribute("onclick"),document.querySelector("#freq-items_"+t+" #decline_request").removeAttribute("onclick"),document.querySelector("#overlay_"+t).style.display="none"},650):(alertmain("An Error Occured!"),console.log(e))}},o.open("POST","http://<?php echo $server; ?>/bscripts/load/freq.php"),o.withCredentials=!0;var r=new FormData;r.append("which","decline"),r.append("key",e),r.append("id",t),o.send(r)}function upload_temp_page_dp(){var e=document.querySelector(".bottom_maincreatepage form .dps input").files[0];if(e.size<=6291456){document.querySelector(".bottom_maincreatepage .dps .spinner").style.display="block";var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".bottom_maincreatepage .dps .spinner").style.display="none","nai_er"==e?alertmain("The File is not an Image."):"isset_er"==e?alertmain("Server Error! Please Try again later."):(document.querySelector(".bottom_maincreatepage form .dps input").setAttribute("src",e),document.querySelector(".bottom_maincreatepage form .dps .upload img").setAttribute("src","http://<?php echo $server; ?>/temp_uploads/"+e),document.querySelector(".bottom_maincreatepage form .dps .upload img").style.display="block",document.querySelector(".bottom_maincreatepage form .dps .upload").setAttribute("class","upload upload-after"),document.querySelector(".bottom_maincreatepage form .dps .upload").setAttribute("upload-after-text","Click to Change"))},800)}};var o=new FormData;o.append("file",e),t.open("POST","http://<?php echo $server; ?>/bscripts/temp/imageupload.temp.php"),t.withCredentials=!0,t.send(o)}else alertmain("The File is too Big. (Max-6mB)")}function createpage(){var e=document.querySelector(".bottom_maincreatepage form .page_name input").value,t=document.querySelector(".bottom_maincreatepage form .page_email input").value,o=document.querySelector(".bottom_maincreatepage form .facebook input").value,r=document.querySelector(".bottom_maincreatepage form .instagram input").value,n=document.querySelector(".bottom_maincreatepage form .twitter input").value,a=document.querySelector(".bottom_maincreatepage form .dps input").getAttribute("src"),i=document.querySelector(".bottom_maincreatepage form .page_about textarea").value;if(document.querySelector(".main_spinner").style.display="flex",""==i&&(i="-"),""==a&&(a="-"),""==o&&(o="-"),""==r&&(r="-"),""==n&&(n="-"),""==e||""==t)alertmain("Please fill the name and email entries.");else{var c=new XMLHttpRequest;c.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".main_spinner").style.display="none","email_err"==e?alertmain("Please enter a real email."):"err"==e?alertmain("Please try again later."):"nemail_err"==e?alertmain("Page with same email already exists."):"char_err"==e?alertmain("Page Name should not contain spaces or special letters."):"len_err"==e?alertmain("Pagename length must atleast be 5 and atmost be 30."):"about_len_err"==e?alertmain("About content should be atmost 300."):"1"==e?alertmain("Your Page has been created."):(console.log(e),alertmain("Server Error! Please Try again later."))},700)}};var s=new FormData;s.append("name",e),s.append("email",t),s.append("facebook",o),s.append("instagram",r),s.append("twitter",n),s.append("pic",a),s.append("about",i),c.open("POST","http://<?php echo $server; ?>/bscripts/actions/createpage.php"),c.withCredentials=!0,c.send(s)}return!1}function open_mypageedit(e,t,o,r,n,a,i,c){document.querySelector(".settings_mypages_overlay").style.display="block",document.querySelector(".settings_mypages_overlay").setAttribute("which",e),document.querySelector(".settings_mypages_overlay .main .top span h2 span").innerHTML="("+decodeURI(o)+")",document.querySelector(".settings_mypages_overlay .main .bottom .name input").setAttribute("value",decodeURI(o)),document.querySelector(".settings_mypages_overlay .main .bottom .about textarea").innerHTML=decodeURIComponent(a),document.querySelector(".settings_mypages_overlay .main .bottom .email input").setAttribute("value",decodeURIComponent(c));var s=(i=JSON.parse(decodeURIComponent(i))).facebook,l=i.instagram,u=i.twitter;document.querySelector(".settings_mypages_overlay .main .bottom .social .facebook input").setAttribute("value",s),document.querySelector(".settings_mypages_overlay .main .bottom .social .instagram input").setAttribute("value",l),document.querySelector(".settings_mypages_overlay .main .bottom .social .twitter input").setAttribute("value",u),"-"==t?document.querySelector(".settings_mypages_overlay .main .bottom .dp .img img").setAttribute("src","http://<?php echo $server; ?>/img_pages/yellow.jpg"):document.querySelector(".settings_mypages_overlay .main .bottom .dp .img img").setAttribute("src","http://<?php echo $server; ?>/img_pages/"+t)}function updatepageinfo(e){document.querySelector(".main_spinner").style.display="flex";var t=document.querySelector(".settings_mypages_overlay .main .bottom .name input").value,o=document.querySelector(".settings_mypages_overlay .main .bottom .about textarea").value,r=document.querySelector(".settings_mypages_overlay .main .bottom .dp .img img").getAttribute("src"),n=document.querySelector(".settings_mypages_overlay .main .bottom .email input").value,a=document.querySelector(".settings_mypages_overlay .main .bottom .social .facebook input").value,i=document.querySelector(".settings_mypages_overlay .main .bottom .social .twitter input").value,c=document.querySelector(".settings_mypages_overlay .main .bottom .social .instagram input").value;""==a&&(a="-"),""==c&&(c="-"),r.includes("yellow.jpg")&&(r="-");var s={facebook:a,twitter:i,instagram:c};s=JSON.stringify(s);var l=new XMLHttpRequest;l.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;document.querySelector(".main_spinner").style.display="none","1"==e?(alertmain("Done!"),setTimeout(function(){document.querySelector(".settings_mypages_overlay").style.display="none"},1e3)):alertmain(e)}};var u=new FormData;u.append("name",t),u.append("about",o),u.append("photo",r),u.append("email",n),u.append("social",s),u.append("pid",e),u.append('dp_change', dp_change),l.open("POST","http://<?php echo $server; ?>/bscripts/actions/updatemypageinfo.php"),l.withCredentials=!0,l.send(u)}function dp_change_page(){var e=document.querySelector("#dpchangepage").files[0];if(e.size<=6291456){document.querySelector(".main_spinner").style.display="flex";var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".main_spinner").style.display="none","nai_er"==e?alertmain("The File is not an Image."):"isset_er"==e?alertmain("Server Error! Please Try again later."):document.querySelector(".settings_mypages_overlay .main .bottom .dp .img img").setAttribute("src","http://<?php echo $server; ?>/temp_uploads/"+e)},800);dp_change=1;}};var o=new FormData;o.append("file",e),t.open("POST","http://<?php echo $server; ?>/bscripts/temp/dpchangepage.temp.php"),t.withCredentials=!0,t.send(o)}else alertmain("The File is too Big. (Max-6mB)")}function load_mypages(){var e=new XMLHttpRequest;e.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;"0"==e?setTimeout(function(){document.querySelector(".main_mypages .bottom .spinner_").style.display="none",document.querySelector(".main_mypages .bottom .items").innerHTML="<span style='position: absolute;left: 50%;color: gray;text-align: center;top: 10px;transform: translate(-50%, 0%)'>You do not have any page.</span>"},300):setTimeout(function(){document.querySelector(".main_mypages .bottom .spinner_").style.display="none",document.querySelector(".main_mypages .bottom .items").innerHTML=e},300)}},e.open("POST","http://<?php echo $server; ?>/bscripts/load/mypages_load.php"),e.withCredentials=!0,e.send()}function open_uploadpost_overlay(e,t,o,r){document.querySelector(".upload_post_overlay").style.display="block",document.querySelector(".upload_post_overlay").setAttribute("which",e),document.querySelector(".upload_post_overlay").setAttribute("name",t),document.querySelector(".upload_post_overlay").setAttribute("pc",o),document.querySelector(".upload_post_overlay").setAttribute("pagedp",r)}function uploadmemeimage(){var e=document.querySelector("#memeuploadinput").files[0];if(e.size<=6291456){document.querySelector(".uploadimage .iconcontainer .spinner").style.display="block";var t=new XMLHttpRequest;t.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){document.querySelector(".uploadimage .iconcontainer .spinner").style.display="none","nai_er"==e?(alertmain("The File is not an Image."),document.querySelector(".upload_post_overlay .main .top button").setAttribute("disabled","true"),document.querySelector(".upload_post_overlay .main .top button").style.cursor="auto"):"isset_er"==e?(alertmain("Server Error! Please Try again later."),document.querySelector(".upload_post_overlay .main .top button").setAttribute("disabled","true"),document.querySelector(".upload_post_overlay .main .top button").style.cursor="auto"):($(".uploadimage").animate({top:"10px"},"fast","linear"),document.querySelector(".uploadimage .iconcontainer i").setAttribute("class","far fa-exchange"),document.querySelector(".uploadimage #infoupload").innerHTML="Click to Change",document.querySelector(".imagecontainer").style.display="block",document.querySelector(".imagecontainer img").setAttribute("src","http://<?php echo $server; ?>/temp_uploads/"+e),document.querySelector(".imagecontainer img").setAttribute("data-src","temp_uploads/"+e),document.querySelector(".upload_post_overlay .main .top button").removeAttribute("disabled"),document.querySelector(".upload_post_overlay .main .top button").style.cursor="pointer")},800)}};var o=new FormData;o.append("file",e),t.open("POST","http://<?php echo $server; ?>/bscripts/temp/memeimageupload.temp.php"),t.withCredentials=!0,t.send(o)}else alertmain("The File is too Big. (Max-6mB)")}function addtagstoggle(){document.querySelector(".upload_post_overlay .bottom .right .addtagsinfo").style.display="none",document.querySelector(".upload_post_overlay .bottom .right .addtags_container").style.display="block",document.querySelector(".upload_post_overlay .bottom .right .addtags").style.display="block"}function uploadmeme(){var e,t=[],o=$(".addtags input[type=checkbox]:checked").length;if(o>0){var r,n=$(".addtags input[type=checkbox]:checked");for(e=0;e<=o-1;e++)r=n[e].value,t.push(r)}else t=[];var a=document.querySelector(".imagecontainer img").getAttribute("data-src"),i=document.querySelector(".upload_post_overlay").getAttribute("which"),c=document.querySelector(".upload_post_overlay .right .caption textarea").value,s=document.querySelector(".upload_post_overlay").getAttribute("name"),l=document.querySelector(".upload_post_overlay").getAttribute("pc"),u=document.querySelector(".upload_post_overlay").getAttribute("pagedp");document.querySelector(".upload_post_overlay .main .top .spinner").style.display="block";var d=new XMLHttpRequest;d.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;setTimeout(function(){document.querySelector(".upload_post_overlay .main .top .spinner").style.display="none",1==e?alertmain("Upload Complete!"):(alertmain("Server Error! Please Try again Later."),console.log(e)),document.querySelector(".overlay").innerHTML="",document.querySelector(".overlay").style.display="none",document.querySelector(".uploadimage .iconcontainer i").setAttribute("class","far fa-arrow-alt-up"),document.querySelector(".uploadimage #infoupload").innerHTML="(Only JPEG, PNG or JPG)",document.querySelector(".imagecontainer").style.display="none",document.querySelector(".upload_post_overlay .main .top button").setAttribute("disabled","true"),document.querySelector(".upload_post_overlay .main .top button").style.cursor="auto",document.querySelector(".imagecontainer img").setAttribute("src",""),document.querySelector(".upload_post_overlay").style.display="none",document.querySelector(".upload_post_overlay").removeAttribute("which"),document.querySelector(".upload_post_overlay").removeAttribute("name"),document.querySelector(".upload_post_overlay").removeAttribute("pc"),document.querySelector(".upload_post_overlay").removeAttribute("pagedp")},500)}};var p=new FormData;p.append("which",i),p.append("img",a),p.append("tags",JSON.stringify(t)),p.append("pagename",s),p.append("pc",l),p.append("pagedp",u),c&&p.append("caption",c),d.open("POST","http://<?php echo $server; ?>/bscripts/actions/uploadmeme.php"),d.withCredentials=!0,d.send(p)}function slidedrop_post(e,t){parseInt(e.getAttribute("dataclick"))%2==0?(document.querySelector("#f"+t+" .top .extraicon i").style.color="var(--hover-color)",document.querySelector("#f"+t+" .top .extraicon i").style.fontWeight="bolder",document.querySelector("#f"+t+" .top .extraicon i").style.fontSize="18px",document.querySelector("#f"+t+" .top").style.borderBottom="2px solid var(--hover-color)",document.querySelector("#f"+t+" .top .pdp").style.border="2px solid var(--hover-color)",$("#drop_"+t).slideDown(200,"swing"),e.setAttribute("dataclick","1")):(document.querySelector("#f"+t+" .top .extraicon i").style.color="#222",document.querySelector("#f"+t+" .top .extraicon i").style.fontWeight="bold",document.querySelector("#f"+t+" .top .extraicon i").style.fontSize="16px",document.querySelector("#f"+t+" .top").style.borderBottom="1px solid var(--main-border)",document.querySelector("#f"+t+" .top .pdp").style.border="1px solid var(--main-border)",$("#drop_"+t).slideUp("fast"),e.setAttribute("dataclick","0"))}function unfollowpage_thrposts(e,t){document.querySelector("#f"+t+" .top .extraicon i").style.color="#222",document.querySelector("#f"+t+" .top .extraicon i").style.fontWeight="bold",document.querySelector("#f"+t+" .top .extraicon i").style.fontSize="16px",document.querySelector("#f"+t+" .top").style.borderBottom="1px solid var(--main-border)",document.querySelector("#f"+t+" .top .pdp").style.border="1px solid var(--main-border)",$("#drop_"+t).slideUp("fast"),document.querySelector("#f"+t+" .top .extraicon i").setAttribute("dataclick","0");var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){alertmain(1==e?"Unfollowed!":0==e?"An error occured.":e)},40)}};var r=new FormData;r.append("pid",e),r.append("action","unfollow"),o.open("POST","http://<?php echo $server; ?>/bscripts/actions/followpage.php"),o.withCredentials=!0,o.send(r)}function followpage_thrposts(e,t){document.querySelector("#f"+t+" .top .extraicon i").style.color="#222",document.querySelector("#f"+t+" .top .extraicon i").style.fontWeight="bold",document.querySelector("#f"+t+" .top .extraicon i").style.fontSize="16px",document.querySelector("#f"+t+" .top").style.borderBottom="1px solid var(--main-border)",document.querySelector("#f"+t+" .top .pdp").style.border="1px solid var(--main-border)",$("#drop_"+t).slideUp("fast"),document.querySelector("#f"+t+" .top .extraicon i").setAttribute("dataclick","0");var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;setTimeout(function(){alertmain(1==e?"Followed!":0==e?"An error occured.":e)},40)}};var r=new FormData;r.append("pid",e),r.append("action","follow"),o.open("POST","http://<?php echo $server; ?>/bscripts/actions/followpage.php"),o.withCredentials=!0,o.send(r)}function reacttopost(e,t,o){if("positive"==e&&(document.querySelector("#f"+o+" .post_actions .left i").setAttribute("id","animatepositive"),document.querySelector("#f"+o+" .post_actions .left span").style.fontWeight="bold",document.querySelector("#f"+o+" .post_actions .left span").style.color="var(--hover-color)",document.querySelector("#f"+o+" .post_actions .left span").style.fontSize="13px"),"positive"==e){var r=parseInt(document.querySelector("#f"+o+" .post_actions #like_count").getAttribute("data-react"));r+=1,document.querySelector("#f"+o+" .post_actions #like_count").setAttribute("data-react",r.toString()),document.querySelector("#f"+o+" .post_actions #like_count").innerHTML="("+r.toString()+")",document.querySelector("#f"+o+" .post_actions .left").removeAttribute("onclick"),document.querySelector("#f"+o+" .post_actions .left").style.cursor="auto",document.querySelector("#f"+o+" .post_actions .right").removeAttribute("onclick"),document.querySelector("#f"+o+" .post_actions .right").style.cursor="auto"}else{r=parseInt(document.querySelector("#f"+o+" .post_actions #dislike_count").getAttribute("data-react"));r+=1,document.querySelector("#f"+o+" .post_actions #dislike_count").setAttribute("data-react",r.toString()),document.querySelector("#f"+o+" .post_actions #dislike_count").innerHTML="("+r.toString()+")",document.querySelector("#f"+o+" .post_actions .left").removeAttribute("onclick"),document.querySelector("#f"+o+" .post_actions .left").style.cursor="auto",document.querySelector("#f"+o+" .post_actions .right").removeAttribute("onclick"),document.querySelector("#f"+o+" .post_actions .right").style.cursor="auto"}var n=new XMLHttpRequest;n.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;"1"==e||(console.log(e),alertmain("Couldn't react to the post"))}};var a=new FormData;a.append("mid",t),a.append("which",e),n.open("POST","http://<?php echo $server; ?>/bscripts/actions/reacttopost.php"),n.withCredentials=!0,n.send(a)}function loadlazychat(){var e=new XMLHttpRequest;e.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var e=this.responseText;try{var t=(e=JSON.parse(e)).count,o=e.data;try{document.querySelector(".onnfrnds").innerHTML=o,document.querySelector(".onlinefrnds #line").getAttribute("data-value").includes("On")&&(document.querySelector(".onlinefrnds #line").setAttribute("data-value","Online Friends ("+t+")"),document.querySelector(".onlinefrnds #line").setAttribute("f_count",t))}catch(e){}}catch(t){document.querySelector(".onlinefrnds #line").setAttribute("data-value","Online Friends (0)"),document.querySelector(".onnfrnds").innerHTML=e}document.querySelector("#offonchatspin").style.display="none"}},e.open("POST","http://<?php echo $server; ?>/bscripts/load/onlinefrnds.php"),e.withCredentials=!0,e.send()}function chatanimation(){document.querySelector(".onlinefrnds #line").setAttribute("class","anim"),setTimeout(function(){document.querySelector(".onlinefrnds #line").setAttribute("class","")},900);var e=parseInt(document.querySelector(".onlinefrnds #line").getAttribute("data-change"));if(e%2==1){document.querySelector(".onnfrnds").style.display="none",document.querySelector("#offonchatspin").style.display="block",setTimeout(function(){document.querySelector("#offonchatspin").style.display="none",document.querySelector(".chathistory").style.display="block",document.querySelector(".onlinefrnds #line").setAttribute("data-value","Chat History")},1250),clearInterval(loadchatinterval),loadchathistory(0,12,null),document.querySelector(".chatwindow").setAttribute("data-which","history");try{document.querySelector(".right_more_ i").removeAttribute("id")}catch(e){}}else{document.querySelector(".onnfrnds").style.display="block",document.querySelector(".chathistory").style.display="none",document.querySelector(".onlinefrnds #line").setAttribute("data-value","Online Friends (0)");var t=document.querySelector(".left_divider .top_heading").getAttribute("state");clearInterval(loadchathistoryinterval),"enabled"==t&&(loadchatinterval=setInterval(function(){loadlazychat()},2500),document.querySelector(".chatwindow").setAttribute("data-which","chat"))}e+=1,document.querySelector(".onlinefrnds #line").setAttribute("data-change",e.toString())}function disablechat(){var e=document.querySelector(".left_more_").getAttribute("cdata");parseInt(e)%2==1?(clearInterval(loadchatinterval),document.querySelector(".onnfrnds").innerHTML="",document.querySelector("#offonchatspin").style.display="block",document.querySelector(".left_divider .top_heading").setAttribute("state","disabled"),setTimeout(function(){document.querySelector(".center_more_").style.color="#cc0000",document.querySelector("#offonchatspin").style.display="none",document.querySelector(".onnfrnds").innerHTML="",document.querySelector(".onnfrnds").innerHTML="<span class='chat_off_warning'><br><h1>You are Offline!</h1><br><font style='color: #262626'>Click the power button</font> <br> <i class='fas fa-power-off fa-4x' style='position: relative;top:2px;color: #262626'></i> <br> <font style='position: relative;top: 4px;'>to enable Chat.</font></span>",document.querySelector(".onnfrnds").innerHTML="<img src='http://<?php echo $server; ?>/img/panda_offline3.jpg' width='260' height='260'>",document.querySelector(".onlinefrnds #line").setAttribute("data-value","Online Friends (0)")},2001)):(document.querySelector("#offonchatspin").style.display="block",document.querySelector(".onnfrnds").innerHTML="",document.querySelector(".left_divider .top_heading").setAttribute("state","enabled"),setTimeout(function(){document.querySelector(".center_more_").style.color="#00b300",loadchatinterval=setInterval(function(){loadlazychat()},2500)},1500));var t=parseInt(e)+1;document.querySelector(".left_more_").setAttribute("cdata",t.toString())}function loadchathistory(e,t,o){e=parseInt(e),t=parseInt(t),null==o||(o=parseInt(o));var r=new XMLHttpRequest;r.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;0==e?document.querySelector(".chathistory").innerHTML="<center><span style='font-size:12px;color: gray;'>You haven't started a conversation with anyone yet.</span></center>":(document.querySelector(".chathistory").innerHTML=e,loadchathistoryinterval=setInterval(function(){loadchathistorytchk(0,12,null)},2500))}},r.open("GET","http://<?php echo $server; ?>/bscripts/chat/loadchathistory.php?offset="+e+"&limit="+t+"&total="+o),r.withCredentials=!0,r.send()}function loadchathistorytchk(e,t,o){var r=new XMLHttpRequest,n=encodeURIComponent(document.querySelector(".item_chat .details ul .last_message").innerHTML);r.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;if(0==e);else{var t=(e=JSON.parse(e)).uid;document.querySelector(".item_chat[uid="+t+"]").remove();var o=document.querySelector(".chathistory").innerHTML;document.querySelector(".chathistory").innerHTML=e.html+o}}},r.open("GET","http://<?php echo $server; ?>/bscripts/chat/loadchathistory.php?offset="+e+"&\tlimit="+t+"&total="+o+"&lmess="+n),r.withCredentials=!0,r.send()}function removechatwindow(e){document.querySelector(".chatwindow").style.display="none",document.querySelector(".chatwindow").setAttribute("data-username",""),document.querySelector(".chatwindow").setAttribute("data-fullname",""),document.querySelector(".chatwindow").setAttribute("data-cid",""),document.querySelector(".chatwindow").setAttribute("data-uid",""),document.querySelector(".chatwindow").setAttribute("data-pic",""),clearInterval(getcidinterval),clearInterval(updatelmesinterval),"history"==e?(loadchathistory(0,12,null),document.querySelector(".onlinefrnds #line").setAttribute("data-value","Chat History")):document.querySelector(".onlinefrnds #line").setAttribute("data-value","Online Friends ("+document.querySelector(".onlinefrnds #line").getAttribute("f_count")+")")}function loadchat(e,t,o,r,n,a,i){document.querySelector(".mainchat .spinner").style.display="block";var c=new XMLHttpRequest;c.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;document.querySelector(".mainchat .spinner").style.display="none";var t=document.querySelector(".mainchat .maindata ul").innerHTML;document.querySelector(".mainchat .maindata ul").innerHTML=t+e;try{document.querySelector("#"+i).style.display="none"}catch(e){}document.querySelector("#chat_mess_input").focus(),document.querySelector("#chat_mess_input").setAttribute("value","")}};var s=new FormData;null==r&&(r=0),null==n&&(n=15),null==a&&(a=0),s.append("offset",r),s.append("limit",n),s.append("username",o),s.append("allmesscount",a),s.append("uid_f",e),s.append("uid_m",t),c.open("POST","http://<?php echo $server; ?>/bscripts/chat/loadchats.php"),c.withCredentials=!0,c.send(s)}function searchbar_toggle(e){if(window.innerWidth>924)document.querySelector(".ico_search").style.display="none",document.querySelector(".search_bar").style.display="flex";else if("open"==e){var t=document.querySelector(".ico_search"),o=document.querySelector(".search_resp_dropdown");document.querySelector(".nav_main").style.borderBottom="2px solid var(--hover-color)",o.style.display="flex";var r=t.getBoundingClientRect(),n=window.innerWidth-r.x-30.5;o.style.right=n.toString()+"px",t.setAttribute("onclick",'searchbar_toggle("close")'),o.style.width="285px",o.style.height="60px"}else{t=document.querySelector(".ico_search"),o=document.querySelector(".search_resp_dropdown");document.querySelector(".nav_main").style.borderBottom="1px solid var(--main-border)",o.style.display="none",t.setAttribute("onclick",'searchbar_toggle("open")')}}function focussearchbar(){document.querySelector(".search_bar .highlgtr").style.background="var(--hover-color)",document.querySelector(".search_resp_dropdown .highlgtr").style.background="var(--hover-color)"}function focussoutsearchbar(){document.querySelector(".search_bar .highlgtr").style.background="gray",document.querySelector(".search_resp_dropdown .highlgtr").style.background="gray"}function search(e,t,o,r){try{document.querySelector(".overlay").style.display="none",document.querySelector(".search_resp_dropdown").style.display="none"}catch(e){}var n=new XMLHttpRequest;document.querySelector(".main_search .bottom .spinner").style.display="block",document.querySelector(".main_search .bottom .content").innerHTML="",n.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var e=this.responseText;document.querySelector(".search_overlay").style.display="block",setTimeout(function(){document.querySelector(".main_search .bottom .spinner").style.display="none",null==r?document.querySelector(".main_search .bottom .content").innerHTML=e:document.querySelector(".main_search .bottom .content").innerHTML+=e},450)}};var a=new FormData;a.append("query",e),a.append("which",t),null==o?a.append("offset",0):a.append("offset",o),n.open("POST","http://<?php echo $server; ?>/bscripts/load/search.php"),n.withCredentials=!0,n.send(a)}function entercapture(e,t,o){document.querySelector(".resp_search_input").value=o,o.length>0&&"Enter"==e.key&&(search(o,"people"),document.querySelector(".typetoggle option[value=people]").removeAttribute("selected"),document.querySelector(".typetoggle #toggle option[value=people]").setAttribute("selected","1"))}function send_freq(e,t){var o=document.querySelector("#spb_"+e+" .send_req .spinner"),r=document.querySelector("#spb_"+e+" .send_req i");o.style.display="block",r.style.display="none";var n=new XMLHttpRequest;n.onreadystatechange=function(){if(200==this.status&&4==this.readyState){var t=this.responseText;setTimeout(function(){o.style.display="none",3==t?(alertmain("You cannot send friend request to yourself."),r.style.display="block"):2==t?(alertmain("You have already sent a friend request to this user."),r.style.display="block"):4==t?(alertmain("This user has already sent you a friend request."),r.style.display="block"):1==t?document.querySelector("#spb_"+e+" .send_req #friend_req_sent").style.display="block":console.log(t)},50)}};var a=new FormData;a.append("uid",t),a.append("which","sendreq"),n.open("POST","http://<?php echo $server; ?>/bscripts/load/freq.php"),n.withCredentials=!0,n.send(a)}function followpage(e,t){document.querySelector("#spageb_"+t+" .followicon .main").style.display="none",document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="block";var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var o=this.responseText;setTimeout(function(){if(1==o){document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="none",document.querySelector("#spageb_"+t+" .followicon .main").style.display="flex",document.querySelector("#spageb_"+t+" .followicon .main i").style.color="var(--hover-color)",document.querySelector("#spageb_"+t+" .followicon .main i").style.fontWeight="bold",document.querySelector("#spageb_"+t+" .followicon .main i").setAttribute("onclick",'unfollowpage("'+e+'", "'+t+'")');var r=parseInt(document.querySelector("#spageb_"+t+" .followicon .main .followcount").innerHTML)+1;document.querySelector("#spageb_"+t+" .followicon .main .followcount").innerHTML=r}else 0==o?(document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="none",document.querySelector("#spageb_"+t+" .followicon .main").style.display="flex",alertmain("Server Error! Please try again later.")):alertmain(o)},40)}};var r=new FormData;r.append("pid",e),r.append("action","follow"),o.open("POST","http://<?php echo $server; ?>/bscripts/actions/followpage.php"),o.withCredentials=!0,o.send(r)}function unfollowpage(e,t){document.querySelector("#spageb_"+t+" .followicon .main").style.display="none",document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="block";var o=new XMLHttpRequest;o.onreadystatechange=function(){if(4==this.readyState&&200==this.status){var o=this.responseText;setTimeout(function(){if(1==o){document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="none",document.querySelector("#spageb_"+t+" .followicon .main").style.display="flex",document.querySelector("#spageb_"+t+" .followicon .main i").style.color="#333";var r=parseInt(document.querySelector("#spageb_"+t+" .followicon .main .followcount").innerHTML)-1;document.querySelector("#spageb_"+t+" .followicon .main .followcount").innerHTML=r,document.querySelector("#spageb_"+t+" .followicon .main i").setAttribute("onclick",'followpage("'+e+'", "'+t+'")')}else 0==o?(document.querySelector("#spageb_"+t+" .followicon .spinner").style.display="none",document.querySelector("#spageb_"+t+" .followicon .main").style.display="flex",alertmain("Server Error! Please try again later.")):alertmain(o)},40)}};var r=new FormData;r.append("pid",e),r.append("action","unfollow"),o.open("POST","http://<?php echo $server; ?>/bscripts/actions/followpage.php"),o.withCredentials=!0,o.send(r)}setInterval(function(){update_freqs(1)},6500),updateconteinterval=setInterval(function(){update_content()},5e3),window.addEventListener("keydown",function(e){27==e.keyCode&&(document.querySelector(".overlay").style.display="none")});		
	</script>

	<script>
		function download_post(pgname, imagelink) {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200){
					document.querySelector('.downloadmeme').setAttribute('href', 'http://<?php echo $server; ?>/labelled_images/'+imagelink);
					document.querySelector('.downloadmeme').setAttribute('download', imagelink);
					document.querySelector('.downloadmeme').click();
				}
			}
        	xml.onerror = function() {
            	console.clear();
            	var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function() {
						if(this.readyState==4 && this.status==200) {
							document.querySelector('.downloadmeme').setAttribute('href', 'http://<?php echo $server; ?>/labelled_images/'+imagelink);
							document.querySelector('.downloadmeme').setAttribute('download', imagelink);
							document.querySelector('.downloadmeme').click();
						}
					}
					xhr.open("GET", "http://localhost/memelok/image/labelimage?pname="+pgname+"&imlink="+imagelink);
					xhr.send();
            }
			xml.open("HEAD", "http://<?php echo $server; ?>/labelled_images/"+imagelink);
			xml.send();
	
		}

    
	</script>
<!--
<script defer src="http://localhost/memelok/chat/socket.io/socket.io.js"></script>
<script defer src="http://<?php echo $server; ?>/bscripts/chat/client.js"></script>
!-->
</body>
</html>
