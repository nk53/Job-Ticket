<!doctype html>
<html>
<head>
<title>Home</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<center>
<p>
<a href="assign.php">Assign</a>
<a href="record.php">Record</a>
<a href="request.php">Request</a>
<?php if (isset($_COOKIE["user"])): ?>
<a href="logout.php">Logout</a>
<?php endif; ?>
</p>
<?php if (empty($_GET)): ?>
<p>Only authorized users are allowed to accessed the above pages. If you attempt to access one of these pages, you will be prompted to log in.</p>
<?php else: ?>
<span class="confirmation">Your submission was received.</span>
<?php endif; ?>
</center>
</body>
</html>
