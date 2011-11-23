<?php

// Unset user cookie
setcookie('user', null);

// Return user to home page
header('Location: index.php');

?>
