<?php

require_once('Users.php');

if (isset($_POST['user'])) {
  $username = $_POST['user'];
  $password = $_POST['password'];
  
  $user = new Users();
  $uid = $user->check_user($username, $password);
  
  if ($uid > 0) {
    // Give user login cookie, or FALSE if login failed
    setcookie('user', $_POST['user']);
    setcookie('uid', $uid);
    //echo $_COOKIE['referer'];
    // Return user to home page
    header('Location: '.$_COOKIE['referer']);
  } else {
    show_login_form(1);
  }
} else {
  show_login_form();
}

function show_login_form($login_failure=0) {
?>
<!doctype html>
<html>
<head>
<title>Login</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<h1>Login</h1>
<form action="login.php" method="post">
<div class="form">
<table>
<tr><td>Username</td></tr>
<tr><td><input type="text" name="user" /></td></tr>
<tr><td>Password</td></tr>
<tr><td><input type="password" name="password" /></td></tr>
</table>
</div>
<input type="submit" name="submit" value="Submit" />
</form>
<?php if ($login_failure) {?>
<p><span id="err_txt">
The username or password you entered was incorrect.
</span></p>
<?php } ?>
</body>
</html>
<?php } ?>
