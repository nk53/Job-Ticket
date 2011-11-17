<?php

// Unset user cookie
setcookie('user', null);

// Return user to home page
header('Location: http://cptrserver.ucollege.edu/nakern/www/index.php');

?>
