<?php

include 'actions/dbh.php';

$val = $_POST['value'];
$val = stripslashes(strip_tags($val));
$obj = new db;

if(preg_match("/[!@^%\$()=?;+#\/*\[\]\{\}<>|,' -\"]/", $val)) {
	echo '0';
}	
else {

try {
	$query = $obj->pconnect()->prepare("SELECT * FROM `users` WHERE `username`='$val'");
	$query->execute();

	if($query->rowCount()>0) {
		echo '0';
	}
	else {
		echo '1';
	}

}
catch(\Exception $e) {
	echo $e->getMessage();
}

}