<?php
include_once 'settings.php';

// Includes all the class files
foreach (glob(INCLUDESPATH . 'class/*.class.php', GLOB_NOCHECK) as $filename) {
    include_once $filename;
}


/* Instantiating a class of error pages and set reporting values */
$errorPages = new error(DEBUG);

/* Instantiating a class of reporting*/
$response = new response();

/* Instantiating a class for the database*/
$db = new database(HOST, USER, PASSWORD, DATABASE, DEBUG, $response);




function hasPermission($id=null, $page=null, $db) {

    if(isset($id)) {
        $permissionLevel = $db->SELECT("SELECT permissions FROM users WHERE id = ?", array('i', $id));

        switch ($page) {
            default:
                $return = true;
        }

        return $return;
    }

}

function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"],
        $cookieParams["path"],
        $cookieParams["domain"],
        $secure,
        $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session
    session_regenerate_id(true);    // regenerated the session, delete the old one.
}