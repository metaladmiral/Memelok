<?php 

session_start();
include 'bscripts/actions/dbh.php';

class getpageposts extends db {
	public function act() {

		$server = "localhost/memelok";

		$limit = $_POST['limit'];
		$offset = $_POST['offset'];
		$total = $_POST['total'];

		$pid = $_POST['pid'];
		$pdb = "pg_".$_POST['pid'];

		if($total=='null') {
			$query = db::postsconnect()->prepare("SELECT COUNT(*) as c FROM posts.`meme` WHERE `pid`='$pid'");
			$query->execute();

			$fe_c = $query->fetch(PDO::FETCH_ASSOC);
			$total = $fe_c['c'];

		}

		$query = db::postsconnect()->prepare("SELECT date, image_link, like_count, caption FROM posts.`meme` WHERE `pid`='$pid' LIMIT $offset, $limit");
		$query->execute();		

		if($query->rowCount()>0) {
			
			while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
				
				$date = $row['date'];
				$image_link = $row['image_link'];
				$like_count = $row['like_count'];
				$caption = $row['caption'];
				$rand = uniqid('psts_');

				?>
	
				<div class="psts <?php echo $rand; ?>">
					
					<span class="time"><?php echo $date; ?></span>
					
					<?php if(!is_null($caption) AND $caption!='') { ?>
					<br>
					<span class="caption" style="font-size: 12px;"><?php echo $caption; ?></span>
					
					<?php } ?>

					<br>

					<img src="http://<?php echo $server;?>/post_img/<?php echo $image_link; ?>" alt="">	
					<br>
					<br>

					<span class="pos_reacts"><b style="color: green;"><?php echo $like_count; ?></b> <font style="font-size: 13px;">Positive reacts</font></span>
						
				</div>
		
				<br>
				<br>

				<?php
			
			}

			if($total-$limit>0) {
				?>
				
				<center>
					<span style="color: blue;padding-top:5px;padding-bottom: 5px;text-decoration: underline;cursor: pointer;font-size: 12px;" onclick="loadposts('<?php echo $offset+12; ?>', '<?php echo $limit+12; ?>', '<?php echo $total; ?>', this)">Load More</span>
				</center>

				<?php
			}

		}

		else {
			?>
			
			<center>
				<span style="color: gray;font-size: 12px;letter-spacing: 0.1px;">This page has'nt uploaded any memes yet.</span>
			</center>

			<?php
		}


	}
}

$obj = new getpageposts;
echo $obj->act();