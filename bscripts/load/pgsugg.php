<?php 

session_start();
include '../actions/dbh.php';

class load extends db {
	public function pgsugg() {

		$db = "usr_".$_SESSION['UID'];

		$friends_count = (int)$_SESSION['friends_count'];
		$pagesfollowing = json_decode($_SESSION['pagesfollowingarr'], true);
		$mypagesarr = json_decode($_SESSION['mypagesarr'], true);

		$pages = array_merge($pagesfollowing, $mypagesarr);
		$pages = "'".implode("', '", $pages)."'";

		if($friends_count>=3) {
			$query = db::pconnect()->prepare("SELECT uid from `users` NATURAL JOIN `$db`.friends WHERE `friends`.uid=`users`.uid AND `pages_following_count`>2 LIMIT 1");
			$query->execute();
			if($query->rowCount()>0) {
				$uid = $query->fetch(PDO::FETCH_ASSOC)['uid'];
				$dbname = "usr_".$uid;
				$query_get = db::mconnect($dbname)->prepare("SELECT pic, pid, page_name from pagesfollowing as pf NATURAL JOIN `pages`.`info` WHERE `info`.`pid`=`pf`.`pid` AND `pid` NOT IN(".$pages.") LIMIT 4");
				$query_get->execute();

				if($query_get->rowCount()>0)
				{	
					while($row = $query_get->fetch(PDO::FETCH_ASSOC)) {
						$pic = $row['pic'];
						$pid = $row['pid'];
						$page_name = $row['page_name'];

						?>
						
						<li>
							<a href="/page/<?php echo $page_name; ?>" style='text-decoration: none;' target="_blank">
							<div class="page_info">
								<img src="/img_pages/<?php echo $pic; ?>" alt="">
								<div class="page_name"><span><?php echo $page_name; ?></span></div><i class="fal fa-external-link"></i>
							</div>
							</a>
						</li>

						<?php

					}
				}
				else {
					return "<li><span style='color: gray;font-size: 12px;margin-left: 5px;margin-top: 5px;'>No Suggestions currently.</span></li>";	
				}
			}
			else {
				return "<li><span style='color: gray;font-size: 12px;margin-left: 5px;margin-top: 5px;'>No Suggestions currently.</span></li>";	
			}
		} 
		else {
			return "<li><span style='color: gray;font-size: 12px;margin-left: 5px;margin-top: 5px;'>No Suggestions currently.</span></li>";
		}
	}
}

$obj = new load;
echo $obj->pgsugg();