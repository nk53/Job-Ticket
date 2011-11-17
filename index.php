<!doctype html>
<html>
<head><title>Home</title></head>
<body>
<center>
<p>
<a href="assign.php">Assign</a>
<a href="record.php">Record</a>
<a href="request.php">Request</a>
<?php if (isset($_COOKIE["user"])) { ?>
<a href="logout.php">Logout</a>
<?php } ?>
</p>
<p>
Only authorized users are allowed to accessed the above pages. If you attempt to access one of these pages, you will be prompted to log in.
</p>
</center>
</body>
</html>
