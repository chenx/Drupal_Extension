<?php
/*
 * Note: chdir() must use the root of drupal. Otherwise it won't work.
 */
chdir('/home2/cssauhco/www/xc/drupal7');
$base_url = 'http://cssauh.com/xc/drupal7';
require_once './includes/bootstrap.inc';
define('DRUPAL_ROOT', getcwd());
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL); 

if (is_admin()) {
    include "header_admin.php";
} else {
    include "header.php";
}
?>


<?php

if (is_user_logged_in()) {
    showUserInfo();
    print "<p><br/><a href='../?q=user/logout'>Log out</a></p>";
} else {
    print "<a href='../'>Login</a> | <a href='../?q=user/register'>Register</a>";
}


function is_user_logged_in() {
    global $user;
    return isset($user) && $user->uid > 0;
}

function is_admin() {
    global $user;
    return in_array("administrator", $user->roles);
}

function showUserInfo() {
    global $user;
    $user_info = "Current User:<br/>" . <<<EOF
uid: $user->uid <br/>
name: $user->name <br/>
mail: $user->mail <br/>
EOF;
    $user_info .= "roles: " . implode(', ', $user->roles);
    print "$user_info <br/><br/>";

    var_dump($user);
}

?>


<?php
include("footer.php");
?>
