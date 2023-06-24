<?php

session_start();
ob_start();

include 'bscripts/actions/dbh.php';

$server = explode('.', $_SERVER['SERVER_NAME']);
$domain = count($server)-1;
$host = count($server)-2;
$server = $server[$host].".".$server[$domain];

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


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8">
	<title>Home - Memelok | Memes | Best Indian memes | Best Memes Collection</title>
	<link rel="stylesheet" type="text/css" href="css/index.css">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="fontawesome/css/all.min.css">
	<script src="scripts/jquery.js"></script>
	<meta name="description" content="Log in or Create an account on Memelok. Get the best Indian memes and funny memes from best memers around India, chat with your friends">
	<meta name="keywords" content="home, memelok home, home page, memelok, memes, meme, best indian memes, best memes collection, funny memes, best memes, adult memes">
	<meta name="robots" content="index, follow">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="English">

</head>
<body>

	<!-- nav dropdowns !-->
	
		<a class="downloadmeme"></a>

		<div class="overlay_responsive">
			
		</div>

		<audio id="message_noti">
		  <source src="sounds/insight.ogg" type="audio/ogg">
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
								xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/freq.php");
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
						xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/markallnotiread.php");
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
							xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/notifications.php");
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

			<div class="right_more_" onclick="chatanimation();"><i class="far fa-exchange-alt" style="cursor: pointer;"></i></div>

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
					
					<div class="dp"><img src="" alt="pop"></div>
					&nbsp;&nbsp;
					<span style="font-family: ;font-size: 13px;" id='chat_username'>Username</span>
					&nbsp;&nbsp;
					<span style="color: #00b300;font-size: 20px;" class="onlinestatus" style="display: none;">&bull;</span>

				</div>

				<div class="mainchat">
					
					<div class="loader spinner"></div>

					<div class="maindata">
						<ul>
							
						</ul>
						
					</div>

				</div>				

				<div class="bottom_chatm">
						
					<input type="text" placeholder="Enter a Message" id='chat_mess_input' onkeyup="messevent(event);">

					<script>
						function messevent(e) {
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
				<img src="img/logo_dev1.png" alt="">
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
					xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/loadposts.php");
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
					
					<div id='morelinks'><a href="/about-us" target="_blank" style="text-decoration: none;"><span>About</span></a> &bull; <a href="/terms" target="_blank" style="text-decoration: none;"><span>Terms</span></a> &bull; <a href="/terms" target="_blank" style="text-decoration: none;"><span style="">Give us a Feedback</span></a></div>
					<span style="font-size: 12px;color: gray;">A PS Production &copy; <?php echo date('Y'); ?>. </span>	
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

	<script>

		var getcidinterval;
		var updatelmesinterval;
		var loadchatinterval;
		var loadchathistoryinterval;
		var updateconteinterval;

		function update_freqs(e) {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					if(e!=undefined) {
						if(resp!=localStorage.getItem('freqs_count')) {
							document.querySelector('#ico1 i').classList.add('active');
						}	
					}

					localStorage.setItem('freqs_count', resp);
				}
			}
			xml.open('GET', 'http://content.<?php echo $server; ?>/bscripts/load/update_freqs.php');
			xml.withCredentials = true;
			xml.send();
		}

		setInterval(function(){update_freqs(1);}, 6500);

		function update_content() {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status=200 && this.readyState==4) {
					var resp = this.responseText;
					var json = JSON.parse(resp);

					if(json.noti_count>json.oldnoticount) {
						document.querySelector('#ico2 i').classList.add('active');
					}
				}
			}

			var state = document.querySelector('.left_divider .top_heading').getAttribute('state');
			var formdata = new FormData();
			formdata.append('state', state);
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/update_content.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}
		updateconteinterval = setInterval(function(){
			update_content();
		}, 5000);

		function hover_ico(e) {
			if(e=='ico1') {
				var dom = document.querySelector('#ico1');
				var dom_ico = document.querySelector('#ico1 i');
				var dom_hg = document.querySelector('#ico1 .hghlgtr');
			}
			else if(e=='ico2') {
				var dom = document.querySelector('#ico2');
				var dom_ico = document.querySelector('#ico2 i');
				var dom_hg = document.querySelector('#ico2 .hghlgtr');
			}
			else {
				var dom = document.querySelector('#ico3');
				var dom_ico = document.querySelector('#ico3 i');
				var dom_hg = document.querySelector('#ico3 .hghlgtr');
			}

			dom_ico.style.color = "var(--hover-color)";
			dom_hg.style.background = "var(--hover-color)";

		}

		function remove_hover_ico(e) {	
			if(e=='ico1') {
				var dom = document.querySelector('#ico1');
				var dom_ico = document.querySelector('#ico1 i');
				var dom_hg = document.querySelector('#ico1 .hghlgtr');
			}
			else if(e=='ico2') {
				var dom = document.querySelector('#ico2');
				var dom_ico = document.querySelector('#ico2 i');
				var dom_hg = document.querySelector('#ico2 .hghlgtr');
			}
			else {
				var dom = document.querySelector('#ico3');
				var dom_ico = document.querySelector('#ico3 i');
				var dom_hg = document.querySelector('#ico3 .hghlgtr');
			}

			dom_ico.style.color = "rgba(0, 0, 0, 0.5)";
			dom_hg.style.background = "gray";
		}	

		function dropdownaction(e) {
			var link = "";
			var spinner =  document.querySelector('.main_spinner');

			var f = e;

			spinner.style.display = "flex";

			try {
				document.querySelector('.search_overlay').style.display = "none";
			}
			catch(err) {
				
			}

			if(e=='logout') {
				link = 'logout.php';

				clearInterval(updateconteinterval);

				localStorage.clear();

				setTimeout(function(){
					spinner.style.display = "none";
					window.location = "http://<?php echo $server; ?>/logout.php";
				}, 900);
			}
			else {
				link = 'http://content.<?php echo $server; ?>/bscripts/'+e+'.php';
				if(e=='profile') {
					link = 'http://content.<?php echo $server; ?>/bscripts/'+e+'.php?id=<?php echo $_SESSION['UID']; ?>';
				}
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function() {
							
							if(e=='settings' && window.innerWidth<950 && window.innerWidth>650) {
								document.querySelector('.overlay_responsive').innerHTML = "";	
								document.querySelector('.overlay_responsive').style.display = "flex";
								spinner.style.display = "none";
								document.querySelector('.overlay_responsive').innerHTML = resp;
							}	
							else {
								document.querySelector('.overlay').innerHTML = "";	
								document.querySelector('.overlay').style.display = "block";
								spinner.style.display = "none";
								document.querySelector('.overlay').innerHTML = resp;
				
								if(f=='mypages') {
									load_mypages();
								}
								setTimeout(function() {
									document.querySelector('#ico3 i').style.color = "rgba(0, 0, 0, 0.5)";
									document.querySelector('#ico3 .hghlgtr').style.background = "gray";
									document.querySelector(".usrstngs_dropdown").style.display = "none";
									document.querySelector('.nav_main').style.borderBottom = "1px solid rgba(0, 0, 0, 0.20)";
									document.querySelector('#ico3').setAttribute('cdata', "1");
									document.querySelector('#ico3').setAttribute('onmouseout', "remove_hover_ico('ico3')");
								}, 4500);
							}

						}, 1225);
					}
				}
				xml.open("POST", link);
				xml.withCredentials = true;
				xml.send();
			}


		}

		window.addEventListener('keydown', function(e) {
			if(e.keyCode==27) {
				document.querySelector('.overlay').style.display = "none";
			}
		});

		document.addEventListener('readystatechange', event => {

			    if (event.target.readyState === "complete") {
			    	document.querySelector('#offonchatspin').style.display = "block";
			    	document.querySelector('.postloader').style.display = "block";
					localStorage.setItem('uid', '<?php echo $_SESSION['UID']; ?>');			    	
			    	setTimeout(function() {
			    		loadlazyposts();
			    	}, 800);

			    	setTimeout(function() {
			    		loadlazychat();
			    	}, 900);

			    	setTimeout(function(){
			    		loadlazypgsugg();
			    	}, 900);

			    	setInterval(function(){
			    		var xml = new XMLHttpRequest();
			    		xml.onreadystatechange = function() {
			    			if(this.readyState==4 && this.status==200) {
			    				if(this.responseText!='<?php echo $_COOKIE['PSID']; ?>') {
			    					var r = confirm("A computer has loggedin from your account. You are being logged out.");
			    					if(r==true) {
			    						window.location = "http://<?php echo $server; ?>/logout.php";
			    					}
			    					else {
			    						window.location = "http://<?php echo $server; ?>/logout.php";	
			    					}
			    					
			    				}
			    			}
			    		}
			    		xml.open("GET", "http://content.<?php echo $server ?>/bscripts/actions/chksessid.php");
			    		xml.withCredentials = true;
			    		// xml.send();
			    	}, 10000);

			    	update_freqs();
					
			    }

			});

			function loadlazypgsugg() {
				var xml = new XMLHttpRequest();
				document.querySelector('.pgsugg .spinner').style.display = "none";
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						document.querySelector('.pgsugg ul').innerHTML = resp;
					} 
				}
				var formdata = new FormData();
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/pgsugg.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}

			function alertmain(cont) {
				document.querySelector('.alert_main .info_content span').innerHTML = cont;
				document.querySelector('.alert_main').style.display = "block";
				setTimeout(function(){
					document.querySelector('.alert_main').style.display = "none";
				}, 4200);
			}

			function enablebtn() {
				try{
				document.querySelector('.right_2 #editbtn').removeAttribute('disabled');
				document.querySelector('.right_2 #editbtn').style.cursor = 'pointer';
				}
				catch(e) {
					console.error(e);
				}
			}
			
			function open_nav_dropdown(e) {
				var i = "";
				if(e=='freq') {
					var dom = document.querySelector('#ico1');
					var dom_ico = document.querySelector('#ico1 i');
					var dom_hg = document.querySelector('#ico1 .hghlgtr');
					var dom_dropdown = document.querySelector(".freq_dropdown");
					document.querySelector('#ico2').setAttribute('cdata', '1');
					document.querySelector('#ico3').setAttribute('cdata', '1');
					document.querySelector('#ico2').setAttribute('onmouseout', "remove_hover_ico('ico2')");
					document.querySelector('#ico3').setAttribute('onmouseout', "remove_hover_ico('ico3')");
					i="ico1";
					document.querySelector('#ico1 i').classList.remove('active');
					dom_dropdown.style.width = "350px";
					dom_dropdown.style.height = "445px";
				}
				else if(e=='noti') {
					var dom = document.querySelector('#ico2');
					var dom_ico = document.querySelector('#ico2 i');
					var dom_hg = document.querySelector('#ico2 .hghlgtr');
					var dom_dropdown = document.querySelector(".noti_dropdown");
					document.querySelector('#ico1').setAttribute('cdata', '1');
					document.querySelector('#ico3').setAttribute('cdata', '1');
					document.querySelector('#ico1').setAttribute('onmouseout', "remove_hover_ico('ico1')");
					document.querySelector('#ico3').setAttribute('onmouseout', "remove_hover_ico('ico3')");
					i='ico2';
					document.querySelector('#ico2 i').classList.remove('active');
					dom_dropdown.style.width = "350px";
					dom_dropdown.style.height = "395px";
				}
				else {
					var dom = document.querySelector('#ico3');
					var dom_ico = document.querySelector('#ico3 i');
					var dom_hg = document.querySelector('#ico3 .hghlgtr');
					var dom_dropdown = document.querySelector(".usrstngs_dropdown");
					document.querySelector('#ico2').setAttribute('cdata', '1');
					document.querySelector('#ico1').setAttribute('cdata', '1');
					document.querySelector('#ico2').setAttribute('onmouseout', "remove_hover_ico('ico2')");
					document.querySelector('#ico1').setAttribute('onmouseout', "remove_hover_ico('ico1')");
					i='ico3';
				}

				var rect = dom.getBoundingClientRect();
				var rx = (window.innerWidth-rect.x)-(45/2+8);
				dom_dropdown.style.right = rx.toString()+"px";

				/* reset all ---------- */

				document.querySelector('#ico1 i').style.color = "rgba(0, 0, 0, 0.5)";
				document.querySelector('#ico1 .hghlgtr').style.background = "gray";
				document.querySelector(".freq_dropdown").style.display = "none";

				document.querySelector('#ico2 i').style.color = "rgba(0, 0, 0, 0.5)";
				document.querySelector('#ico2 .hghlgtr').style.background = "gray";
				document.querySelector(".noti_dropdown").style.display = "none";

				document.querySelector('#ico3 i').style.color = "rgba(0, 0, 0, 0.5)";
				document.querySelector('#ico3 .hghlgtr').style.background = "gray";
				document.querySelector(".usrstngs_dropdown").style.display = "none";

				 /* -------- */

				var c_cdata = parseInt(dom.getAttribute('cdata'));

				if(c_cdata%2==1) {
					dom_ico.style.color = "var(--hover-color)";
					dom_hg.style.background = "var(--hover-color)";
					document.querySelector('.nav_main').style.borderBottom = "2px solid var(--hover-color)";
					var n_cdata = c_cdata+1;
					dom.setAttribute('cdata', n_cdata.toString());
					dom_dropdown.style.display = "block";
					dom.removeAttribute('onmouseout');
					if(i=='ico1') {
						load_freq();
					}
					else if(i=='ico2'){
						load_noti();
						document.querySelector('.noti_dropdown .mcont .bottom .noti_main').innerHTML = "";
					}
				}
				else {
					dom_ico.style.color = "rgba(0, 0, 0, 0.5)";
					dom_hg.style.background = "gray";
					document.querySelector('.nav_main').style.borderBottom = "1px solid rgba(0, 0, 0, 0.20)";
					var n_cdata = c_cdata+1;
					dom.setAttribute('cdata', n_cdata.toString());
					dom_dropdown.style.display = "none";	
					var fun= "remove_hover_ico('"+i+"');";
					dom.setAttribute('onmouseout', fun);
				}

			}
		// settings js // 
		function signup_ifocus(i) {
			var dom = document.querySelector('.'+i);
			var hgh = document.querySelector('.'+i+' .highlighter');
			hgh.style.background = "var(--hover-color)";
		}
		function remove_signup_ifocus(i) {
			var dom = document.querySelector('.'+i);
			var hgh = document.querySelector('.'+i+' .highlighter');
			hgh.style.background = "rgba(0, 0, 0, 0.60)";
		}

		function dp_change() {
			var dom = document.querySelector('#dpchange').files[0];
			if(dom.size<=6291456) {
				document.querySelector('.main_spinner').style.display = "flex";
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							document.querySelector('.main_spinner').style.display = "none";
							if(resp=='nai_er') {	
								alertmain('The File is not an Image.')
							}	
							else if(resp=='isset_er'){
								alertmain('Server Error! Please Try again later.')
							}
							else {
								document.querySelector('.main_settings .left .dp .img img').setAttribute('src', 'http://content.<?php echo $server; ?>/img_users/'+resp);
							}
						}, 800);
					}
				}
				var formdata = new FormData();
				formdata.append("file", dom);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/temp/dpchange.temp.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}
			else {
				alertmain('The File is too Big. (Max-6mB)');
			}
		}

		function animate_settings_toggle(n) {
			var angle = $('.toggle_settings ul .angle');
			var nn = n;
			n = parseInt(n);

			if(n==4) {
				loadfriends();
			}

			var value;
			if(n==1) {
				value = 15;
			}
			else if(value==2){
				value = 55;
			}
			else {
				value = ((n-1)*40)+15;
			}
			
			angle.animate({top: value}, 180, "swing");
			document.querySelector('.toggle_settings ul li[id="selected"]').setAttribute('id', '');
			
			document.querySelector('.toggle_settings ul li[name="'+n+'"]').setAttribute('id', 'selected');

			document.querySelector('.main_settings .right #selected').setAttribute('id', '');
			document.querySelector('.main_settings .right .right_'+n).setAttribute('id', 'selected');

		}
		function togglepass(n, c) {
			if(n=='show') {
				if(c=='current') {
					document.querySelector('.current .fa-eye').style.display = "none";
					document.querySelector('.current .fa-eye-slash').style.display = "block";
					document.querySelector('.current input').setAttribute('type', 'text');
				}
				else {
					document.querySelector('.new .fa-eye').style.display = "none";
					document.querySelector('.new .fa-eye-slash').style.display = "block";
					document.querySelector('.new input').setAttribute('type', 'text');
				}
			}
			else {
				if(c=='current') {
					document.querySelector('.current .fa-eye').style.display = "block";
					document.querySelector('.current .fa-eye-slash').style.display = "none";
					document.querySelector('.current input').setAttribute('type', 'password');
				}
				else {
					document.querySelector('.new .fa-eye').style.display = "block";
					document.querySelector('.new .fa-eye-slash').style.display = "none";
					document.querySelector('.new input').setAttribute('type', 'password');
				}
			}
		}

		function usrncheck() {

			document.querySelector('.right_1 .spinner').style.display = "none";
			document.querySelector('.right_1 #usrnavl').style.display = "none";
			document.querySelector('.right_1 #usrnnavl').style.display = "none";
			var val = document.querySelector('.right_1 .username input').value;
			if(val=='<?php echo $_SESSION['username']; ?>') {
				document.querySelector('.right_1 .username input').setAttribute('toupdate', '0');
			}
			else {

				document.querySelector('.right_1 #usrnavl').style.display = "none";
				document.querySelector('.right_1 .spinner').style.display = "none";
				document.querySelector('.right_1 .username input').setAttribute('toupdate', '0');
				document.querySelector('.right_1 #usrnnavl').style.display = "none";

				if(val.length>=7 && val.length<=18) {
					
					document.querySelector('.right_1 .spinner').style.display = "block";
					var xml = new XMLHttpRequest();
					xml.onreadystatechange = function() {
						if(this.readyState==4 && this.status==200) {
							var resp = this.responseText;
							if(resp=='1') {
								document.querySelector('.right_1 .spinner').style.display = "none";
								document.querySelector('.right_1 #usrnnavl').style.display = "none";
								document.querySelector('.right_1 .username input').setAttribute('toupdate', '1');
								document.querySelector('.right_1 #usrnavl').style.display = "block";
							}
							else if(resp=='0') {
								document.querySelector('.right_1 #usrnavl').style.display = "none";
								document.querySelector('.right_1 .spinner').style.display = "none";
								document.querySelector('.right_1 .username input').setAttribute('toupdate', '0');
								document.querySelector('.right_1 #usrnnavl').style.display = "block";
							}
							else {
								document.querySelector('.right_1 #usrnavl').style.display = "none";
								document.querySelector('.right_1 .spinner').style.display = "none";
								document.querySelector('.right_1 #usrnnavl').style.display = "block";
								document.querySelector('.right_1 .username input').setAttribute('toupdate', '0');
								alertmain(resp);
								console.log(resp);
							}
						}
					}
					xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/usrncheck.php");
					xml.withCredentials = true;
					var formdata = new FormData();
					formdata.append("value", val);
					xml.send(formdata);
				}
				else {
					document.querySelector('.right_1 .username input').setAttribute('toupdate', '0');
				}

			}


		}

		function agechk(date, month, year) {
			var obj = new Date();

			var curr_date = obj.getDate();
			var curr_month = obj.getMonth()+1;
			var curr_year = obj.getFullYear();

			if((curr_year-year)>10) {
				return 1;
			}
			else if((curr_year-year)==10 && (curr_date-date)>=0 && (curr_month-month)>=0){
				return 1;
			}
			else {
				return 0;
			}

		}

		function updategeneral() {
			document.querySelector(".main_spinner").style.display = "flex";
			var username = document.querySelector('.right_1 .username input').value;
			var bio = document.querySelector('.right_1 .bio textarea').value;
			var day = document.querySelector('.right_1 .birthday select[name=day]').value.toString();
			var month = document.querySelector('.right_1 .birthday select[name=month]').value.toString();
			var year = document.querySelector('.right_1 .birthday select[name=year]').value.toString();
			var birthday = day+"/"+month+"/"+year;

			if(agechk(day, month, year)) {

				var toupdateusername = document.querySelector('.right_1 .username input').getAttribute('toupdate');

				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							document.querySelector(".main_spinner").style.display = "none";
							alertmain("Account Updated!");
						}, 300);
					}
				}
				var formdata = new FormData();
				formdata.append("username", username);
				formdata.append("bio", bio);
				formdata.append("birthday", birthday);
				formdata.append("toupdateusername", toupdateusername);
				formdata.append("which", "general");
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/update_user.php");
				xml.withCredentials = true;
				xml.send(formdata);

			}
			else{
				document.querySelector(".main_spinner").style.display = "none";
				alertmain('Your age should be atleast 10 years.');
			}

		}

		function updatesocial() {
			var facebook = document.querySelector('.right_2 .facebook input').value;
			var instagram = document.querySelector('.right_2 .instagram input').value;
			var twitter = document.querySelector('.right_2 .twitter input').value;
			document.querySelector(".main_spinner").style.display = "flex";

			var xml=new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					if(resp=='1') {
						setTimeout(function(){
							document.querySelector(".main_spinner").style.display = "none";
							alertmain("Your social media accounts are Updated!");						
						}, 500);
					}
					else {
						console.log(resp);
					}
				}
			}
			var formdata = new FormData();
			formdata.append('facebook', facebook);
			formdata.append('instagram', instagram);
			formdata.append('twitter', twitter);
			formdata.append('which', 'social')
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/update_user.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}

		function password_btn_toggle() {
			var passlen = document.querySelector('.right_3 .new input').value.length;
			if(passlen>=8) {
				document.querySelector('.right_3 .change_pass_btn').removeAttribute('disabled');
				document.querySelector('.right_3 .change_pass_btn').style.cursor = 'pointer';
			}
			else {
				document.querySelector('.right_3 .change_pass_btn').setAttribute('disabled', 'true');
				document.querySelector('.right_3 .change_pass_btn').style.cursor = 'auto';	
			}
		}

		function updatepassword() {
			var current_pass = document.querySelector(".right_3 .current input").value;
			var new_pass = document.querySelector(".right_3 .new input").value;
			document.querySelector('.main_spinner').style.display = "flex";

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function(){
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					setTimeout(function(){
						document.querySelector('.main_spinner').style.display = "none";
						if(resp=='1') {
							alertmain("Password Changed!");
						}						
						else if(resp=='no'){
							alertmain("Current Password is wrong!");
						}
						else {
							alertmain("Server Error! Please try again later.");
						}
					}, 650);
				}
			}	
			var formdata = new FormData();
			formdata.append('current', current_pass);
			formdata.append('new', new_pass);
			formdata.append('which', 'security');
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/update_user.php");
			xml.withCredentials = true;
			xml.send(formdata);	

		}

		function loadfriends(offset, withload) {

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					try {
						var json = JSON.parse(resp);
						document.querySelector('.right_4 .top h1 span').innerHTML = "("+json.count+")";
						setTimeout(function(){
							if(withload==undefined) {
								document.querySelector('.right_4 .bottom .main').innerHTML = json.data;
							}
							else {
								document.querySelector('.right_4 .bottom .main').innerHTML += json.data;
							}
							document.querySelector('.right_4 .spinner_main').style.display = "none";
						}, 250);
					}
					catch(err) {
						document.querySelector('.right_4 .top h1 span').innerHTML = "(0)";
						document.querySelector('.right_4 .bottom .main').innerHTML = resp;
					}
				}
			}
			var formdata = new FormData();
			if(offset==undefined) {
				formdata.append('offset', 0);
			}
			else {
				formdata.append('offset', offset);
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/getfriendfromstorage.php");
			xml.withCredentials = true;
			xml.send(formdata);

		}

		function unfriend(key, id) {
			var spinner = document.querySelector('#id'+id+' .spinner');
			var icon = document.querySelector('#id'+id+' i');
			spinner.style.display = "block";
			icon.style.display = "none";

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					
					if(resp==1) {
						setTimeout(function(){
							document.querySelector('#id'+id+' .unfriend_confirm').style.display = "block";
							spinner.style.display = "none";
						}, 500);
					}
					else {
						spinner.style.display = "none";
						alertmain('An error Occured!');
						console.log(resp);
					}

				}
			}
			var formdata = new FormData();
			formdata.append('key', key);
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/unfriend.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}

		// settings js ends //

		// Friend Requests //
		

		function accept_request(key, id) {
			document.querySelector('#overlay_'+id).style.display = "flex";
			var xml = new XMLHttpRequest();
			xml.onreadystatechange= function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					if(resp==1) {
					setTimeout(function(){
						document.querySelector("#freq-items_"+id+" #accept_request").innerHTML = 'Accepted &#10004;';
						document.querySelector("#freq-items_"+id+" #accept_request").style.width = "70px";
						document.querySelector("#freq-items_"+id+" #accept_request").setAttribute("disabled", "true");
						document.querySelector("#freq-items_"+id+" #accept_request").style.cursor = "auto";
						document.querySelector("#freq-items_"+id+" #decline_request").setAttribute("disabled", "true");
						document.querySelector("#freq-items_"+id+" #decline_request").style.cursor = "auto";
						document.querySelector("#freq-items_"+id+" #accept_request").removeAttribute('onclick');
						document.querySelector("#freq-items_"+id+" #decline_request").removeAttribute('onclick');
						document.querySelector('#overlay_'+id).style.display = "none";
					}, 650);

					var xml_req = new XMLHttpRequest();
					xml_req.onreadystatechange = function() {
						if(this.readyState==4 && this.status==200) {
						}
					}
					var formdata = new FormData();
					var content = "<?php echo $_SESSION['username']; ?>"+" accepted your friend request.";
					formdata.append("key", key);
					formdata.append("which", "sendnoti");
					formdata.append("content", content);
					xml_req.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/notifications.php");
					xml_req.withCredentials = true;
					xml_req.send(formdata);
					
				}
				else {
					alertmain('An error Occured!');
					console.log(resp);
				}

				}

			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/freq.php");
			xml.withCredentials = true;
			var formdata = new FormData();
			formdata.append("which", "accept");
			formdata.append("key", key);
			xml.send(formdata);
		}

		function decline_request(key, id) {
			document.querySelector('#overlay_'+id).style.display = "flex";
			var xml = new XMLHttpRequest();
			xml.onreadystatechange= function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;

					if(resp==1) {
						setTimeout(function(){
							document.querySelector("#freq-items_"+id+" #decline_request").innerHTML = 'Declined &#10004;';
							document.querySelector("#freq-items_"+id+" #decline_request").style.width = "70px";
							document.querySelector("#freq-items_"+id+" #decline_request").setAttribute("disabled", "true");
							document.querySelector("#freq-items_"+id+" #decline_request").style.cursor = "auto";
							document.querySelector("#freq-items_"+id+" #accept_request").setAttribute("disabled", "true");
							document.querySelector("#freq-items_"+id+" #accept_request").style.cursor = "auto";
							document.querySelector("#freq-items_"+id+" #accept_request").removeAttribute('onclick');
							document.querySelector("#freq-items_"+id+" #decline_request").removeAttribute('onclick');
							document.querySelector('#overlay_'+id).style.display = "none";
						}, 650);
					}
					else {
						alertmain('An Error Occured!');
						console.log(resp);
					}

				}
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/freq.php");
			xml.withCredentials = true;
			var formdata = new FormData();
			formdata.append("which", "decline");
			formdata.append("key", key);
			formdata.append("id", id);
			xml.send(formdata);
		}

		// Friend Requests ends //


		// Create Page //

		function upload_temp_page_dp() {
			var dom = document.querySelector('.bottom_maincreatepage form .dps input').files[0];
			if(dom.size<=6291456) {
				document.querySelector('.bottom_maincreatepage .dps .spinner').style.display = "block";
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							document.querySelector('.bottom_maincreatepage .dps .spinner').style.display = "none";
							if(resp=='nai_er') {	
								alertmain('The File is not an Image.')
							}	
							else if(resp=='isset_er'){
								alertmain('Server Error! Please Try again later.')
							}
							else {
								document.querySelector('.bottom_maincreatepage form .dps input').setAttribute('src', resp);
								document.querySelector('.bottom_maincreatepage form .dps .upload img').setAttribute('src', 'http://content.<?php echo $server; ?>/temp_uploads/'+resp);
								document.querySelector('.bottom_maincreatepage form .dps .upload img').style.display = "block";
								document.querySelector('.bottom_maincreatepage form .dps .upload').setAttribute('class', 'upload upload-after');
								document.querySelector('.bottom_maincreatepage form .dps .upload').setAttribute('upload-after-text', 'Click to Change');
							}
						}, 800);
					}
				}
				var formdata = new FormData();
				formdata.append("file", dom);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/temp/imageupload.temp.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}
			else {
				alertmain('The File is too Big. (Max-6mB)');
			}
		}		

		function createpage() {
			var name = document.querySelector('.bottom_maincreatepage form .page_name input').value;
			var email = document.querySelector('.bottom_maincreatepage form .page_email input').value;
			var facebook = document.querySelector('.bottom_maincreatepage form .facebook input').value;
			var instagram = document.querySelector('.bottom_maincreatepage form .instagram input').value;
			var twitter = document.querySelector('.bottom_maincreatepage form .twitter input').value;
			var pic = document.querySelector('.bottom_maincreatepage form .dps input').getAttribute('src');
			var about = document.querySelector('.bottom_maincreatepage form .page_about textarea').value;

			document.querySelector('.main_spinner').style.display = "flex";

			if(about=='') {
				about = '-';
			}

			if(pic=='') {
				pic = '-';
			}

			if(facebook=='') {
				facebook = '-';
			}

			if(instagram=='') {
				instagram = '-';
			}

			if(twitter=='') {
				twitter = '-';
			}

			if(name=='' || email=='') {
				alertmain("Please fill the name and email entries.");
			}else {
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;

						setTimeout(function(){
							document.querySelector('.main_spinner').style.display = "none";
							if(resp=='email_err') {
								alertmain("Please enter a real email.");
							}
							else if(resp=='err') {
								alertmain("Please try again later.");
							}
							else if(resp=='nemail_err') {
								alertmain("Page with same email already exists.");
							}
							else if(resp=='char_err') {
								alertmain("Page Name should not contain spaces or special letters.");
							}
							else if(resp=='len_err') {
								alertmain("Pagename length should be greator than 4 and less than 19.");
							}
							else if(resp=='1'){
								alertmain("Your Page has been created.");
							}
							else {
								console.log(resp);
								alertmain("Server Error! Please Try again later.");
							}
						}, 700);						
					}
				}
				var formdata = new FormData();
				formdata.append('name', name);
				formdata.append('email', email);
				formdata.append('facebook', facebook);
				formdata.append('instagram', instagram);
				formdata.append('twitter', twitter);
				formdata.append('pic', pic);
				formdata.append('about', about);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/createpage.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}

			return false;

		}

		// Create Page ends //


		// My Pages //

		function open_mypageedit(which, dp, name, post_count, followers, about, social, email) {
			document.querySelector('.settings_mypages_overlay').style.display = "block";
			document.querySelector('.settings_mypages_overlay').setAttribute('which', which);
			document.querySelector('.settings_mypages_overlay .main .top span h2 span').innerHTML = "("+decodeURI(name)+")";
			document.querySelector('.settings_mypages_overlay .main .bottom .name input').setAttribute('value', decodeURI(name));
			document.querySelector('.settings_mypages_overlay .main .bottom .about textarea').innerHTML = decodeURIComponent(about);
			document.querySelector('.settings_mypages_overlay .main .bottom .email input').setAttribute('value', decodeURIComponent(email));

			social = JSON.parse(decodeURIComponent(social));
			var fb = social.facebook;
			var instagram = social.instagram;
			var twitter = social.twitter;

			document.querySelector('.settings_mypages_overlay .main .bottom .social .facebook input').setAttribute('value', fb);
			document.querySelector('.settings_mypages_overlay .main .bottom .social .instagram input').setAttribute('value', instagram);
			document.querySelector('.settings_mypages_overlay .main .bottom .social .twitter input').setAttribute('value', twitter);

			if(dp=='-') {
				document.querySelector('.settings_mypages_overlay .main .bottom .dp .img img').setAttribute('src', 'http://content.<?php echo $server; ?>/img_pages/yellow.jpg');
			}
			else {
				document.querySelector('.settings_mypages_overlay .main .bottom .dp .img img').setAttribute('src', 'http://content.<?php echo $server; ?>/img_pages/'+dp);
			}

		}

		function updatepageinfo(which) {
			document.querySelector('.main_spinner').style.display = "flex";
			var name = document.querySelector('.settings_mypages_overlay .main .bottom .name input').value;
			var about = document.querySelector('.settings_mypages_overlay .main .bottom .about textarea').value;
			var photo = document.querySelector('.settings_mypages_overlay .main .bottom .dp .img img').getAttribute('src');
			var email = document.querySelector('.settings_mypages_overlay .main .bottom .email input').value;

			var fb = document.querySelector('.settings_mypages_overlay .main .bottom .social .facebook input').value;
			var twitter = document.querySelector('.settings_mypages_overlay .main .bottom .social .twitter input').value;
			var instagram = document.querySelector('.settings_mypages_overlay .main .bottom .social .instagram input').value;

			if(fb=='') {
				fb = '-';
			}

			if(twitter=='') {
				twitter == '-';
			}

			if(instagram=='') {
				instagram = '-';
			}

			if(photo.includes("yellow.jpg")) {
				photo = "-";
			}

			var social = {facebook: fb, twitter: twitter, instagram: instagram};
			social = JSON.stringify(social);

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					document.querySelector('.main_spinner').style.display = "none";
					if(resp=='1') {
						alertmain("Done!");
						setTimeout(function(){
							document.querySelector('.settings_mypages_overlay').style.display = 'none';
						}, 1000);
					}
					else {
						alertmain(resp);
					}
				}
			}
			var formdata = new FormData();
			formdata.append("name", name);
			formdata.append("about", about);
			formdata.append("photo", photo);
			formdata.append("email", email);
			formdata.append("social", social);
			formdata.append("pid", which);
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/updatemypageinfo.php");
			xml.withCredentials = true;
			xml.send(formdata);
		
		}

		function dp_change_page() {
			var dom = document.querySelector('#dpchangepage').files[0];
			if(dom.size<=6291456) {
				document.querySelector('.main_spinner').style.display = "flex";
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							document.querySelector('.main_spinner').style.display = "none";
							if(resp=='nai_er') {	
								alertmain('The File is not an Image.')
							}	
							else if(resp=='isset_er'){
								alertmain('Server Error! Please Try again later.')
							}
							else {
								document.querySelector('.settings_mypages_overlay .main .bottom .dp .img img').setAttribute('src', 'http://content.<?php echo $server; ?>/temp_uploads/'+resp);
							}
						}, 800);
					}
				}
				var formdata = new FormData();
				formdata.append("file", dom);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/temp/dpchangepage.temp.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}
			else {
				alertmain('The File is too Big. (Max-6mB)');
			}
		}

		function load_mypages() {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					if(resp=='0') {
						setTimeout(function(){
							document.querySelector(".main_mypages .bottom .spinner_").style.display = "none"
							document.querySelector(".main_mypages .bottom .items").innerHTML = "<span style='position: absolute;left: 50%;color: gray;text-align: center;top: 10px;transform: translate(-50%, 0%)'>You do not have any page.</span>";
						}, 300);
					}
					else {
						setTimeout(function(){
							document.querySelector(".main_mypages .bottom .spinner_").style.display = "none"
							document.querySelector(".main_mypages .bottom .items").innerHTML = resp;
						}, 300);
					}
				}
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/mypages_load.php");
			xml.withCredentials=true;
			xml.send();
		}

		function open_uploadpost_overlay(which, name, pc, pagedp) {
			document.querySelector('.upload_post_overlay').style.display = "block";
			document.querySelector('.upload_post_overlay').setAttribute('which', which);
			document.querySelector('.upload_post_overlay').setAttribute('name', name);
			document.querySelector('.upload_post_overlay').setAttribute('pc', pc);
			document.querySelector('.upload_post_overlay').setAttribute('pagedp', pagedp);
		}

		function uploadmemeimage() {
			var dom = document.querySelector('#memeuploadinput').files[0];
			if(dom.size<=6291456) {
				document.querySelector('.uploadimage .iconcontainer .spinner').style.display = "block";
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							document.querySelector('.uploadimage .iconcontainer .spinner').style.display = "none";
							if(resp=='nai_er') {	
								alertmain('The File is not an Image.');
								document.querySelector('.upload_post_overlay .main .top button').setAttribute('disabled', 'true');
								document.querySelector('.upload_post_overlay .main .top button').style.cursor = "auto";
							}	
							else if(resp=='isset_er'){
								alertmain('Server Error! Please Try again later.');
								document.querySelector('.upload_post_overlay .main .top button').setAttribute('disabled', 'true');
								document.querySelector('.upload_post_overlay .main .top button').style.cursor = "auto";
							}
							else {
								$('.uploadimage').animate({top: "10px"}, "fast", "linear");

								document.querySelector('.uploadimage .iconcontainer i').setAttribute('class', 'far fa-exchange');
								document.querySelector('.uploadimage #infoupload').innerHTML = "Click to Change";
								document.querySelector('.imagecontainer').style.display = "block";
								document.querySelector('.imagecontainer img').setAttribute('src', "http://content.<?php echo $server; ?>/temp_uploads/"+resp); 
								document.querySelector('.upload_post_overlay .main .top button').removeAttribute('disabled');
								document.querySelector('.upload_post_overlay .main .top button').style.cursor = "pointer";
							}
						}, 800);
					}
				}
				var formdata = new FormData();
				formdata.append("file", dom);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/temp/memeimageupload.temp.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}
			else {
				alertmain('The File is too Big. (Max-6mB)');
			}	
		}

		function addtagstoggle() {
			document.querySelector('.upload_post_overlay .bottom .right .addtagsinfo').style.display = "none";
			document.querySelector('.upload_post_overlay .bottom .right .addtags_container').style.display = "block";
			document.querySelector('.upload_post_overlay .bottom .right .addtags').style.display = "block";
		}
 
		function uploadmeme() {
			//.upload_post_overlay > which
			//.imagecontainer img > src
			var tags = [];
			var tagslen = $('.addtags input[type=checkbox]:checked').length;
			var i;
			if(tagslen>0) {
				var stags = $('.addtags input[type=checkbox]:checked');
				var value;
				for(i=0;i<=tagslen-1;i++) {
					value = stags[i].value;
					tags.push(value);
				}
			}
			else {
				tags = [];
			}

			var img = document.querySelector('.imagecontainer img').getAttribute('src');
			var which = document.querySelector('.upload_post_overlay').getAttribute('which');
			var caption = document.querySelector('.upload_post_overlay .right .caption textarea').value;
			var pagename = document.querySelector('.upload_post_overlay').getAttribute('name');
			var pc = document.querySelector('.upload_post_overlay').getAttribute('pc');

			var pagedp = document.querySelector('.upload_post_overlay').getAttribute('pagedp');

			document.querySelector('.upload_post_overlay .main .top .spinner').style.display = "block";

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					setTimeout(function(){
						document.querySelector('.upload_post_overlay .main .top .spinner').style.display = "none";

						if(resp==1) {
							alertmain("Upload Complete!");
						}
						else {
							alertmain("Server Error! Please Try again Later.");
							console.log(resp);
						}

						document.querySelector('.overlay').innerHTML = "";
						document.querySelector('.overlay').style.display = "none";
						document.querySelector('.uploadimage .iconcontainer i').setAttribute('class', 'far fa-arrow-alt-up');
						document.querySelector('.uploadimage #infoupload').innerHTML = "(Only JPEG, PNG or JPG)";
						document.querySelector('.imagecontainer').style.display = "none";
						document.querySelector('.upload_post_overlay .main .top button').setAttribute('disabled', 'true');
						document.querySelector('.upload_post_overlay .main .top button').style.cursor = "auto";
						document.querySelector('.imagecontainer img').setAttribute('src', "");


						document.querySelector('.upload_post_overlay').style.display = "none";
						document.querySelector('.upload_post_overlay').removeAttribute('which');
						document.querySelector('.upload_post_overlay').removeAttribute('name');
						document.querySelector('.upload_post_overlay').removeAttribute('pc');
						document.querySelector('.upload_post_overlay').removeAttribute('pagedp');


					}, 500);
				}
			}
			var formdata = new FormData();
			formdata.append('which', which);
			formdata.append('img', img);
			formdata.append('tags', JSON.stringify(tags));
			formdata.append('pagename', pagename);
			formdata.append('pc', pc);
			formdata.append('pagedp', pagedp);
			if(caption) {
				formdata.append('caption', caption);
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/uploadmeme.php");
			xml.withCredentials = true;
			xml.send(formdata);

		}

		// ----------------------------------------------- //


		/* main */

		function download_post(pgname, imagelink) {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==404) {
					var xhr = new XMLHttpRequest();
					xhr.onreadystatechange = function() {
						if(this.readyState==4 && this.status==200) {
							document.querySelector('.downloadmeme').setAttribute('href', 'http://content.<?php echo $server; ?>/labelled_images/');
							document.querySelector('.downloadmeme').setAttribute('download', imagelink);
							/*document.querySelector('.downloadmeme').click();*/
						}
					}
					xhr.open("GET", "http://localhost:5000/labelimage?pname="+pgname+"&imlink="+imagelink);
					xhr.send();
				}
				else if(this.readyState==4 && this.status==200){
					document.querySelector('.downloadmeme').setAttribute('href', 'http://content.<?php echo $server; ?>/labelled_images/');
					document.querySelector('.downloadmeme').setAttribute('download', imagelink);
					/*document.querySelector('.downloadmeme').click();*/
				}
			}
			xml.open("GET", "http://content.<?php echo $server; ?>/labelled_images/"+imagelink);
			xml.send();
	
		}

		function slidedrop_post(dom, id) {
			var data = parseInt(dom.getAttribute('dataclick'));

			if(data%2==0) {
				document.querySelector('#f'+id+' .top .extraicon i').style.color = 'var(--hover-color)';
				document.querySelector('#f'+id+' .top .extraicon i').style.fontWeight = 'bolder';
				document.querySelector('#f'+id+' .top .extraicon i').style.fontSize = '18px';
				document.querySelector('#f'+id+' .top').style.borderBottom = "2px solid var(--hover-color)";
				document.querySelector('#f'+id+' .top .pdp').style.border = "2px solid var(--hover-color)";
				$('#drop_'+id).slideDown(200, 'swing');
				dom.setAttribute('dataclick', '1');
			}
			else {
				document.querySelector('#f'+id+' .top .extraicon i').style.color = '#222';
				document.querySelector('#f'+id+' .top .extraicon i').style.fontWeight = 'bold';
				document.querySelector('#f'+id+' .top .extraicon i').style.fontSize = '16px';
				document.querySelector('#f'+id+' .top').style.borderBottom = "1px solid var(--main-border)";
				document.querySelector('#f'+id+' .top .pdp').style.border = "1px solid var(--main-border)";
				$('#drop_'+id).slideUp("fast");
				dom.setAttribute('dataclick', '0');	
			}

		}

		function unfollowpage_thrposts(which, id) {
			document.querySelector('#f'+id+' .top .extraicon i').style.color = '#222';
			document.querySelector('#f'+id+' .top .extraicon i').style.fontWeight = 'bold';
			document.querySelector('#f'+id+' .top .extraicon i').style.fontSize = '16px';
			document.querySelector('#f'+id+' .top').style.borderBottom = "1px solid var(--main-border)";
			document.querySelector('#f'+id+' .top .pdp').style.border = "1px solid var(--main-border)";
			$('#drop_'+id).slideUp("fast");
			document.querySelector('#f'+id+" .top .extraicon i").setAttribute('dataclick', '0');	

			var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							if(resp==1) {
								alertmain("Unfollowed!");
							}	
							else if(resp==0){
								alertmain('An error occured.');
							}
							else {
								alertmain(resp);
							}
						}, 40);
					}
				}
				var formdata = new FormData();
				formdata.append("pid", which);
				formdata.append('action', 'unfollow');
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/followpage.php");
				xml.withCredentials = true;
				xml.send(formdata);	

		}

		function followpage_thrposts(which, id) {
			document.querySelector('#f'+id+' .top .extraicon i').style.color = '#222';
			document.querySelector('#f'+id+' .top .extraicon i').style.fontWeight = 'bold';
			document.querySelector('#f'+id+' .top .extraicon i').style.fontSize = '16px';
			document.querySelector('#f'+id+' .top').style.borderBottom = "1px solid var(--main-border)";
			document.querySelector('#f'+id+' .top .pdp').style.border = "1px solid var(--main-border)";
			$('#drop_'+id).slideUp("fast");
			document.querySelector('#f'+id+" .top .extraicon i").setAttribute('dataclick', '0');	

			var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.readyState==4 && this.status==200) {
						var resp = this.responseText;
						setTimeout(function(){
							if(resp==1) {
								alertmain("Followed!");
							}	
							else if(resp==0){
								alertmain('An error occured.');
							}
							else {
								alertmain(resp);
							}
						}, 40);
					}
				}
				var formdata = new FormData();
				formdata.append("pid", which);
				formdata.append('action', 'follow');
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/followpage.php");
				xml.withCredentials = true;
				xml.send(formdata);	

		}

		function reacttopost(which, mid, domid) {
			if(which=='positive') {
				document.querySelector('#f'+domid+' .post_actions .left i').setAttribute('id', 'animatepositive');
				document.querySelector('#f'+domid+' .post_actions .left span').style.fontWeight = "bold";
				document.querySelector('#f'+domid+' .post_actions .left span').style.color = "var(--hover-color)";
				document.querySelector('#f'+domid+' .post_actions .left span').style.fontSize = "13px";	

			}
			else {

			}

			if(which=='positive') {
				var count = parseInt(document.querySelector('#f'+domid+' .post_actions #like_count').getAttribute('data-react'));
				count += 1;
				document.querySelector('#f'+domid+' .post_actions #like_count').setAttribute('data-react', count.toString());
				document.querySelector('#f'+domid+' .post_actions #like_count').innerHTML = "("+count.toString()+")";
				
				document.querySelector('#f'+domid+' .post_actions .left').removeAttribute('onclick');
				document.querySelector('#f'+domid+' .post_actions .left').style.cursor = "auto";

				document.querySelector('#f'+domid+' .post_actions .right').removeAttribute('onclick');
				document.querySelector('#f'+domid+' .post_actions .right').style.cursor = "auto";
			}
			else {
				var count = parseInt(document.querySelector('#f'+domid+' .post_actions #dislike_count').getAttribute('data-react'));
				count += 1;
				document.querySelector('#f'+domid+' .post_actions #dislike_count').setAttribute('data-react', count.toString());
				document.querySelector('#f'+domid+' .post_actions #dislike_count').innerHTML = "("+count.toString()+")";

				document.querySelector('#f'+domid+' .post_actions .left').removeAttribute('onclick');
				document.querySelector('#f'+domid+' .post_actions .left').style.cursor = "auto";

				document.querySelector('#f'+domid+' .post_actions .right').removeAttribute('onclick');
				document.querySelector('#f'+domid+' .post_actions .right').style.cursor = "auto";

			}

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					if(resp=='1') {

					}
					else {
						console.log(resp);
						alertmain("Couldn't react to the post");
					}
				}
			}
			var formdata = new FormData();
			formdata.append("mid", mid);
			formdata.append("which", which);
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/reacttopost.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}
 
		/* ------------------------------------------ */

		/* CHAT */

		function loadlazychat() {
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState == 4 && this.status==200) {
					var resp = this.responseText;
					try {
						resp = JSON.parse(resp);
						var count = resp['count'];
						var data = resp['data'];

						try {
							document.querySelector('.onnfrnds').innerHTML = data;
							if(document.querySelector('.onlinefrnds #line').getAttribute('data-value').includes('On')) {

								document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Online Friends ('+count+')');
								document.querySelector('.onlinefrnds #line').setAttribute('f_count', count);
							}
							else {

							}
						}
						catch(err) {

						}	
					}
					catch(err) {
						document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Online Friends (0)');
						document.querySelector('.onnfrnds').innerHTML = resp;
					}

					document.querySelector("#offonchatspin").style.display = "none";							
				}
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/onlinefrnds.php");
			xml.withCredentials = true;
			xml.send();
		}

		function chatanimation() {
				document.querySelector('.onlinefrnds #line').setAttribute("class", "anim");

				setTimeout(function() {
					document.querySelector('.onlinefrnds #line').setAttribute("class", "");
				}, 900);		

				var changedata = parseInt(document.querySelector('.onlinefrnds #line').getAttribute('data-change'));

				if(changedata%2==1) {
					document.querySelector('.onnfrnds').style.display = "none";
					document.querySelector('#offonchatspin').style.display = "block";
					setTimeout(function() {
						document.querySelector('#offonchatspin').style.display = "none";
						document.querySelector('.chathistory').style.display = "block";	
						document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Chat History');
					}, 1250);

					clearInterval(loadchatinterval);

					loadchathistory(0, 12, null);
					document.querySelector('.chatwindow').setAttribute('data-which', 'history');

					try {
						document.querySelector('.right_more_ i').removeAttribute('id');
					}
					catch(err) {

					}
				}	
				else {
					document.querySelector('.onnfrnds').style.display = "block";
					document.querySelector('.chathistory').style.display = "none";
					document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Online Friends (0)');
					var state = document.querySelector('.left_divider .top_heading').getAttribute('state');

					clearInterval(loadchathistoryinterval);

					if(state=='enabled') {

						loadchatinterval = setInterval(function(){
							loadlazychat();
						}, 2500);
						document.querySelector('.chatwindow').setAttribute('data-which', 'chat');
					}
					else {

					}
				}

				changedata += 1;

				document.querySelector('.onlinefrnds #line').setAttribute('data-change', changedata.toString());

			}

			function disablechat() {
				var data = document.querySelector('.left_more_').getAttribute('cdata');
				if(parseInt(data)%2==1) {
					clearInterval(loadchatinterval);
					document.querySelector('.onnfrnds').innerHTML = "";
					document.querySelector('#offonchatspin').style.display = "block";

					document.querySelector('.left_divider .top_heading').setAttribute('state', 'disabled');


					setTimeout(function() {
						document.querySelector('.center_more_').style.color = "#cc0000";
						document.querySelector('#offonchatspin').style.display = "none";
						document.querySelector('.onnfrnds').innerHTML = "";
						document.querySelector('.onnfrnds').innerHTML = "<span class='chat_off_warning'><br><h1>You are Offline!</h1><br><font style='color: #262626'>Click the power button</font> <br> <i class='fas fa-power-off fa-4x' style='position: relative;top:2px;color: #262626'></i> <br> <font style='position: relative;top: 4px;'>to enable Chat.</font></span>";
						document.querySelector('.onnfrnds').innerHTML = "<img src='img/panda_offline3.jpg' width='260' height='260'>";
						document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Online Friends (0)');
					}, 2001);
				}
				else {

					document.querySelector('#offonchatspin').style.display = "block";
					document.querySelector('.onnfrnds').innerHTML = "";

					document.querySelector('.left_divider .top_heading').setAttribute('state', 'enabled');


					setTimeout(function(){
						document.querySelector('.center_more_').style.color = "#00b300";
						loadchatinterval = setInterval(function(){loadlazychat();}, 2500);
					}, 1500);
				}
				var newdata = parseInt(data)+1;
				document.querySelector('.left_more_').setAttribute('cdata', newdata.toString());
			}

			function loadchathistory(offset, limit,total) {
				offset = parseInt(offset);
				limit = parseInt(limit);
				if(total==null) {

				}
				else {
					total = parseInt(total);
				}

				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.status==200 && this.readyState==4) {
						var resp = this.responseText;
						if(resp==0) {
							document.querySelector('.chathistory').innerHTML = "<center><span style='font-size:12px;color: gray;'>You haven't started a conversation with anyone yet.</span></center>";
						}
						else {
							document.querySelector('.chathistory').innerHTML = resp;
							loadchathistoryinterval = setInterval(function(){loadchathistorytchk(0, 12, null);}, 2500);
						}
					}
				}
				xml.open("GET", "http://content.<?php echo $server; ?>/bscripts/chat/loadchathistory.php?offset="+offset+"&limit="+limit+"&total="+total);
				xml.withCredentials = true;
				xml.send();
			}

			function loadchathistorytchk(offset, limit, total) {
				var xml = new XMLHttpRequest();
				var lastmess = encodeURIComponent(document.querySelector('.item_chat .details ul .last_message').innerHTML);
				xml.onreadystatechange = function() {
					if(this.status==200 && this.readyState==4) {
						var resp = this.responseText;
						if(resp==0) {
						}
						else {
							resp = JSON.parse(resp);
							var id = resp["uid"];
							document.querySelector(".item_chat[uid="+id+"]").remove();
							var prevdata = document.querySelector('.chathistory').innerHTML;
							document.querySelector('.chathistory').innerHTML = resp["html"]+prevdata;
						}
					}
				}
				xml.open("GET", "http://content.<?php echo $server; ?>/bscripts/chat/loadchathistory.php?offset="+offset+"&	limit="+limit+"&total="+total+"&lmess="+lastmess);
				xml.withCredentials = true;
				xml.send();	
			}

		function openchat(username, uid, pic, fullname, id) {

			var cid = document.querySelector('#'+id).getAttribute('data_cid');
			document.querySelector('.chatwindow .top .dp img').setAttribute('src', 'http://content.<?php echo $server; ?>/img_users/'+pic);
			document.querySelector('.chatwindow .top #chat_username').innerHTML = username;
			document.querySelector('.chatwindow').setAttribute('data-username', username);
			document.querySelector('.chatwindow').setAttribute('data-uid', uid);
			document.querySelector('.chatwindow').setAttribute('data-fullname', fullname);
			document.querySelector('.chatwindow').setAttribute('data-cid', cid);
			document.querySelector('.chatwindow').style.display = "block";

			document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Chat window');

			document.querySelector('.chatwindow .mainchat .maindata ul').innerHTML = "";

			loadchat(uid, '<?php echo $_SESSION['UID']; ?>', username);

			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					
				}
			}
			var formdata = new FormData();
			formdata.append('uidf', uid);
			formdata.append('uidm', '<?php echo $_SESSION['UID']; ?>');
			formdata.append('updatelmess', '1');
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/chat/change_last_message.php");
			xml.withCredentials = true;
			xml.send(formdata);

			/* get cid + online */
			getcidinterval = setInterval(function(){
				var xml = new XMLHttpRequest();
				var uid = document.querySelector('.chatwindow').getAttribute('data-uid');
				xml.onreadystatechange = function() {
					if(this.status==200 && this.readyState==4) {
						var resp = JSON.parse(this.responseText);

						var cid = resp['cid'];
						var online = resp['online'];

						document.querySelector('.chatwindow').setAttribute('data-cid', cid);

						if(online==1) {
							document.querySelector('.chatwindow .top .onlinestatus').style.display = "block";
						}
						else {
							document.querySelector('.chatwindow .top .onlinestatus').style.display = "none";
						}

					}
				}
				var formdata = new FormData();
				formdata.append('uid', uid);
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/chat/getcid.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}, 800);

			updatelmesinterval = setInterval(function(){
				var xml = new XMLHttpRequest();
				xml.onreadystatechange = function() {
					if(this.status==200 && this.readyState==4) {
					
					}
				}
				var formdata = new FormData();
				formdata.append('uidf', uid);
				formdata.append('uidm', '<?php echo $_SESSION['UID']; ?>');
				formdata.append('updatelmess', '1');
				xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/chat/change_last_message.php");
				xml.withCredentials = true;
				xml.send(formdata);
			}, 3500);

		}	

		function removechatwindow(which) {
			document.querySelector('.chatwindow').style.display = 'none';
			document.querySelector('.chatwindow').setAttribute('data-username', '');
			document.querySelector('.chatwindow').setAttribute('data-fullname', '');
			document.querySelector('.chatwindow').setAttribute('data-cid', '');
			document.querySelector('.chatwindow').setAttribute('data-uid', '');
			document.querySelector('.chatwindow').setAttribute('data-pic', '');
			
			clearInterval(getcidinterval);
			clearInterval(updatelmesinterval);

			if(which=='history') {
				loadchathistory(0, 12, null);
				document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Chat History');	
			}
			else {
				document.querySelector('.onlinefrnds #line').setAttribute('data-value', 'Online Friends ('+document.querySelector('.onlinefrnds #line').getAttribute('f_count')+')');
			}
		
		}	

		function loadchat(uid_f, uid_m, username, offset, limit, allmesscount, rand) {
			document.querySelector('.mainchat .spinner').style.display = "block";
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					document.querySelector('.mainchat .spinner').style.display = "none";
					
					var prevdata = document.querySelector('.mainchat .maindata ul').innerHTML;
					document.querySelector('.mainchat .maindata ul').innerHTML = prevdata+resp;
					try {
					document.querySelector('#'+rand).style.display = 'none';
					}
					catch(err) {

					}	
					document.querySelector('#chat_mess_input').focus();
					document.querySelector('#chat_mess_input').setAttribute('value', '');
				}
			}
			var formdata = new FormData();
			if(offset==undefined) {
				offset = 0;
			}

			if(limit==undefined) {
				limit = 15;
			}

			if(allmesscount==undefined) {
				allmesscount = 0;
			}

			formdata.append('offset', offset);
			formdata.append('limit', limit);
			formdata.append('username', username);
			formdata.append('allmesscount', allmesscount);

			formdata.append("uid_f", uid_f);
			formdata.append("uid_m", uid_m);
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/chat/loadchats.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}

		/* ------------------------------ */


		/* SEARCH ----------- */ 


		function searchbar_toggle(e) {
			if(window.innerWidth>924) {
				document.querySelector('.ico_search').style.display='none';
				document.querySelector('.search_bar').style.display='flex';
			}
			else {
				if(e=='open') {
					var dom = document.querySelector('.ico_search');
					var dom_dropdown = document.querySelector('.search_resp_dropdown');

					document.querySelector('.nav_main').style.borderBottom = "2px solid var(--hover-color)";

					dom_dropdown.style.display = "flex";
					var rect = dom.getBoundingClientRect();
					var rx = (window.innerWidth-rect.x)-(45/2+8);
					dom_dropdown.style.right = rx.toString()+"px";
					dom.setAttribute('onclick', 'searchbar_toggle("close")');
					dom_dropdown.style.width = "285px";
					dom_dropdown.style.height = "60px";
				}
				else {
					var dom = document.querySelector('.ico_search');
					var dom_dropdown = document.querySelector('.search_resp_dropdown');

					document.querySelector('.nav_main').style.borderBottom = "1px solid var(--main-border)";

					dom_dropdown.style.display = "none";
					dom.setAttribute('onclick', 'searchbar_toggle("open")');	
				}
			}
		}


		function focussearchbar() {
			document.querySelector('.search_bar .highlgtr').style.background = "var(--hover-color)"; 
			document.querySelector('.search_resp_dropdown .highlgtr').style.background = "var(--hover-color)"; 
		}

		function focussoutsearchbar() {
			document.querySelector('.search_bar .highlgtr').style.background = "gray";
			document.querySelector('.search_resp_dropdown .highlgtr').style.background = "gray"; 
		}

		function search(val, which, offset, withload) {
			try {
				document.querySelector(".overlay").style.display = "none";
				document.querySelector(".search_resp_dropdown").style.display = "none";
			}
			catch(err) {

			}
			var xml = new XMLHttpRequest();
			document.querySelector('.main_search .bottom .spinner').style.display = "block";
			document.querySelector('.main_search .bottom .content').innerHTML = "";
			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					document.querySelector('.search_overlay').style.display = "block";
					setTimeout(function(){
						document.querySelector('.main_search .bottom .spinner').style.display = "none";
						if(withload==undefined) {
							document.querySelector('.main_search .bottom .content').innerHTML = resp;
						}
						else {
							document.querySelector('.main_search .bottom .content').innerHTML += resp;
						}
					}, 450);
				}
			}
			var formdata = new FormData();
			formdata.append("query", val);
			formdata.append("which", which);
			if(offset==undefined) {
				formdata.append('offset', 0);
			}
			else{
				formdata.append('offset', offset);
			}
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/search.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}

		function entercapture(e, dom, value) {
				document.querySelector('.resp_search_input').value = value;
			if(value.length>0) {
				if(e.key=='Enter') {
					search(value, 'people');
					document.querySelector('.typetoggle option[value=people]').removeAttribute('selected');
					document.querySelector('.typetoggle #toggle option[value=people]').setAttribute("selected", "1");
				}	

			}

		}

		function send_freq(domid, uid) {
			
			var spinner = document.querySelector('#spb_'+domid+' .send_req .spinner');
			var icon = document.querySelector('#spb_'+domid+' .send_req i');
			spinner.style.display = "block";
			icon.style.display = "none";

			var xml = new XMLHttpRequest();

			xml.onreadystatechange = function() {
				if(this.status==200 && this.readyState==4) {
					var resp = this.responseText;
					setTimeout(function(){
						spinner.style.display = "none";
						if(resp==3) {
							alertmain("You cannot send friend request to yourself.");
							icon.style.display = "block";
						}
						else if(resp==2) {
							alertmain("You have already sent a friend request to this user.");
							icon.style.display = "block";
						}
						else if(resp==1) {
							document.querySelector('#spb_'+domid+' .send_req #friend_req_sent').style.display = "block";
						}
						else {
							console.log(resp);
						}

					},50);
				}
			}
			var formdata = new FormData();
			formdata.append("uid", uid);
			formdata.append("which", "sendreq");
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/load/freq.php");
			xml.withCredentials = true;
			xml.send(formdata);

		}

		function followpage(which, id) {
			document.querySelector('#spageb_'+id+' .followicon .main').style.display = "none";
			document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "block";
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					setTimeout(function(){
						if(resp==1) {
							document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "none";
							document.querySelector('#spageb_'+id+' .followicon .main').style.display = "flex";
							document.querySelector('#spageb_'+id+' .followicon .main i').style.color = "var(--hover-color)";
							document.querySelector('#spageb_'+id+' .followicon .main i').style.fontWeight = "bold";
							document.querySelector('#spageb_'+id+' .followicon .main i').setAttribute('onclick', 'unfollowpage('+'"'+which+'"'+', '+'"'+id+'"'+')');
							var count = parseInt(document.querySelector('#spageb_'+id+' .followicon .main .followcount').innerHTML)+1;
							document.querySelector('#spageb_'+id+' .followicon .main .followcount').innerHTML = count;
						}
						else if(resp==0){
							document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "none";
							document.querySelector('#spageb_'+id+' .followicon .main').style.display = "flex";
							alertmain("Server Error! Please try again later.");
						}
						else {
							alertmain(resp);
						}
					}, 40);
				}
			}
			var formdata = new FormData();
			formdata.append("pid", which);
			formdata.append('action', 'follow');
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/followpage.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}

		function unfollowpage(which, id) {
			document.querySelector('#spageb_'+id+' .followicon .main').style.display = "none";
			document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "block";
			var xml = new XMLHttpRequest();
			xml.onreadystatechange = function() {
				if(this.readyState==4 && this.status==200) {
					var resp = this.responseText;
					setTimeout(function(){
						if(resp==1) {
							document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "none";
							document.querySelector('#spageb_'+id+' .followicon .main').style.display = "flex";
							document.querySelector('#spageb_'+id+' .followicon .main i').style.color = "#333";
							var count = parseInt(document.querySelector('#spageb_'+id+' .followicon .main .followcount').innerHTML)-1;
							document.querySelector('#spageb_'+id+' .followicon .main .followcount').innerHTML = count;
							document.querySelector('#spageb_'+id+' .followicon .main i').setAttribute('onclick', 'followpage('+'"'+which+'"'+', '+'"'+id+'"'+')');
						}
						else if(resp==0) {
							document.querySelector('#spageb_'+id+' .followicon .spinner').style.display = "none";
							document.querySelector('#spageb_'+id+' .followicon .main').style.display = "flex";
							alertmain("Server Error! Please try again later.");
						}
						else {
							alertmain(resp);
						}
					}, 40);
				}
			}
			var formdata = new FormData();
			formdata.append("pid", which);
			formdata.append('action', 'unfollow');
			xml.open("POST", "http://content.<?php echo $server; ?>/bscripts/actions/followpage.php");
			xml.withCredentials = true;
			xml.send(formdata);
		}


		/* --------------- */ 
			
	</script>
	<!--
	<script defer src="http://localhost:3005/socket.io/socket.io.js"></script>
	<script defer src="http://content.<?php echo $server; ?>/bscripts/chat/client.js"></script>

	!-->

</body>
</html>