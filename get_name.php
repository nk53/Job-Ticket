<?php
$username = $_COOKIE['user'];
$link = mysql_connect('localhost', 'nakern', 'nakern')
  or die('Could not connect: ' . mysql_error());
mysql_select_db('nakern') or die('Could not select database');

$query = "SELECT fullname FROM users WHERE name='$username';";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

$user = mysql_fetch_assoc($result);

echo $user['fullname'];
?>
