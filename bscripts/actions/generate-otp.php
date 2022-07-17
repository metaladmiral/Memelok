<?php 

$email = $_POST['email'];

if(filter_var($email, FILTER_VALIDATE_EMAIL)) {


$rand_otp = rand(1239, 9842);

mail($email, "OTP for Registration on Memelok", "Your OTP is <b></b>. After Entering the OTP, click continue. It usually takes 10-15 seconds to get registered.");

echo $rand_otp;

}
else {
	echo 0;
}