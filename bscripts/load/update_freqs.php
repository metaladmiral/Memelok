<?php 

session_start();
include '../actions/dbh.php';

class load extends db {
	public function freqs() {
		$dbname = "usr_".$_SESSION['UID'];
		$query = db::mconnect($dbname)->prepare("SELECT COUNT(*) as count FROM `friend_requests`");
		$query->execute();

		$f_req_count = $query->fetch(PDO::FETCH_ASSOC)['count'];

		return $f_req_count;

	}
}

$obj = new load;
echo $obj->freqs();