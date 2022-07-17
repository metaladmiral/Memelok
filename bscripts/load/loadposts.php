<?php 

session_start();
include '../actions/dbh.php';

class load extends db {

	public $shown;
	public $int;
	public $mypages;
	public $remaining;
	public $not;

	public function show($ret, $f_data, $s_data, $t_data) {
		$shown = json_decode($this->shown, true);
		$not = json_encode($this->not);
		$uni_spinner = uniqid('').rand(99, 9999999);

		$px = explode('x', $_POST['px']);
		$iwidth = (int)$px[0];

		$pagesfollowing = json_decode($_SESSION['pagesfollowingarr'], true);

		$f_data = json_encode($f_data);
		$s_data = json_encode($s_data);
		$t_data = json_encode($t_data);

		foreach ($ret as $key => $value) {
			$page_name = rawurldecode($ret[$key]['page_name']);
			$mid = $ret[$key]['mid'];
			$pid = $ret[$key]['pid'];
			$image_link = $ret[$key]['image_link'];
			$like_count = $ret[$key]['like_count'];
			$dislike_count = $ret[$key]['dislike_count'];
			$caption= $ret[$key]['caption'];
			$date = $ret[$key]['date'];
			$pagedp = $ret[$key]['pagedp'];
			array_push($shown, $mid);

			$rand = uniqid();

			?>
			
			<div class="feeds" id='f<?php echo $rand; ?>'>

			<div class="top">

			<div class="pdp"><img src="img_pages/<?php echo $pagedp; ?>" alt=""></div>

			<div class="pagename">
				<a href="memelok/page/<?php echo $page_name; ?>" target="_blank"><span onclick=''><?php echo $page_name; ?></span></a>
			</div>	

			<div class="extraicon"><i class="fas fa-ellipsis-h" style="cursor: pointer;font-size: 16px;color: #222;" dataclick='0' onclick="slidedrop_post(this, '<?php echo $rand; ?>');"></i></div>	

			<div class="extradropdown" id='drop_<?php echo $rand; ?>'>

			<i class="far fa-chevron-up" style="color: var(--hover-color);font-size: 18px;"></i>

			<div class="bottom">

				<ul>
					<?php if(in_array($pid, $pagesfollowing)) { ?>
						<li onclick="unfollowpage_thrposts('<?php echo $pid; ?>', '<?php echo $rand; ?>');">Unfollow this Page</li>
					<?php }else { ?>
						<li onclick="followpage_thrposts('<?php echo $pid; ?>', '<?php echo $rand; ?>');">Follow this Page</li>
					<?php } ?>
					<li onclick="download_post('<?php echo $page_name ?>', '<?php echo $image_link; ?>');">Download</li>
				</ul>

			</div>

			</div>

			</div>

			<div class="content">
				
				<?php if(($iwidth>=700 and $iwidth<=914) || ($iwidth>=1115)) { $image_link = "big_".$image_link; ?>		
				<img src="post_img/<?php echo $image_link; ?>" alt="Not Found" style="">
				<?php }else { $image_link = "small_".$image_link; ?>
				<img src="post_img/<?php echo $image_link; ?>" alt="Not Found" style="">
				<?php } ?>
	
			</div>

			<div class="post_actions">
				
				<div class="left" onclick="reacttopost('positive', '<?php echo $mid; ?>', '<?php echo $rand; ?>')">
					
					<i class="fal fa-laugh-beam" style="position: relative;top: -1px;"> </i> &nbsp; <span style="font-weight: bold;font-family: sans-serif;">Haha</span> &nbsp; <span style="color: gray;font-size: 11px;" id='like_count' data-react="<?php echo $like_count; ?>">(<?php echo $like_count; ?>)</span>

				</div>

				<div id="line"></div>

				<div class="right" onclick="reacttopost('negative', '<?php echo $mid; ?>', '<?php echo $rand; ?>')">
					
					<i class="fal fa-meh" style="font-size: 16px;position: relative;top: -1px;"></i> &nbsp; <span style="font-size: 12px;font-weight: bold;font-family: sans-serif;color: #333;">Nah</span> &nbsp; <span style="color: gray;font-size: 11px;" id='dislike_count' data-react="<?php echo $dislike_count; ?>">(<?php echo $dislike_count; ?>)</span>

				</div>

			</div>

		</div>

		<br>

		<?php

		}
		$shown = json_encode($shown);

		?>

		<center><div class="loadmore" id='loadmore_<?php echo $uni_spinner; ?>' data-fdata='<?php echo $f_data; ?>' data-sdata='<?php echo $s_data; ?>' data-tdata='<?php echo $t_data; ?>' data-shown='<?php echo $shown; ?>' data-not='<?php echo $not; ?>'><span style="font-size: 13px;color: gray;text-decoration: underline;cursor: pointer;" href="" onclick="loadlazyposts('#loadmore_<?php echo $uni_spinner; ?>');">Load more</span></div></center>

		<br>
		
		<?php

	}

