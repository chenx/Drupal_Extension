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

The projects contains a series of folders member_[theme]. Each folder is for a different theme. Right now the available themes include:

- Bartik

In order to use the templates, follow these steps:

- Copy the theme template folder(s) to your Drupal installation root
- In Drupal admin bar, go to Appearance, active your theme, say "Bartik".
- In Drupal admin bar, go to Structure -> Menus, in "Main menu" section, click on "Add Link", add link to "member".
  - Note: if the "Member" link already exists, you can just update its URL.
- Now visit your Drupal homepage, click on "Member" link, it'll bring you to the custom page.

That's all.


# Implementation: Basics

## 1) Create custom roles

## 2) Check user login status, and obtain user profile

Basically, in order to access Drupal's built in framework functions, you need to bootstrap it by including the following code at the head of your page:

```php
<?php
/*
 * Note: chdir() must use the root of drupal. Otherwise it won't work.
 */
chdir('/var/www/html/drupal'); // Drupal root directory.
$base_url = 'http://mysite.com/drupal'; // Drupal site url.
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
chdir('/var/www/html/drupal'); // Drupal root directory.
$base_url = 'http://mysite.com/drupal'; // Drupal site url.
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


# Implementation: More Details

## 1) Hide a menu item

Say you have the "member" menu item when a user logs in. However you want to hide it for anonymous users. There are some online discussions for this [2][3]. 

Of course the more formal way is to look into Drupal source code to hide a menu item at menu generation time.

A more simple way is to hide it using javascript when the page loads.

For example, checking the menu's html source you see this:

```html
<ul id="main-menu-links" class="links clearfix">
    <li class="menu-218 first active"><a href="/" class="active">Home</a></li>
    <li class="menu-308 last"><a href="http://mysite.com/member/">Member</a></li>
</ul>    
```

Then you can modify /themes/[theme]/templates/page.tpl.php, and append this code (this is good for the Bartik theme) to the end:

```php
<?php if (! isset($user) || $user->uid == 0) { ?>
<script type="text/javascript">
    var v = document.getElementsByClassName('menu-308 last');
    v[0].style.display = 'none';
</script>
<?php } ?>
```

Or more general, for any theme, append this code to end of /themes/[theme]/templates/html.tpl.php (for home page) and /themes/[theme]/templates/page.tpl.php (for other pages).

```php
<?php if (! isset($user) || $user->uid == 0) { ?>
<script type="text/javascript">
    var div = document.getElementsByClassName('menu');
    var ul = div[0];
    //alert(ul.innerHTML);
    for (var i = 0, n = ul.childNodes.length; i < n; i += 2) {
        var item = ul.childNodes[i];
        if (item.innerHTML.indexOf('Member') > 0) {
            //alert(item.innerHTML);
            item.style.display = 'none';
        }
    }
</script>
<?php } ?>
```

# Misc Stuff

- Drupal site setting is in: /sites/default/settings.php  

- Drupal site theme is in: /themes/[theme]/   

- Drupal theme page template: /themes/[theme]/templates/page.tpl.php  

- Installed themes are stored in /sites/all/themes/. Delete a theme folder here, it will disappear from admin page.
- 

# Future Work


# Summary

This project shows how to create custom pages for logged in users of a Drupal site.


# License

Apache/BSD/MIT/GPLv2


# Author

X. Chen  
Created On: August 28, 2015  
Last Modified: August 28, 2015   


# References:

[1] http://www.nguyenquyhy.com/2014/06/access-current-user-outside-of-drupal/  
[2] <a href="https://www.drupal.org/node/55296">Hide navigation for anon users</a>   
[3] <a href="https://www.drupal.org/node/50413">Role based visibility of menu items</a>   

