# Drupal_Extension
Extension to customize WordPress as website framework.

# About

Similar to WordPress, Drupal is a CMS that has a lot of themes to choose from. Use drupal as a website framework can save a lot of work on user interface.

This project aims to find a way to allow develop any custom functions based on a Drupal installation.


# Issues to address

For this purpose, one should be able to create custom roles (user groups), members of such roles may or may not be able to post blogs, but can access custom pages once log in. Several things are crucial:

1) create custom role(s).
2) check if a user is logged in, and access user profile such as ID, login name, user name, email, user type. Based on this, one can add custom database tables and build whatever function that is desired.
3) make use of Drupal theme in custom page.

A study on these requirements proves fruitful. Over the past week, I was able to build a member page for some Drupal themes. It's easy to extend to more themes.


# How to use templates in this project


# Implementation: Basics

## 1) Create custom roles

## 2) Check user login status, and obtain user profile

Basically, in order to access Drupal's built in framework functions, you need to bootstrap it by including the following code at the head of your page:

```php
<?php
/*
 * Note: chdir() must use the root of drupal. Otherwise it won't work.
 */
chdir('/home2/cssauhco/www/xc/drupal7');
$base_url = 'http://cssauh.com/xc/drupal7';
require_once './includes/bootstrap.inc';
define('DRUPAL_ROOT', getcwd());
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

include "header.php";
?>
```

Then you can check if a user is logged in with the global $user variable:

```php
function is_user_logged_in() {
    global $user;
    return isset($user) && $user->uid > 0;
}
```

and then further access user profile:

```php
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
```

## 3) Use WordPress theme in custom page.

Different from WordPress, which uses get_header() and get_footer() functions for a theme, Drupal uses page template. So at present, this project create custom header and footer by extracting html source from an existing page of the current theme.

So far, a minimal template page is like this:

```php
<?php
/*
 * Note: chdir() must use the root of drupal. Otherwise it won't work.
 */
chdir('/home2/cssauhco/www/xc/drupal7');
$base_url = 'http://cssauh.com/xc/drupal7';
require_once './includes/bootstrap.inc';
define('DRUPAL_ROOT', getcwd());
drupal_bootstrap(DRUPAL_BOOTSTRAP_FULL);

include "header.php";


if (is_user_logged_in()) {
    showUserInfo();
} else {
    print "<a href='../'>Login</a> | <a href='../?q=user/register'>Register</a>";
}


function is_user_logged_in() {
    global $user;
    return isset($user) && $user->uid > 0;
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

include("footer.php");
?>

```