	public function show_remaining() {}

	public function followingWposts($limit, $offset, $datebefore) {

		$mdb = "usr_".$_SESSION['UID'];

		$pagesfollowing = json_decode($_SESSION['pagesfollowingarr'], true);
		
		if(count($pagesfollowing)>0) {

			$pgsfllw = implode(" ", $pagesfollowing);

			$date_curr = date("Ymd");
			$date_arr=array();
			for($i=0;$i<=$datebefore;$i++) {
				$d = $date_curr-$i;
				array_push($date_arr, $d);
			}
			$dates = implode(' ', $date_arr);
			
			$shown = json_decode($this->shown);
			$shownget = "'".implode("', '", $shown)."'";

			$mypages = json_decode($this->mypages, true);
			$mypagesimp = "'".implode("', '", $mypages)."'";

			$query = db::postsconnect()->prepare("SELECT `meme`.page_name, `meme`.mid, `meme`.pid, `meme`.image_link, `meme`.like_count, `meme`.dislike_count, `meme`.caption, `meme`.`date` FROM posts.`meme` LEFT JOIN $mdb.`likes` ON `likes`.mid=`meme`.`mid` WHERE `likes`.mid IS NULL AND `meme`.date > DATE_SUB(NOW(), INTERVAL $datebefore DAY) AND MATCH(`meme`.pid) AGAINST('".$pgsfllw."') AND `meme`.mid NOT IN(".$shownget.") LIMIT $offset, $limit");
			$query->execute();

			if($query->rowCount()>=1) {
				$retarr = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					array_push($retarr, $row);
				}
				return $retarr;
			}
			else {
				return 0;
			}

		}
		else {
			return 0;
		}

	}

	public function intWposts($limit, $offset, $datebefore) {
		$mdb = "usr_".$_SESSION['UID'];

		if($this->int!='random') {
			$int = json_decode($this->int);
			$interest_sql = implode(" ", $int);
		}
		else {
			$interest_sql = "random";
		}
		
		$shown = json_decode($this->shown);
		$shownget = "'".implode("', '", $shown)."'";

		$mypages = json_decode($this->mypages, true);
		$mypagesimp = "'".implode("', '", $mypages)."'";

		if($interest_sql!='random') { 

			$query = db::postsconnect()->prepare("SELECT `meme`.page_name, `meme`.mid, `meme`.pid, `meme`.image_link, `meme`.like_count, `meme`.dislike_count, `meme`.caption, `meme`.`date` FROM posts.`meme` LEFT JOIN $mdb.`likes` ON `likes`.mid=`meme`.`mid` WHERE `likes`.mid IS NULL AND MATCH(`meme`.date) AGAINST('".$dates."') AND MATCH(`meme`.tags) AGAINST('".$interest_sql."') AND `meme`.pid NOT IN(".$mypagesimp.") AND `meme`.mid NOT IN(".$shownget.") LIMIT $offset, $limit");
			$query->execute();

			if($query->rowCount()>=1) {
				return $query->fetch(PDO::FETCH_ASSOC);
			}
			else {
				return 0;
			}

		}
		else {

			$query = db::postsconnect()->prepare("SELECT `meme`.page_name, `meme`.mid, `meme`.pid, `meme`.image_link, `meme`.like_count, `meme`.dislike_count, `meme`.caption, `meme`.`date` FROM posts.`meme` LEFT JOIN $mdb.`likes` ON `likes`.mid=`meme`.`mid` WHERE `likes`.mid IS NULL AND `meme`.date > DATE_SUB(NOW(), INTERVAL $datebefore DAY) AND `meme`.pid NOT IN(".$mypagesimp.") AND `meme`.mid NOT IN(".$shownget.")");
			$query->execute();

			if($query->rowCount()>=1) {
				$retarr = array();
				while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
					array_push($retarr, $row);
				}
				return $retarr;
			}
			else {
				return 0;
			}			
		}

	}

	public function randWposts($limit, $offset, $datebefore) {

		$mdb = "usr_".$_SESSION['UID'];
		
		$shown = json_decode($this->shown);
		$shownget = "'".implode("', '", $shown)."'";

		$mypages = json_decode($this->mypages, true);
		$mypagesimp = "'".implode("', '", $mypages)."'";

		$query = db::postsconnect()->prepare("SELECT `meme`.page_name, `meme`.mid, `meme`.pid, `meme`.image_link, `meme`.like_count, `meme`.dislike_count, `meme`.caption, `meme`.`date`, `meme`.`pagedp` FROM posts.`meme` LEFT JOIN $mdb.`likes` ON `likes`.mid=`meme`.`mid` WHERE `likes`.mid IS NULL AND `meme`.pid NOT IN(".$mypagesimp.") AND `meme`.mid NOT IN(".$shownget.") LIMIT $offset, $limit");
		$query->execute();

		if($query->rowCount()>=1) {
			$retarr = array();
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				array_push($retarr, $row);
			}
			return $retarr;
		}
		else {
			return 0;
		}

	}

	public function exec() {
		
		$this->shown = $_POST['shown'];
		$this->int = $_SESSION['interests'];
		$this->mypages = $_SESSION['mypagesarr'];
		$this->not = json_decode($_POST['not'], true);
		
		$f_data = json_decode($_POST['f_data'], true);
		$s_data = json_decode($_POST['s_data'], true);
		$t_data = json_decode($_POST['t_data'], true);

		if(($f_data["do"]==0 AND $s_data["do"]==0) OR count($this->not)>1) {
			echo 0;
		}
		else {

			$rand= 3;
						
			if(in_array($rand, $this->not)) {

				$do_f = (int)$f_data["do"];
				$do_s = (int)$f_data["do"];

				if($do_f==1 && $do_s==1) {
					$rand = rand(1, 3);
				} 
				else if($do_f==0 && $do_s==1) {
					$rand = rand(2, 3);
				}
				else if($do_f==1 && $do_s==0) {
					$rand = rand(1, 3);
					if($rand==2) {
						$rand = 3;
					}
				}
				else {
					$rand = 3;
				}

			}

			if($rand==1) {
				$ret = self::followingWposts($f_data["limit"], $f_data["offset"], $f_data["datebefore"]);
				if($ret==0) {
					$f_data["do"] = 0;
					array_push($this->not, 1);
					$ret = self::randWposts($t_data["limit"], $t_data["offset"], $t_data["datebefore"]+20);				
					if($ret!=0) {
						$t_data["limit"] = $t_data["limit"]+8;
						$t_data["offset"] = $t_data["offset"]+8; 
						self::show($ret, $f_data, $s_data, $t_data);
					}
					else {
						echo 0;
					}
				}
				else {
					$f_data["limit"] = $f_data["limit"]+8;
					$f_data["offset"] = $f_data["offset"]+8; 
					self::show($ret, $f_data, $s_data, $t_data);
				}

			}
			else if($rand==2) {
				
				$ret = self::intWposts($s_data["limit"], $s_data["offset"], $s_data["datebefore"]);
				if($ret==0) {
					
					$s_data["do"] = 0;
					array_push($this->not, 2);
					$ret = self::randWposts($t_data["limit"], $t_data["offset"], $t_data["datebefore"]+20);
					if($ret!=0) {
						$t_data["limit"] = $t_data["limit"]+8;
						$t_data["offset"] = $t_data["offset"]+8; 
						self::show($ret, $f_data, $s_data, $t_data);
					}
					else {
						echo 0;
					}

				}
				else {
					$s_data["limit"] = $s_data["limit"]+8;
					$s_data["offset"] = $s_data["offset"]+8; 
					self::show($ret, $f_data, $s_data, $t_data);
				}
			}
			else if($rand==3){
				$ret = self::randWposts($t_data["limit"], $t_data["offset"], $t_data["datebefore"]);
				if($ret==0) {
					
					$ret = self::randWposts($t_data["limit"], $t_data["offset"], $t_data["datebefore"]+10);
					if($ret==0) {
						echo 0;
					}
					else {
						$t_data["limit"] = $t_data["limit"]+8;
						$t_data["offset"] = $t_data["offset"]+8;
						$t_data["datebefore"] = $t_data["datebefore"]+10; 
						self::show($ret, $f_data, $s_data, $t_data);
					}
					
				}
				else {
					$t_data["limit"] = $t_data["limit"]+8;
					$t_data["offset"] = $t_data["offset"]+8; 
					self::show($ret, $f_data, $s_data, $t_data);
				}
			}

		}
	}

}


$obj = new load;
$res = $obj->exec();
