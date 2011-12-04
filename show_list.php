<?php

/** @file show_list.php
 * List functions
 *
 * This file contains functions that deal with lists and list components.
 * There are functions to generate list components and to parse certain
 * elements (e.g. phone numbers).
 */

require_once('Assign.php');
require_once('Request.php');
require_once('Record.php');
require_once('Users.php');

function show_list($list, $id) {
  $prev = '';
  $next = '';
  $id_list = array();
  if ($list == 'assign') {  
    $do = new Assign();
    $query = "SELECT assign.* FROM assign, request " .
             "WHERE request.id = assign.rid " .
             "AND request.approved = TRUE " .
             "ORDER BY assign.id";
    $do->query($query);
  } else if ($list == 'history') {
    $do = new Request();
    $do->find(false);
  } else if ($list == 'record') {
    $do = new Record();
    $do->find(false);
  } else if ($list == 'request') {
    $do = new Request();
    $do->find(false);
  }
  show_header($list);
  for ($i=1; $do->rows(); $i++) {
    $is_selected = ($do->id == $id) ? ' selected' : '';
    $id_list[] = $do->id;
    echo_row($list, $do, ($i%2 == 0) ? 'even' : 'odd', $is_selected);
  }
  
  for($j=0;$j<count($id_list);$j++){
    if ($id_list[$j] == $id && $j > 0){
      $prev = $id_list[$j-1];
    }
    if ($id_list[$j] == $id && $j < count($id_list)) {
      $next = $id_list[$j+1];
    }
  }

  echo "</form></table></div>";
  echo "<script>var prev = '$prev'; var next = '$next';</script>";
}

// Function start: show_header
function show_header($list) { ?>
<div class="list">
<table>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php
  if ($list == 'assign') {
?>
<tr>
  <th>Assignment Id</th>
  <th>Requestor</th>
  <th>Estimated Hours to Complete</th>
  <th>Estimated Cost</th>
  <th>Estimated Date of Completion</th>
  <th>Assigned To</th>
  <th>Edit</th>
</tr>
<?php
  } else if ($list == 'history') {
?>
<tr>
  <th>Request Id</th>
  <th>Name</th>
  <th>Phone</th>
  <th>Deadline</th>
  <th>Description</th>
  <th>Approved</th>
  <th>View</th>
</tr>
<?php } else if ($list == 'request') {
?>
<tr>
  <th>Request Id</th>
  <th>Name</th>
  <th>Phone</th>
  <th>Deadline</th>
  <th>Description</th>
  <th>Approved</th>
  <th>Edit</th>
</tr>
<?php } else if ($list == 'record') { ?>
<tr>
  <th>Record Id</th>
  <th>Assignment Id</th>
  <th>Assigned To</th>
  <th>Date of Work</th>
  <th>Hours</th>
  <th>Materials</th>
  <th>Cost</th>
  <th>Edit</th>
</tr>
<?php }

}
// Function end: show_header

function echo_row($list, $do, $even_or_odd, $is_selected) {
  if ($list == 'assign') {
    // Get fullname of requestor
    $req = new Request();
    $req->id = $do->rid;
    $req->find();
    $requestor = $req->name;
    // Get fullname of person this job is assigned to
    $user = new Users();
    $user->id = $do->aid;
    $user->find();
    $assigned_to = $user->fullname;
    //echo ($is_selected) ? 'TRUE' : 'FALSE';
    echo "<tr class='$even_or_odd$is_selected'>" .
      "<td>{$do->id}</td>" .
      "<td>$requestor</td>" .
      "<td>{$do->hours}</td>" .
      "<td>\${$do->cost}</td>" .
      "<td>{$do->complete}</td>" .
      "<td>$assigned_to</td>" .
      "<td><a href='{$_SERVER['PHP_SELF']}?aid={$do->id}'>Edit</a></td>" .
    "</td>";
  } else if ($list == 'history') {
    if (strlen($do->description) > 23) {
      $description = substr($do->description, 0, 20)."...";
    } else {
      $description = $do->description;
    }
    $approved = ($do->approved) ? 'Yes' : 'No';
    echo "<tr class='$even_or_odd$is_selected'>" .
      "<td>{$do->id}</td>" .
      "<td>{$do->name}</td>" .
      '<td>'.parse_phone($do->phone).'</td>' .
      "<td>{$do->deadline}</td>" .
      "<td>$description</td>" .
      "<td>$approved</td>" .
      "<td><a href='{$_SERVER['PHP_SELF']}?id={$do->id}'>View</a></td>" .
    "</tr>";
  } else if ($list == 'record') {
    if (strlen($do->materials) > 23) {
      $materials = substr($do->materials, 0, 20)."...";
    } else {
      $materials = $do->materials;
    }
    // Get fullname of User
    $user = new Users();
    $user->id = $do->uid;
    $user->find();
    $assigned_to = $user->fullname;
    echo "<tr class='$even_or_odd$is_selected'>" .
      "<td>{$do->id}</td>" .
      "<td>{$do->aid}</td>" .
      "<td>$assigned_to</td>" .
      "<td>{$do->date}</td>" .
      "<td>{$do->hours}</td>" .
      "<td>$materials</td>" .
      "<td>\${$do->cost}</td>" .
      "<td><a href='{$_SERVER['PHP_SELF']}?id={$do->id}'>Edit</a></td>" .
    "</td>";
      
  } else if ($list == 'request') {
    if (strlen($do->description) > 23) {
      $description = substr($do->description, 0, 20)."...";
    } else {
      $description = $do->description;
    }
    $approved = ($do->approved) ? 'Yes' : 'No';
    echo "<tr class='$even_or_odd$is_selected'>" .
      "<td>{$do->id}</td>" .
      "<td>{$do->name}</td>" .
      '<td>'.parse_phone($do->phone).'</td>' .
      "<td>{$do->deadline}</td>" .
      "<td>$description</td>" .
      "<td>$approved</td>" .
      "<td><a href='{$_SERVER['PHP_SELF']}?id={$do->id}'>Edit</a></td>" .
    "</tr>";
  }
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
