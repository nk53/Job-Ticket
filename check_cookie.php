<?php

/**
 * $priv is the minimum privilege level required to view the page
 */
function check_cookie($referer, $priv) {
  if (isset($_COOKIE['user'])) {
    if (!$priv || $_COOKIE['privileges'] > $priv) {
      // User exists and has privileges.
      setcookie('referer', null);
      return true;
    } else {
      // User doesn't have required privileges.
?>
<html>
<head><title>Access Denied</title></head>
<body>You do not have permission to view this page. Return to the <a href="index.php">Home Page</a>.</body>
</html>
<?php
      return false;
    }
  } else {
    setcookie('referer', $referer);
    header("Location: login.php");
  }
}
?>
