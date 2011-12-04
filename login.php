<?php

if (isset($_POST['user'])) {
  $username = $_POST['user'];
  $password = $_POST['password'];
  // Connect to MySQL
  $link = mysql_connect('localhost', 'nakern', 'nakern')
	or die('Could not connect: ' . mysql_error());
  // Select Database
  mysql_select_db('nakern') or die('Could not select database');

  // Query Database
  $query = "SELECT * FROM users WHERE users.name='$username' " .
           "AND users.password='$password';";
  $result = mysql_query($query) or die('Query failed: ' . mysql_error());
  
  // Read first result
  $user = mysql_fetch_assoc($result);
  
  if ($user) {
    // Give user login cookie, or FALSE if login failed
    setcookie('user', $user['name']);
    setcookie('uid', $user['uid']);
    setcookie('privileges', $user['privileges']);
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
