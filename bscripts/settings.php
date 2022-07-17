<?php

session_start();

?>
<div class="back_arrow">

	<i class="fas fa-arrow-left" style="" onclick='document.querySelector(".overlay").style.display="none";document.querySelector(".overlay_responsive").style.display = "none";'></i>

</div>

<div class="main_settings">
	
	<div class="left">
	
		<div class="dp">
			<input type="file" accept="image/jpeg, image/jpg, image/png" style="display: none;" id='dpchange' onchange="dp_change();">
			<label for="dpchange">
				<div class="img">
					<img src="img_users/<?php echo $_SESSION['pic']; ?>" alt="">
					<div class="img_wrapper"></div>
				</div>
			</label>
		</div>		


		<div class="toggle_settings">
			
			<div class="top_label">
				<label>Settings</label>
				<i class="far fa-chevron-right"></i>
			</div>


			<ul>
				<div class="angle"><i class="far fa-chevron-left" style="font-size: 15px;color: var(--hover-color);"></i></div>
				
				<li id='selected' onclick="animate_settings_toggle('1');" name='1'><span>General</span></li>
				<li onclick="animate_settings_toggle('2');" name='2'><span>Social</span></li>
				<li onclick="animate_settings_toggle('3');" name='3'><span>Privacy and Security</span></li>
				<li onclick="animate_settings_toggle('4');" name='4'><span>My Friends</span></li>

			</ul>

		</div>

	</div>

	<div class="right">
		<div class="right_1 mm_settings_right" id='selected'>
			
			<div class="top">
				<h1>General</h1>
			</div>

			<div class="bottom">
		
				<br>

				<div class="bio">
					
					<label style="color: gray;font-size: 14px;">Bio: </label>
					<textarea onkeyup="document.querySelector('.editgeneral').removeAttribute('disabled');document.querySelector('.editgeneral').style.cursor = 'pointer';" name="" id="bio" maxlength="300" onfocus="document.querySelector('.bio label').style.color='var(--hover-color)';document.querySelector('.bio label').style.fontWeight='bold';" onfocusout="document.querySelector('.bio label').style.color='gray';document.querySelector('.bio label').style.boxShadow='normal';"><?php echo $_SESSION['bio']; ?></textarea>

				</div>
				
				<br>

				<div class="birthday">

					<label style="color: gray;font-size: 14px;">Birthday: </label>

					<?php

					$birthday = (string)$_SESSION['birthday'];
					$day = explode('/', $birthday)[0];
					$month = explode('/', $birthday)[1];
					$year = explode('/', $birthday)[2];

					?>

					<select name="day" onchange="document.querySelector('.main_settings .birthday select[name=day]').setAttribute('value', this.value);try{document.querySelector('.editgeneral').removeAttribute('disabled');}catch(err){}document.querySelector('.editgeneral').style.cursor = 'pointer';"><option value="<?php echo $day; ?>"><?php echo $day; ?></option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option><option value="6">6</option><option value="7">7</option><option value="8">8</option><option value="9">9</option><option value="10">10</option><option value="11">11</option><option value="12">12</option><option value="13">13</option><option value="14">14</option><option value="15">15</option><option value="16">16</option><option value="17">17</option><option value="18">18</option><option value="19">19</option><option value="20">20</option><option value="21">21</option><option value="22">22</option><option value="23">23</option><option value="24">24</option><option value="25">25</option><option value="26">26</option><option value="27">27</option><option value="28">28</option><option value="29">29</option><option value="30">30</option><option value="31">31</option></select>

					<select name="month" onchange="document.querySelector('.birthday select[name=month]').setAttribute('value', this.value);try{document.querySelector('.editgeneral').removeAttribute('disabled');}catch(err){}document.querySelector('.editgeneral').style.cursor = 'pointer';"><option value="<?php echo $month; ?>" selected="1"><?php echo $month; ?></option><option value="1">Jan</option><option value="2">Feb</option><option value="3">Mar</option><option value="4">Apr</option><option value="5">May</option><option value="6">Jun</option><option value="7">Jul</option><option value="8">Aug</option><option value="9">Sept</option><option value="10">Oct</option><option value="11">Nov</option><option value="12">Dec</option></select>

					<select name="year" onchange="document.querySelector('.birthday select[name=year]').setAttribute('value', this.value);try{document.querySelector('.editgeneral').removeAttribute('disabled');}catch(err){}document.querySelector('.editgeneral').style.cursor = 'pointer';"><option value="<?php echo $year; ?>"><?php echo $year; ?></option><option value="2019">2019</option><option value="2018">2018</option><option value="2017">2017</option><option value="2016">2016</option><option value="2015">2015</option><option value="2014">2014</option><option value="2013">2013</option><option value="2012">2012</option><option value="2011">2011</option><option value="2010">2010</option><option value="2009">2009</option><option value="2008">2008</option><option value="2007">2007</option><option value="2006">2006</option><option value="2005">2005</option><option value="2004">2004</option><option value="2003">2003</option><option value="2002">2002</option><option value="2001">2001</option><option value="2000">2000</option><option value="1999">1999</option><option value="1998">1998</option><option value="1997">1997</option><option value="1996">1996</option><option value="1995">1995</option><option value="1994">1994</option><option value="1993">1993</option><option value="1992">1992</option><option value="1991">1991</option><option value="1990">1990</option><option value="1989">1989</option><option value="1988">1988</option><option value="1987">1987</option><option value="1986">1986</option><option value="1985">1985</option><option value="1984">1984</option><option value="1983">1983</option><option value="1982">1982</option><option value="1981">1981</option><option value="1980">1980</option><option value="1979">1979</option><option value="1978">1978</option><option value="1977">1977</option><option value="1976">1976</option><option value="1975">1975</option><option value="1974">1974</option><option value="1973">1973</option><option value="1972">1972</option><option value="1971">1971</option><option value="1970">1970</option><option value="1969">1969</option><option value="1968">1968</option><option value="1967">1967</option><option value="1966">1966</option><option value="1965">1965</option><option value="1964">1964</option><option value="1963">1963</option><option value="1962">1962</option><option value="1961">1961</option><option value="1960">1960</option><option value="1959">1959</option><option value="1958">1958</option><option value="1957">1957</option><option value="1956">1956</option><option value="1955">1955</option><option value="1954">1954</option><option value="1953">1953</option><option value="1952">1952</option><option value="1951">1951</option><option value="1950">1950</option><option value="1949">1949</option><option value="1948">1948</option><option value="1947">1947</option><option value="1946">1946</option><option value="1945">1945</option><option value="1944">1944</option><option value="1943">1943</option><option value="1942">1942</option><option value="1941">1941</option><option value="1940">1940</option><option value="1939">1939</option><option value="1938">1938</option><option value="1937">1937</option><option value="1936">1936</option><option value="1935">1935</option><option value="1934">1934</option><option value="1933">1933</option><option value="1932">1932</option><option value="1931">1931</option><option value="1930">1930</option><option value="1929">1929</option><option value="1928">1928</option><option value="1927">1927</option><option value="1926">1926</option><option value="1925">1925</option><option value="1924">1924</option><option value="1923">1923</option><option value="1922">1922</option><option value="1921">1921</option><option value="1920">1920</option><option value="1919">1919</option><option value="1918">1918</option><option value="1917">1917</option><option value="1916">1916</option><option value="1915">1915</option><option value="1914">1914</option><option value="1913">1913</option><option value="1912">1912</option><option value="1911">1911</option><option value="1910">1910</option><option value="1909">1909</option><option value="1908">1908</option><option value="1907">1907</option><option value="1906">1906</option><option value="1905">1905</option></select>

				</div>

				<br>

				<div class="username">
					
					<label style="font-size: 14px;color: gray;">Username: </label>

					<input type="text" onkeyup="usrncheck();try{document.querySelector('.editgeneral').removeAttribute('disabled');}catch(err){}document.querySelector('.editgeneral').style.cursor = 'pointer';" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('username');document.querySelector('.main_settings .username label').style.color = 'var(--hover-color)';document.querySelector('.main_settings .username label').style.fontWeight = 'bold';" onfocusout="remove_signup_ifocus('username');document.querySelector('.main_settings .username label').style.color = 'gray';document.querySelector('.main_settings .username label').style.fontWeight = 'normal';" value="<?php echo $_SESSION['username']; ?>" toupdate='0'>

					<div class="highlighter"><i style="color: white;" class="far fa-user"></i></div>

					<div class="loader spinner"></div>

					<i class="far fa-check" id='usrnavl'></i>

					<i class="far fa-times" id='usrnnavl'></i>

				</div>

				<br>


				<button id='editbtn' disabled="true" class="editgeneral" style="cursor: auto;" onclick="updategeneral();">Save Settings</button>

				<br>
				<br>
				<br>

			</div>

		</div>
		
		<div class="right_2 mm_settings_right">
		
			<div class="top">	
				<h1>Social</h1>
			</div>

			<div class="bottom">

				<?php

				$socialmedia = json_decode($_SESSION['socialmedia'], true);

				?>

				<br>
				<br>

				<div class="facebook">
					<label style="font-size: 14px;color: gray;">Facebook: </label>
					<input type="text" onkeyup="document.querySelector('.right_2 #editbtn').removeAttribute('disabled');document.querySelector('.right_2 #editbtn').style.cursor = 'pointer';" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('facebook')" onfocusout="remove_signup_ifocus('facebook')" value="<?php echo $socialmedia["facebook"]; ?>">
					<div class="highlighter"><i style="color: white;" class="fab fa-facebook-f"></i></div>	
				</div>

				<br>

				<div class="instagram">
					<label style="font-size: 14px;color: gray;">Instagram: </label>
					<input type="text" onkeyup="document.querySelector('.right_2 #editbtn').removeAttribute('disabled');document.querySelector('.right_2 #editbtn').style.cursor = 'pointer';" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('instagram')" onfocusout="remove_signup_ifocus('instagram')" value="<?php echo $socialmedia["instagram"]; ?>">
					<div class="highlighter"><i style="color: white;" class="fab fa-instagram"></i></div>
				</div>
				
				<br>

				<div class="twitter">
					<label style="font-size: 14px;color: gray;">Twitter: </label>
					<input type="text" placeholder="" onkeyup="document.querySelector('.right_2 #editbtn').removeAttribute('disabled');document.querySelector('.right_2 #editbtn').style.cursor = 'pointer';"	 class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('twitter')" onfocusout="remove_signup_ifocus('twitter')" value="<?php echo $socialmedia["twitter"]; ?>">
					<div class="highlighter"><i style="color: white;" class="fab fa-twitter"></i></div>
				</div>
				
				<br>

				<button id='editbtn' disabled="true" style="cursor: auto;" onclick="updatesocial()">Save Settings</button>

			</div>

		</div>
		
		<div class="right_3 mm_settings_right">
			
			<div class="top">
			<h1>Privacy and Security</h1>
			</div>

			<div class="bottom">
		
				<br>

				<div class="change_pass">
					
					<label style="color: gray;font-size: 14px" id='main_heading'>Change Password: </label>
					
					<div class="current">
						<label style="color: gray;font-size: 13px;position: absolute;left: 0px;top:0px;">Current Password</label>		
						<input type="password" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('current')" onfocusout="remove_signup_ifocus('current')">
						<i class="far fa-eye" id='togglepass' style="color: gray;margin-top: 4px;" onclick="togglepass('show', 'current');"></i>
						<i class="far fa-eye-slash" id='togglepass' style="color: gray;margin-top: 4px;display: none;" onclick="togglepass('hide', 'current');"></i>
						<div class="highlighter"><i class="far fa-lock" style="color: white;"></i></div>
					</div>

					<div class="new">
						<label style="color: gray;font-size: 13px;position: absolute;left: 0px;top:0px;">New Password</label>		
						<input type="password" onkeyup="password_btn_toggle();" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('new')" onfocusout="remove_signup_ifocus('new')">
						<i class="far fa-eye" id='togglepass' style="color: gray;margin-top: 4px;" onclick="togglepass('show', 'new');"></i>
						<i class="far fa-eye-slash" id='togglepass' style="color: gray;margin-top: 4px;display: none;" onclick="togglepass('hide', 'new');"></i>
						<div class="highlighter"><i class="far fa-lock" style="color: white;"></i></div>
					</div>
				

				</div>

				<br>

				<button class="change_pass_btn" style="cursor: auto;" id='btn' onclick="updatepassword()" disabled="true">Update Password</button>
				
				<br>
				<br>

				<hr style="border: 1px solid var(--hover-color);">

				<br>

				<div class="change_email">

					<label style="color: gray;font-size: 14px;">Email: </label>

					<input type="email" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('change_email')" value="<?php echo $_SESSION['email']; ?>" onfocusout="remove_signup_ifocus('change_email')">

					<div class="highlighter"><i style="color: white;" class="far fa-lock"></i></div>

				</div>

			</div>

		</div>
		
		<div class="right_4 mm_settings_right">
			
			<div class="top">
				<h1>My Friends <span style="color: gray;font-size: 16px;position: relative;top: -2px;">(0)</span></h1>
			</div>

			<div class="bottom">

				<div class="main">
				
					<!-- html += '<div class="friends_item" id="id'+username+'"><div class="dp"><img src="/img_users/'+dp+'" alt=""></div><div class="details"><div class="username"><span>'+username+'</span></div><div class="fullname"><span>'+fullname+'</span></div></div><div class="action"><i class="fas fa-times" onclick="unfriend('+keys+', '+usernames+');" style="color: #333;font-size: 18px;cursor: pointer;"></i></div><div class="spinner loader"></div><button class="unfriend_confirm" id="btn">Unfriended &#10004;</button></div>';

					!-->					
					
				</div>
			
				<div class="spinner_main loader"></div>


			</div>

		</div>

		<div class="right_5 mm_settings_right">
			
			<div class="top">
				<h1>Info</h1>
			</div>

			<div class="bottom">
				
			</div>
		</div>
	</div>

