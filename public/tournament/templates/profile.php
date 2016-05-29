<?php
$pid = $_GET['pid'];
$profilePage = $_GET['page'];
$edit = $_GET['edit'];

$pclass = new user_profile($db, $pid, $username);

echo '<div class="wrapper">';
if(isset($edit) && $edit && isset($pid)) {
    if($pclass->myProfile()) {
        echo $pclass->editProfile();
    }
    else {
        echo 'You are not authorized to edit this profile';
    }
}
else if(isset($pid)) {

    if (!empty($pclass->empty_msg)) {
        echo $pclass->empty_msg;
    } else {
        echo '
        <div class="w-container">
            ' . $pclass->headerContainer() . '
        </div>
        <div class="info-container-l">
            <div class="tab-switch">
                <a href="?view=profile&pid=' . $pid . '&page=overview">User</a>
                <a href="?view=profile&pid=' . $pid . '&page=teams">Teams</a>
                <a href="?view=profile&pid=' . $pid . '&page=history">History</a>
                <a href="">Matches</a>
            </div>
            <div class="inner-padding">
                <h4>Info Shown Below Is Switched:</h4>';
        switch ($profilePage) {
            case 'overview':
                echo $pclass->overview();
                break;
            case 'teams':
                echo $pclass->teams();
                break;
            case 'history':
                echo $pclass->gameHistory();
                break;
            default:
                echo $pclass->overview();
        }
        echo '
            </div>
        </div>
    
        <div class="info-container-r">
            ' . $pclass->profileInfo() . '
        </div>

        <div class="profile-information"></div>';
    }
}
else {
    echo 'No user selected. How to handle this :)?';
}
echo '</div>';
