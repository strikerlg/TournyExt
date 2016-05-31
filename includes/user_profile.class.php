<?php

class edit_user_profile {
    private $db;
    private $viewer;
    private $imgLoc = "images/profile/";
    private $path = HOMEPATH . "tournament/images/profile/";

    public $fname;
    public $lname;
    public $location;
    public $age;
    public $headerImgOld;
    public $profileImgOld;
    public $headerImg;
    public $profileImg;

    public $facebook;
    public $twitter;
    public $youtube;
    public $instagram;
    public $website;

    public function __construct(\database $db, $fname, $lname, $location, $age, $facebook, $twitter, $youtube, $instagram, $website, $profileImgOld, $headerImgOld, $profileImg, $headerImg, $viewer) {
        $this->db = $db;
        $this->viewer = $viewer;

        $this->fname = (!empty($fname) ? $fname : null);
        $this->lname = (!empty($lname) ? $lname : null);
        $this->location = (!empty($location) ? $location : null);
        $this->age = (!empty($age) ? $age : null);

        $this->headerImgOld = (!empty($headerImgOld) ? $headerImgOld : 'default_profile_header_img.jpg');
        $this->headerImg = (!empty($headerImg) ? $headerImg : '');

        $this->profileImgOld = (!empty($profileImgOld) ? $profileImgOld : 'default_profile_img.png');
        $this->profileImg= (!empty($profileImg) ? $profileImg : '');

        $this->facebook = (!empty($facebook) ? $facebook : null);
        $this->twitter = (!empty($twitter) ? $twitter : null);
        $this->youtube = (!empty($youtube) ? $youtube : null);
        $this->instagram = (!empty($instagram) ? $instagram : null);
        $this->website = (!empty($website) ? $website : null);
    }

    public function update_profile() {

        if($this->profileImg['name'] != "") {
            $extProfile = pathinfo($this->profileImg['name'])['extension'];
            $random1 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
            $profile = $random1 . ".$extProfile";
            if (file_exists($this->path . $this->profileImgOld) && !empty($this->profileImgOld) && $this->profileImgOld != "default_profile_img.png")
            {
                unlink($this->path . $this->profileImgOld);
            }
            move_uploaded_file($this->profileImg['tmp_name'], $this->path . $profile);
        }
        else {
            $profile = $this->profileImgOld;
        }

        if($this->headerImg['name'] != "") {
            $extHeader = pathinfo($this->headerImg['name'])['extension'];
            $random2 = substr(str_shuffle("abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ"), 0, 15);
            $header = $random2 . ".$extHeader";
            if (file_exists($this->path . $this->headerImgOld) && !empty($this->headerImgOld) && $this->headerImgOld != "default_profile_header_img.jpg")
            {
                unlink($this->path . $this->headerImgOld);
            }
            move_uploaded_file($this->headerImg['tmp_name'], $this->path . $header);
        }
        else {
            $header = $this->headerImgOld;
        }

        $update = $this->db->UPDATE("UPDATE `users_info` SET `fname`=?, `lname`=?, `location`=?, `age`=?, `headerImg`=?, `profileImg`=?, `facebook`=?, `twitter`=?, `youtube`=?, `instagram`=?, `website`=? WHERE `user_id` = ?"
                                    ,array('sssssssssssi',$this->fname, $this->lname, $this->location, $this->age, $header, $profile, $this->facebook, $this->twitter,
                                    $this->youtube, $this->instagram, $this->website, $this->viewer));
        


        if($update[0] == "Update Successful") {
            $success_msg .= '<div class="success_msg">' . $update[0] . '</div>';
            return $success_msg;
        }
        else {
            $success_msg .= '<div class="error_msg">' . $update[0] . '</div>';
            return $success_msg;
        }
    }
}






class user_profile {

    private $db;
    private $viewer;

    public $imgLoc = "images/profile/";
    public $username;
    public $fname;
    public $lname;
    public $location;
    public $age;
    public $headerImg;
    public $profileImg;

    public $facebook;
    public $twitter;
    public $youtube;
    public $instagram;
    public $website;

    public $empty_msg;

