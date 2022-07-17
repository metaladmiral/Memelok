<?php 

session_start();
include '../actions/dbh.php';

class load extends db {
	public function act() {

		$dbname = "usr_".$_SESSION['UID'];
		$query = db::mconnect($dbname)->prepare("SELECT * FROM `mypages` LIMIT 0, 7");
		$query->execute();

		if($query->rowCount()>0) {
			$pidarr = array();
			$retdata = array();
			$html = "";

			while($fetch = $query->fetch(PDO::FETCH_ASSOC)) {
				$pid=$fetch['PID'];
				$p_name=$fetch['p_name'];
				$p_dp=$fetch['p_dp'];
				array_push($pidarr, $pid);
				$retdata[$pid] = array($p_dp, $p_name);
			}
			
			for($i=0;$i<=count($pidarr)-1;$i++) {
				$pidn = $pidarr[$i];
				$query = db::pageconnect()->prepare("SELECT posts_count, followers, about, social, email FROM `info` WHERE pid='$pidn'");
				$query->execute();
				$fetch = $query->fetch(PDO::FETCH_ASSOC);
				$posts_count = $fetch['posts_count'];
				$followers = $fetch['followers'];
				array_push($retdata[$pidn], $posts_count);
				array_push($retdata[$pidn], $followers);
				array_push($retdata[$pidn], $fetch['about']);
				array_push($retdata[$pidn], $fetch['social']);
				array_push($retdata[$pidn], $fetch['email']);
			}
			

			foreach ($retdata as $key => $value) {
				$dp = $value[0];
				$name = $value[1];
				$posts_count = $value[2];
				$followers = $value[3];
				
				$dpS = "'".$dp."'";
				$nameS = "'".rawurlencode($name)."'";
				$posts_countS = "'".$posts_count."'";
				$followersS = "'".$followers."'";
				$aboutS = "'".$value[4]."'";
				$socialS = "'".rawurlencode($value[5])."'";
				$emailS = "'".$value[6]."'";

				if($dp=='-') {
					$dp = "../img_pages/yellow.jpg";
				}
				else {
					$dp = "../img_pages/".$dp;
				}

				$keyS = "'".$key."'";

				$rand = uniqid('pi');
				$randS = "'".$rand."'";

				$overlay_pgS = "'#".$rand." .overlay_pg"."'";
				$none = "'"."none"."'";
				$flex = "'"."flex"."'";

				$html .= '<div class="pageitem" id="'.$rand.'" data-attr="'.$key.'">

				<div class="left">

					<div class="dp"><a target="_blank" href="/page/'.$name.'"><img src="'.$dp.'" alt=""></a></div>
					<div class="pagename"><a target="_blank" href="/page/'.$name.'"><span>'.$name.'</span></a></div>

				</div>

				<div class="right">
					
					<div class="posts" onclick="open_uploadpost_overlay('.$keyS.', '.$nameS.', '.$posts_countS.', '.$dpS.');">

						<ul style="list-style: none;">
							<li><span style="font-weight: bold;font-size: 13px;color: gray;">Posts</span></li>
							<center><li><span style="font-size: 12px;color: #333;font-weight: bold;">'.$posts_count.'</span></li></center>
						</ul>

					</div>

					<div id="line" class="f"></div>

					<div class="follow_count">

						<ul style="list-style: none;">
							<li><span style="font-weight: bold;font-size: 13px;color: gray;">Followers</span></li>
							<center><li><span style="font-size: 12px;color: #333;font-weight: bold;">'.$followers.'</span></li></center>
						</ul>

					</div>

					<div id="line" class="s"></div>

					<div class="settings">		
						
						<i class="far fa-cog" style="cursor: pointer;" onclick="open_mypageedit('.$keyS.', '.$dpS.', '.$nameS.', '.$posts_countS.', '.$followersS.', '.$aboutS.', '.$socialS.', '.$emailS.');"></i>

					</div>

				</div>			
				
			</div>';

			}

			return $html;

		}
		else {
			return '0';
		}

	}
}

$obj = new load;
echo $obj->act();