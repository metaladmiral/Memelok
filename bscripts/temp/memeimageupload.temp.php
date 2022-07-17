<?php

include '../actions/dbh.php';

session_start();

if(isset($_FILES["file"])) {
	$file = $_FILES["file"];
	$type = mime_content_type($file["tmp_name"]);

	$db = new db;

	if($type=='image/jpeg' OR $type=='image/jpg' OR $type=='image/png') {
		
		$ext = explode('/', $type)[1];
		$prefix = strlen($file['name']).$file['size'].rand(11, 500);
		$random_name = uniqid($prefix, true).".".$ext;

		try {
			move_uploaded_file($file["tmp_name"], "C:/xampp/htdocs/memelok/temp_uploads/$random_name");

			echo $random_name;
		}
		catch(\Exception $e) {
			echo $e->getMessage();
		}

	}
	else {
		echo "nai_er";
	}

}
else {
	echo "isset_er";
}