    public function __construct(\database $db, $pid, $viewer) {
        $this->db = $db;
        $this->username = $pid;
        $this->viewer = $viewer;

        $params = $this->db->SELECT("SELECT users_info.*, users.username, users.lastOnline FROM users_info INNER JOIN users ON users.id = users_info.user_id WHERE users.username = ?", array('s', $this->username));

        if(empty($params)) {
            $this->empty_msg = 'The user you are attempting to view does not exist.';
        }
        else {
            $this->fname = (!empty($params[0]['fname']) ? $params[0]['fname'] : null);
            $this->lname = (!empty($params[0]['lname']) ? $params[0]['lname'] : null);
            $this->location = (!empty($params[0]['location']) ? $params[0]['location'] : null);
            $this->age = (!empty($params[0]['age']) ? $params[0]['age'] : null);

            $this->headerImg = (!empty($params[0]['headerImg']) ? $params[0]['headerImg'] : 'default_profile_header_img.jpg');
            $this->profileImg = (!empty($params[0]['profileImg']) ? $params[0]['profileImg'] : 'default_profile_img.png');

            $this->facebook = (!empty($params[0]['facebook']) ? $params[0]['facebook'] : null);
            $this->twitter = (!empty($params[0]['twitter']) ? $params[0]['twitter'] : null);
            $this->youtube = (!empty($params[0]['youtube']) ? $params[0]['youtube'] : null);
            $this->instagram = (!empty($params[0]['instagram']) ? $params[0]['instagram'] : null);
            $this->website = (!empty($params[0]['website']) ? $params[0]['website'] : null);
        }
    }

    public function editProfile() {
        $string = '
        <div class="edit_profile">
            <h2>Edit your profile</h2>
            <div class="grey-line">    </div>
            <form action="" method="POST" class="edit_profile_form" name="edit_profile" enctype="multipart/form-data">
                <label for="firstname">Firstname</label>
                <input type="text" name="firstname" id="firstname" value="' . $this->fname . '">
                <label for="lastname">Lastname</label>
                <input type="text" name="lastname" id="lastname" value="' . $this->lname . '">
                <label for="age">Age</label>
                <input type="text" name="age" id="age" value="' . $this->age . '">
                <label for="location">Location</label>
                <input type="text" name="location" id="location" value="' . $this->location . '">
                
                <label for="website">Website</label>
                <input type="text" name="website" id="website" value="' . $this->website . '">
                <label for="facebook">Facebook</label>
                <input type="text" name="facebook" id="facebook" value="' . $this->facebook . '">
                <label for="twitter">Twitter</label>
                <input type="text" name="twitter" id="twitter" value="' . $this->twitter . '">
                <label for="youtube">Youtube</label>
                <input type="text" name="youtube" id="youtube" value="' . $this->youtube . '">
                <label for="instagram">Instagram</label>
                <input type="text" name="instagram" id="instagram" value="' . $this->instagram . '">
                
                <label for="profilepic">Profile Picture</label>
                <input type="file" name="profilepic" id="profilepic">
                
                <label for="profileheader">Profile Header</label>
                <input type="file" name="profileheader" id="profileheader">
                
                <input type="hidden" name="profilePicOld" value="' . $this->profileImg . '">
                <input type="hidden" name="headerPicOld" value="' . $this->headerImg . '">
                <input type="hidden" name="type" id="type" value="edit_profile">
                <input type="submit" value="Edit profile" >
            </form>
        </div>';

        return $string;
    }

    public function headerContainer() {
        $string = '
        <div class="header">
            <div class="pro-head">
                <div class="pro-head-img">
                    <img src="' . $this->imgLoc . $this->headerImg . '" alt="" />
                </div>
                <div class="pro-img">
                    <img src="' . $this->imgLoc . $this->profileImg . '"/>
                </div>
                <div class="pro-name">
                    '. $this->username .'
                </div>
            </div>
            <div class="pro-bot-hold">
                <div class="pro-bot-w">
                    <div class="w-24 info-box">
                        13
                    <div class="info-desc">Points</div>
                </div>
                <div class="w-24 info-box">
                    34
                    <div class="info-desc">Views</div>
                </div>
                <div class="w-24 info-box">
                    1,000
                    <div class="info-desc">Followers</div>
                </div>
                <div class="w-24 info-box">
                    123
                    <div class="info-desc">Follows</div>
                </div>
            </div>
        </div>';

        return $string;
    }

