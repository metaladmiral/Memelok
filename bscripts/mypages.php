<?php session_start(); ?>
<div class="back_arrow">

	<i class="fas fa-arrow-left" style="" onclick='document.querySelector(".overlay").style.display="none";'></i>

</div>

<div class="settings_mypages_overlay" which=''>

	<i class="far fa-times" style="color: white;cursor: pointer;font-size: 22px;position: relative;top: 28px;left: 30px;" onclick="document.querySelector('.settings_mypages_overlay').style.display = 'none';"></i>

	<div class="main">
		
		<div class="top">
			<span><h2>Edit <span class="pgname">(Prakhar's First Page)</span></h2> </span>
		</div>

		<div class="bottom">
			
			<div class="dp">
				<input type="file" accept="image/jpeg, image/jpg, image/png" style="display: none;" id='dpchangepage' onchange="dp_change_page();">
				<label for="dpchangepage">
					<div class="img">
						<img src="img_users/<?php echo $_SESSION['pic']; ?>" alt="">
						<div class="img_wrapper"></div>
					</div>
				</label>
			</div>

			<br>

			<div class="name">
				
				<label for="pgname" style="color: gray;font-size: 12px;">Name of the Page</label>
				<input type="text" id='pgname' value="Prakhar's First Page" class="input" spellcheck="false" autocomplete="false" onfocus="signup_ifocus('settings_mypages_overlay .name');" onfocusout="remove_signup_ifocus('settings_mypages_overlay .name');">
				<div class="highlighter" style="left: 5px;"><i class="far fa-user" style="color: white;"></i></div>

			</div>

			<div class="about">
				
				<label for="pgabout" style="color: gray;font-size: 12px;">About the Page</label>
				<textarea id="pgabout" class="input" spellcheck="false"></textarea>

			</div>

			<div class="social">
				
				<label style="font-size: 12px;color:gray;">Social Media</label>

				<div class="facebook">
					<label style="font-size: 11px;color: gray;">Facebook: </label>
					<input type="text" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('settings_mypages_overlay .facebook')" onfocusout="remove_signup_ifocus('settings_mypages_overlay .facebook')" value="">
					<div class="highlighter" style="left: 5px;"><i style="color: white;" class="fab fa-facebook-f"></i></div>	
				</div>

		
				<div class="instagram">
					<label style="font-size: 11px;color: gray;">Instagram: </label>
					<input type="text" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('settings_mypages_overlay .instagram')" onfocusout="remove_signup_ifocus('settings_mypages_overlay .instagram')" value="">
					<div class="highlighter" style="left: 5px;"><i style="color: white;" class="fab fa-instagram"></i></div>
				</div>
			

				<div class="twitter">
					<label style="font-size: 11px;color: gray;">Twitter: </label>
					<input type="text" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('settings_mypages_overlay .twitter')" onfocusout="remove_signup_ifocus('settings_mypages_overlay .twitter')" value="">
					<div class="highlighter" style="left: 5px;"><i style="color: white;" class="fab fa-twitter"></i></div>
				</div>

			</div>

			<div class="email">
				
				<label style="font-size: 11px;color: gray;">Email for the Page</label>
				<input type="text" class="input" autocomplete="false" spellcheck="false" onfocus="signup_ifocus('settings_mypages_overlay .email')" onfocusout="remove_signup_ifocus('settings_mypages_overlay .email')" value="">
					<div class="highlighter" style="left: 5px;"><i style="color: white;" class="far fa-envelope"></i></div>

			</div>

			<button id='btn' style="width: 70px;" onclick="updatepageinfo(document.querySelector('.settings_mypages_overlay').getAttribute('which'));">Done</button>

			<br>
			<br>

		</div>

	</div>
	
</div>

<div class="upload_post_overlay" which=''>
	
	<i class="far fa-times" style="color: white;font-size: 22px;cursor:pointer;position: relative;top: 28px;left: 30px;" onclick="document.querySelector('.upload_post_overlay').style.display = 'none';"></i>

	<div class="main">
		
		<div class="top"><span><h2>Post <span class="pgname_post"></span></h2> </span> <div class="spinner loader"></div> <button disabled="true" id='btn' onclick="uploadmeme();">Upload</button></div>

		<div class="bottom">
			
			<div class="left">

				<div class="uploadimage">
					
					<input type="file" style="display: none;" accept="image/jpeg, image/jpg, image/png" id="memeuploadinput" onchange="uploadmemeimage()">

					<label for="memeuploadinput">
						
						<div class="iconcontainer">
							
							<i class="far fa-arrow-alt-up" style="font-size: 35px;color: #bfbfbf;position: absolute;"></i>

							<div class="spinner loader" style="width: 10px;height: 10px;position: absolute;"></div>

						</div>

					</label>

					<center><span style="color: gray;font-size: 12px;position: relative;top: 5px;" id="infoupload">(Only JPEG, PNG or JPG)</span></center>

				</div>

				<div class="imagecontainer" style="background: #f2f2f2;">
					
					<img src="" style="width: 100%;height: 100%;position: absolute;object-fit: cover;" alt="">

				</div>

			</div>

			<div id="line"></div>

			<div class="right">

				<div class="caption">
					
					<label for="" style="font-size: 12px;color: gray;">Add a Caption (Optional)</label>
					<textarea spellcheck="none" class="caption_meme"></textarea>

				</div>

				<div class="addtagsinfo">
					
					<br>
				
					<h2 style="font-size: 19px;color: #333;">Add Tags</h2>
					<p style="color: gray;position: relative;top: 2px;">Next Step is to add tags. Tags can help your meme to reach more people. If no tags are given then your meme will only reach to your followers and there is very less probability that it reaches more people.</p>

					<a href="javascript:void(0)" onclick="addtagstoggle();return false;" style="font-size: 13px;position: relative;top: 3px;color: blue;"><span style="font-size: 17px;">&oplus;</span> Click to add tags</a>

				</div>

				<div class="addtags">
					
					<span class="topspan" style="font-size: 12px;color: gray;"><i class="fas fa-info-circle" style="position: relative;top:0.5px;"></i> Only select those tags which are related to your meme.</span>
					

					<div class="bottom">
						
						<?php

							$tags = array("School Life", "College Life", "Indian Parents", "Technology", "Sports", "Childhood", "Dailylife", "Bollywood", "Indian Politics", "Animals", "Engineers", "Doctors", "Indian Weddings", "10 Year Challenge", "Adult", "Cartoons", "Indian Babas", "Sarcastic", "Indian TV Ads", "Tik Tok", "Reactions", "Relationship", "Teenage Life", "Humourous", "Indian History", "Nature");

							$selectS = "'"."select"."'";

							for($i=0;$i<=sizeof($tags)-1;$i++) {
								
								echo '<label>
						
							<div id="tagcheckbox">
							
								<div class="checkbox_cont">
									
									<input type="checkbox" onchange="this.setAttribute('.$selectS.', '.$selectS.');" value="'.$tags[$i].'" select="">
									<div class="checkmark">
										
									</div>

								</div>

								<div class="label">
									<span style="color: gray;font-size: 13px;">'.$tags[$i].'&nbsp;&nbsp;</span>
								</div>

							</div>

						</label>';

							}

						?>

					</div>

					
				</div>

			</div>			
			
		</div>

	</div>

</div>

<div class="main_mypages">
	
	<div class="top">
		<div class="heading"><h2>My Pages</h2></div>
		<div class="spinner loader"></div>
	</div>

	<div class="bottom">
		
		<div class="spinner_ loader"></div>

		<div class="items" style="width: 100%;height: 100%;">

			

		</div>

	</div>

</div>
	
<div class="prob_page" style="position: absolute;bottom: 15px;left: 50%;transform: translate(-50%, 0%);width: 100%;height: auto;display: flex;justify-content: center;"><span style="font-size: 12px;">Having problems uploading your Meme? <a href="/how-to-create-a-page" style="display: static;font-size: 12px;" target="_blank">Click Here</a></span></div>
