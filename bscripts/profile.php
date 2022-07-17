<?php 

session_start();

include 'actions/dbh.php';

class profile extends db {
    public function app() {
        $uid = $_GET['id'];
        $query = db::pconnect()->prepare("SELECT fullname, username, pic, socialmedia, friend_count, bio, birthday FROM `users` WHERE `uid`='$uid'");
        $query->execute();

        if($query->rowCount()>0) {
            $data = $query->fetch(PDO::FETCH_ASSOC);
            return $data;
        }
        else {
            return 0;
        }

    }
}

$obj = new profile;
$ret = $obj->app();

if($ret==0) {
    header('No User was Found');
}
else {
    $data= $ret;
    $social=json_decode($data['socialmedia'], true);
    
    $birth = explode('/', $data['birthday']);

    $birth_day = $birth[0];
    $birth_month = date("F", mktime(0, 0, 0, $birth[1], 10)); 
    $birth_year = $birth[2];

    $birthday = $birth_day." ".$birth_month.", ".$birth_year;
}

?>

<div class="back_arrow">

	<i class="fas fa-arrow-left" style="" onclick='document.querySelector(".overlay").style.display="none";'></i>

</div>

<div class="main_profile">
    
    <div class="top">
        
        <div class="dp"><img src="img_users/<?php echo $data['pic']; ?>" alt=""></div>
    
        <div class="details">
            <div class="username">
                <h1 style="display: inline;"><?php echo $data['username']; ?></h1>&nbsp;<span style="color: gray;font-size: 12px;">(<?php echo $data['fullname']; ?>)</span>        
            </div>

            <div class="social">
                
                <?php 
                
                if($social['facebook']!='-') {
                    ?>
                    <div class="fb" style="cursor: pointer;"><i class="fab fa-facebook-f"></i></div>        
                    <?php
                } 
                else {
                    ?>
                    <div class="fb" style="cursor: auto;"><i class="fab fa-facebook-f"></i></div>        
                    <?php  
                }

                ?>

                <!-- --------------------------------- !-->

                <?php 
                
                if($social['twitter']!='-') {
                    ?>
                    <div class="twitter" style="cursor: pointer;"><i class="fab fa-twitter"></i></div>        
                    <?php
                } 
                else {
                    ?>
                    <div class="twitter" style="cursor: auto;"><i class="fab fa-twitter"></i></div>        
                    <?php  
                }

                ?>
                
                <!-- ----------------------------------- !-->

                <?php 
                
                if($social['instagram']!='-') {
                    ?>
                    <div class="insta" style="cursor: pointer;"><i class="fab fa-instagram"></i></div>        
                    <?php
                } 
                else {
                    ?>
                    <div class="insta" style="cursor: auto;"><i class="fab fa-instagram"></i></div>        
                    <?php  
                }

                ?>

            </div>      
        </div>

    </div>

    <div class="more">
        <div class="bio">
            <div class="biotxt">
                <h1>Bio</h1>
            </div>
            <div class="biomain">
                <?php echo $data['bio']; ?>
            </div>
        </div>
        <br>
        <div class="birthday"><i style="font-size: 18px;color: pink;" class="fad fa-birthday-cake"></i>&nbsp;&nbsp;<?php echo $birthday; ?></div>

        <br>

        <div class="frnd_count_text"><span>Friends Count</span></div>
        <div class="frnd_count_no"><span style="font-size: 13px;font-weight: bold;"><?php echo $data['friend_count']; ?></span></div>

    </div>

</div>

<!-- <div class="container">

    <div class="dp">

    </div>

    <div class="details">
        <div class="fname">

        </div>
        <div class="social fb"></div>
        <div class="social insta"></div>
        <div class="social twitter"></div>
    </div>

    <div class="more">
        <div class="biotxt">
            <span>BIO</span>
        </div>
        <div class="biocon"></div>
        <div class="birthday"></div>
        <div class="friendstxt">FRIENDS COUNT</div>
        <div class="friendscount"></div>
        <div class="sharedpoststxt">SHARED POSTS</div>
        <div class="sharedposts"></div>
    </div>
</div> -->