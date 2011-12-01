<?php

$links = array(
  'index.php' => 'Home',
  'assign.php' => 'Assign',
  'history.php' => 'History',
  'record.php' => 'Record',
  'request.php' => 'Request',
);

$header = '';

foreach ($links as $link => $text) {
  if (!strpos($_SERVER['PHP_SELF'], $link)) {
    $header .= "<a href='$link'>$text</a> ";
  }
}

substr($header, 0, -1);

echo $header;