</div>













<!--
<div class="main_settings">
	
	<div class="top">
		<div class="heading"><h2>SETTINGS</h2><div class="loader spinner"></div></div>
	</div>

	<div class="form">
		
		<div class="profilepicc">

			<input type="file" id='change_pic' style='display: none;'>
			
			<label for="change_pic">
				<div class="change_picd">

				</div>
			</label>

		</div>

		<br>

		<div class="usern_c">
			<input type="text" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('usern_c')" onfocusout="remove_signup_ifocus('usern_c')">

			<div class="highlighter"><i style="color: white;" class="far fa-user"></i></div>
		</div>

		<br>

		<div class="pass_c">
			<input type="password" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('pass_c')" onfocusout="remove_signup_ifocus('pass_c')">

			<div class="highlighter"><i style="color: white;" class="far fa-lock"></i></div>
		</div>

		<br>
	
		<div class="fullname_c">
			<input type="text" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('fullname_c')" onfocusout="remove_signup_ifocus('fullname_c')">

			<div class="highlighter"><i style="color: white;" class="far fa-address-book"></i></div>
		</div>

		<br>	

		<div class="bio_c">
			<label style="font-size: 14px;color: gray;position: relative;left: 5px;">Bio: </label>
			<br>
			<p><textarea name="" id="" maxlength="300" placeholder="Type your Bio.." spellcheck="false" autocomplete="false" onfocus="document.querySelector('.main_settings .top').style.borderBottom = '2px solid var(--hover-color)';document.querySelector('.bio_c label').style.color = 'var(--hover-color)';document.querySelector('.bio_c label').style.fontWeight = '600';scrollfbio();" onfocusout="document.querySelector('.main_settings .top').style.borderBottom = '1px solid var(--main-border)';document.querySelector('.bio_c label').style.color = 'gray';document.querySelector('.bio_c label').style.fontWeight = '400';"></textarea></p>
		</div>

		<br>

		<div class="social_c">
			
			<label style="font-size: 14px;color: gray;position: relative;left: 5px;">Social Media:</label>

			<br>

			<div class="facebook">
				<input type="text" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('facebook')" onfocusout="remove_signup_ifocus('facebook')">
				<div class="highlighter"><i style="color: white;" class="fab fa-facebook-f"></i></div>	
			</div>

			<br>

			<div class="insta">
				<input type="text" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('insta')" onfocusout="remove_signup_ifocus('insta')">
				<div class="highlighter"><i style="color: white;" class="fab fa-instagram"></i></div>
			</div>
			
			<br>

			<div class="twitter">
				<input type="text" placeholder="" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('twitter')" onfocusout="remove_signup_ifocus('twitter')">
				<div class="highlighter"><i style="color: white;" class="fab fa-twitter"></i></div>
			</div>

		</div>
		
		<br>

		<div class="edittopics">
			
		</div>
		
		<br>

		<input type="button" value="Done" disabled="true">

	</div>

</div>

!-->