    public function profileInfo() {
        $string = '<div class="inner-padding">';
            if ($this->myProfile()) {
                $string .= '<a href="?view=profile&pid=' . $this->username . '&edit=true" class="edit-pro-but">Edit Profile</a>
                            <div class="grey-line">    </div>';
            }

            if(!is_null($this->facebook) || !is_null($this->twitter) || !is_null($this->youtube) || !is_null($this->instagram) || !is_null($this->website)) {
                $string .= '
                <div class="info-title">Social</div>
                <ul class="social-list" >';
                    $string .= (!is_null($this->facebook) ? '<li><a href=""><i class="icon-facebook-sign"></i><span>/</span>' . $this->facebook . '</a></li>' : '');
                    $string .= (!is_null($this->twitter) ? '<li><a href=""><i class="icon-twitter-sign"></i><span>/</span>' . $this->twitter . '</a></li>' : '');
                    $string .= (!is_null($this->youtube) ? '<li><a href=""><i class="icon-youtube-play"></i> <span>/</span>' . $this->youtube . '</a></li>' : '');
                    $string .= (!is_null($this->instagram) ? '<li><a href=""><i class="icon-instagram"></i> <span>/</span>' . $this->instagram . '</a></li>' : '');
                    $string .= (!is_null($this->website) ? '<li><a href=""><i class="icon-globe"></i> <span>/</span>' . $this->website . '</a></li>' : '');
                $string .= '</ul >';
            }

        $string .= '<div class="info-title">Bio</div>
            <div class="personal-list">
                <ul>
                    <li><span>Name:</span>' . $this->fname .' '. $this->lname . '</li>
                    <li><span>Age:</span>' . $this->age . '</li>
                    <li><span>Country:</span>' . $this->location . '</li>
                </ul>
            </div>
        </div>';

        return $string;
    }

    public function overview() {
        $string = '
        <h4>Recently Played games</h4>
        <div class="game-list">
          <div class="game-cover">
            <div class="game-cov-img">
              <a href="#"><img src="http://vignette2.wikia.nocookie.net/callofduty/images/7/76/Game_cover_art_BOIII.jpg/revision/latest/scale-to-width-down/300?cb=20160211194000" alt="" /></a>
            </div>
            <div class="game-cov-title">CS:GO</div>
          </div>
                  <div class="game-cover">
            <div class="game-cov-img">
              <a href="#"><img src="http://vignette2.wikia.nocookie.net/callofduty/images/7/76/Game_cover_art_BOIII.jpg/revision/latest/scale-to-width-down/300?cb=20160211194000" alt="" /></a>
            </div>
            <div class="game-cov-title">CS:GO</div>
          </div>
          <div class="game-cover">
            <div class="game-cov-img">
              <a href="#"><img src="http://vignette2.wikia.nocookie.net/callofduty/images/7/76/Game_cover_art_BOIII.jpg/revision/latest/scale-to-width-down/300?cb=20160211194000" alt="" /></a>
            </div>
            <div class="game-cov-title">COD BO3</div>
          </div>
                  <div class="game-cover">
            <div class="game-cov-img">
              <a href="#"><img src="http://static.giantbomb.com/uploads/original/1/13692/2302957-i2cs9uzmq4yua.jpg" alt="" /></a>
            </div>
            <div class="game-cov-title">CS:GO</div>
          </div>
        </div>

        ';// end of string

        return $string;
    }

    public function teams() {
        $string = "This will have the layout for the teams section";

        $string .= '<div style="border: 1px solid red">Testing for the Teams Div</div>';

        return $string;
    }

    public function gameHistory() {
        $string = "This will have the layout for the history section";

        $string .= '<div style="border: 1px solid blue">Testing for the history Div</div>';

        return $string;
    }

    public function myProfile() {
        if($this->username === $this->viewer) {
            return true;
        }
        else {
            return false;
        }
    }
}