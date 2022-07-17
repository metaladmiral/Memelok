<?php 

include '../actions/dbh.php';
session_start();


class app extends db  {
	public function update() {
		$uid = $_SESSION['UID'];

		$state = $_POST['state'];

		if($state=='enabled') {
			$la = date("Y-m-d H:i:s", strtotime(date('h:i:sa')));
			$query = db::pconnect()->prepare("UPDATE `users` SET `last_activity`='$la' WHERE `uid`='$uid'");
			$query->execute();
		}
		else {

		}


		$dbn = "usr_".$uid;
		$query = db::mconnect($dbn)->prepare("SELECT uid FROM `friends`");
		$query->execute();
		
		$friends = array();
		while($row=$query->fetch(PDO::FETCH_ASSOC)) {
			array_push($friends, $row['uid']);
		}
		$friends = json_encode($friends);

		$query = db::mconnect($dbn)->prepare("SELECT PID as pid FROM `mypages`");
		$query->execute();

		$mypagearr = array();

		while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
			array_push($mypagearr, $row['pid']);
		}


		$query = db::mconnect($dbn)->prepare("SELECT pid as pid FROM `pagesfollowing`");
		$query->execute();
		
		$pagesfollowingarr = array();

		while ($row=$query->fetch(PDO::FETCH_ASSOC)) {
			array_push($pagesfollowingarr, $row['pid']);
		}		

		$pagesfollowingarr = json_encode($pagesfollowingarr);
		$mypagearr = json_encode($mypagearr);

		$query = db::mconnect($dbn)->prepare("SELECT * FROM `notifications`");
		$query->execute();
		$rows = $query->rowCount();

		$oldnoticount = $_SESSION['noti_count'];

		$_SESSION['noti_count']= $rows;
		$_SESSION['friends'] = $friends;
		$_SESSION['mypagesarr'] = $mypagearr;
		$_SESSION['pagesfollowing'] = $pagesfollowingarr;
		$_SESSION['pagesfollowingarr'] = $pagesfollowingarr;

		$array = array("oldnoticount"=>$oldnoticount, "noti_count"=>$rows);

		return json_encode($array);

		/*
		$array = array("noti_count"=>$rows, "friends"=>$friends, "mypagearr"=>$mypagearr, "pagesfollowing"=>$pagesfollowingarr);
		$json = json_encode($array);

		return $json;

		*/

	}
}

$app = new app;
echo $app->update();