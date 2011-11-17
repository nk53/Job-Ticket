<?php
$username = $_COOKIE['user'];
$link = mysql_connect('localhost', 'nakern', 'nakern')
  or die('Could not connect: ' . mysql_error());
mysql_select_db('nakern') or die('Could not select database');

$query = "SELECT fullname FROM users;";
$result = mysql_query($query) or die('Query failed: ' . mysql_error());

while ($row = mysql_fetch_assoc($result)) { 
  $fullname=$row['fullname']; ?>
<option value="<?php echo $fullname;?>"><?php echo $fullname;?></option>
<?php } ?>
