<?php 

session_start();
include 'dbh.php';

class upload extends db {
	public function act() {
		if(isset($_POST['caption'])) {
			$caption = stripslashes(strip_tags($_POST['caption']));
		}
		else {
			$caption = null;
		}

		$tags = json_decode($_POST['tags'], true);

		if(sizeof($tags)==0) {
			$tags = "random";
		}
		else {
			$tags = json_encode($tags);
		}

		$pid = $_POST['which'];
		$img = explode('/', $_POST['img'])[1];

		$img_big = "big_".$img;
		$img_small = "small_".$img;

		$pagename = $_POST['pagename'];
		$pagedp = $_POST['pagedp'];
		
		/* mid */

		$pidsplit = str_split($pid);
		$imgsplit = str_split($img);

		$prefix = $pidsplit[0].$pidsplit[1].$pidsplit[2].$pidsplit[3].$imgsplit[0].$imgsplit[1].$imgsplit[2].rand(0, 1000);
		$mid = uniqid($prefix, true);

		/* */ 
		
		$date = date("Y-m-d H:i:s", strtotime(date('h:i:sa')));;
		$like_count = 0;
		$likes = json_encode(array());
		$comments = json_encode(array());
		$dccount = 0;

		/* queries */
		$pgtable = "pg_".$pid;

		try {

			copy("../../temp_uploads/".$img, "../../post_img/".$img);	

			$ch = curl_init();

			curl_setopt($ch, CURLOPT_URL, "http://localhost:5000/optimizememe?imlink=$img&imbig=$img_big&imsmall=$img_small");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

			$output = curl_exec($ch);
			curl_close($ch);


			$query = db::mconnect($pgtable)->prepare("INSERT INTO `posts`(mid) VALUES(:mid)");
			$query->bindParam(':mid', $mid);
			$query->execute();

			$query = db::mconnect("posts")->prepare("INSERT INTO `meme`(`mid`, `date`, `page_name`, `pid`, `tags`, `image_link`, `like_count`, `dislike_count`, `caption`, `pagedp`) VALUES(:mid, :daten, :page_name, :pid, :tags, :image, :like_count, :dccount, :caption, :pagedp)");

			$query->bindParam(':mid', $mid);
			$query->bindParam(':daten', $date);
			$query->bindParam(':page_name', $pagename);
			$query->bindParam(':pid', $pid);
			$query->bindParam(':tags', $tags);
			$query->bindParam(':image', $img);
			$query->bindParam(':dccount', $dccount);
			$query->bindParam(':like_count', $like_count);
			$query->bindParam(':caption', $caption);
			$query->bindParam(':pagedp', $pagedp);

			$query->execute();

			$npost_count = $_POST['pc']+1;

			$query = db::pageconnect()->prepare("UPDATE `info` SET `posts_count`='".$npost_count."' WHERE `pid`='".$pid."'");
			$query->execute();

			return 1;

		}
		catch(\Exception $e) {
			return $e->getMessage();
		}

	}	
}

$obj = new upload;
echo $obj->act();