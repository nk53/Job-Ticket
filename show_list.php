<?php

/** @file show_list.php
 * List functions
 *
 * This file contains functions that deal with lists and list components.
 * There are functions to generate list components and to parse certain
 * elements (e.g. phone numbers).
 */

require_once('Request.php');

function show_list() {
  
  $req = new Request();
  
  $req->find(false);
  
  show_header();
  
  for ($i=1; $req->rows(); $i++) {
    echo_row($req, ($i%2 == 0) ? 'even' : 'odd');
  }
  
  echo "</form></table>";
  
}

// Function start: show_header
function show_header() { ?>
<table class="list">
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<tr>
  <th>Name</th>
  <th>Phone</th>
  <th>Deadline</th>
  <th>Description</th>
  <th>Approved</th>
  <th>Edit</th>
</tr>
<?php }
// Function end: show_header

function echo_row($req, $even_or_odd) {
  if (strlen($req->description) > 23) {
    $description = substr($req->description, 0, 20)."...";
  } else {
    $description = $req->description;
  }
  $approved = ($req->approved) ? 'Yes' : 'No';
  echo "<tr class='$even_or_odd'><td>{$req->name}</td>" .
           '<td>'.parse_phone($req->phone).'</td>' .
           "<td>{$req->deadline}</td>" .
           "<td>$description</td>" .
           "<td>$approved</td>" .
           "<td><a href='{$_SERVER['PHP_SELF']}?id={$req->id}'>Edit</a></td></tr>";
}

/**
 * Replaces 11-integer-long strings with '-' to represent a phone number.
 * 
 * @param p an 11-digit phone number consisting of only digits (no '-')
 */
function parse_phone($p) {
  return substr($p, 0, 3).'-'.substr($p, 3, 3).'-'.substr($p, 6);
}

/**
 * Takes a date from an array and returns a MySQL date string.
 *
 * @param d array; contains 'year', 'month', and 'day' elements.
 */
function parse_date($d) {
  $day = $d['day'];
  $month = $d['month'];
  $year = $d['year'];
  $time = strtotime("$day $month $year");
  return date('Y-m-d', $time);
}

?>
