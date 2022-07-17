<div class="back_arrow">

	<i class="fas fa-arrow-left" style="" onclick='document.querySelector(".overlay").style.display="none";'></i>

</div>

<div class="main_createpage">
	
	<div class="top">
		
		<div class="heading"><h2>CREATE A PAGE </h2> <div class="spinner loader"></div></div>

	</div>

	<div class="bottom_maincreatepage">
		
		<form action="" method="POST" enctype="multipart/form-data" onsubmit="return createpage();">
			
			<div class="dps">
				
				<span id='info'>Picture for Your Page</span>

				<input src="" type="file" id='fileupload' style="display: none;" onchange="upload_temp_page_dp();" accept="image/jpeg, image/jpg, image/png">

				<label for="fileupload">
					
					<div class="upload upload-after upload-before" upload-after-text='Add a Picture'><img src="" alt="" style="display: none;width: 100%;height: 100%;object-fit: cover;"></div>					

				</label>

				<div class="spinner loader"></div>

			</div>

			<br>

			<div class="page_name">
				
				<span id='info'>Name of the Page</span>

				<input type="text" class="input" placeholder="Type in a name" onfocus="signup_ifocus('page_name');document.querySelector('.main_createpage .top').style.borderBottom = '2px solid var(--hover-color)';" onfocusout="remove_signup_ifocus('page_name');document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'" required>

				<div class="highlighter"><i class="far fa-pager" style="color: white;"></i></div>

			</div>

			<br>

			<div class="page_email">
			
				<span id='info'>Email for the Page</span>

				<input type="email" class="input" placeholder="Type in an email" onfocus="signup_ifocus('page_email');document.querySelector('.main_createpage .top').style.borderBottom = '2px solid var(--hover-color)'" onfocusout="remove_signup_ifocus('page_email');document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'" required>

				<div class="highlighter"><i class="far fa-envelope" style="color: white;"></i></div>

			</div>

			<br>

			<div class="page_about">
				
				<span id='info'>About the Page</span>

				<textarea class="input" id="about" placeholder="About your page" maxlength="50" onfocus="document.querySelector('.main_createpage .page_about #info').style.color = 'var(--hover-color)';document.querySelector('.main_createpage .page_about #info').style.fontWeight = 'bold';document.querySelector('.main_createpage .top').style.borderBottom = '2px solid var(--hover-color)'" onfocusout="document.querySelector('.main_createpage .page_about #info').style.color = 'gray';document.querySelector('.main_createpage .page_about #info').style.fontWeight = 'normal';document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'"></textarea>	

			</div>

			<br>

			<div class="osm">
				
				<span id='info'>Other Social Media</span>

				<br>

				<div class="facebook">
					
					<input type="text" placeholder="Facebook" class="input" onfocus="signup_ifocus('osm .facebook');document.querySelector('.main_createpage .top').style.borderBottom = '2px solid var(--hover-color)'" onfocusout="remove_signup_ifocus('osm .facebook');document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'">

					<div class="highlighter"><i class="fab fa-facebook-f" style="color: white;"></i></div>

				</div>

				<div class="twitter">

					<input type="text" placeholder="Twitter" class="input" onfocus="signup_ifocus('osm .twitter');document.querySelector('.main_createpage .top').style.borderBottom = '2px solid var(--hover-color)'" onfocusout="remove_signup_ifocus('osm .twitter');document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'">

					<div class="highlighter"><i class="fab fa-twitter" style="color: white;"></i></div>
					
				</div>

				<div class="instagram">
					
					<input type="text" placeholder="Instagram" class="input" onfocus="signup_ifocus('osm .instagram');document.querySelector('.main_createpage .top').style.borderBottom = '3px solid var(--hover-color)'" onfocusout="remove_signup_ifocus('osm .instagram');document.querySelector('.main_createpage .top').style.borderBottom = '1px solid var(--main-border)'">

					<div class="highlighter"><i class="fab fa-instagram" style="color: white;"></i></div>

				</div>

			</div>

			<br>

			<button class="action_createapage" id='btn'>Create</button>

		</form>

	</div>

</div>





