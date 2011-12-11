<?php

/** @file show_list.php
 * List functions
 *
 * This file contains functions that deal with lists and list components.
 * There are functions to generate list components and to parse certain
 * elements (e.g. phone numbers).
 * 
 * Three constants are defined:
 *   NO_HIGHLIGHT  == null
 *   LIMIT_TO_USER == true
 *   NO_EDIT       == false
 * You can pass these as arguments to show_list().
 */

define('NO_HIGHLIGHT', null);
define('LIMIT_TO_USER', true);
define('NO_EDIT', false);

require_once('Jobs.php');
require_once('Records.php');
require_once('Record.php');
require_once('Users.php');
require_once('Workers.php');

/**
 * Generate a list from information in a table
 * 
 * @param $list
 *   A string indicating the list to be generated: 'assign', 'history',
 *   'Record', or 'Jobs'.
 * 
 * @param $id
 *   The id of the item to be highlighted (usually the selected item)
 *   or NO_HIGHLIGHT (null) if no item is to be highlighted.
 * 
 * @param $limit_to_user
 *   Defaults to null. Any true value makes the 'jobs' list only show
 *   jobss by that user.
 * 
 * @param $edit
 *   Defaults to true. Use false if you don't want to show the last
 *   column (called 'edit' or 'view').
 */
function show_list($list, $id, $options=array()) {
  $edit = (!is_null($options['edit'])) ? $options['edit'] : true;
  $view = (!is_null($options['view'])) ? $options['view'] : false;
  $prev = '';
  $next = '';
  $id_list = array();
  $do = new $list();
  $p_key = $do->primary;
  // strlen($var) returns false when $var is null or ''
  //if (strlen($id)) {
  //  $do->$p_key = $id;
  //}
  if ($list == 'Jobs') {
    if ($options['limit_to_user']) {
      $do->userId = $_COOKIE['uid'];
      $do->limit(10);
      $do->order_by('jobId DESC');
    } else if ($options['assigned_to_user']) {
      $do->assignedUserId = $_COOKIE['uid'];
    } else {
      $do->status = 0;
    }
  } else if ($list == 'Records') {
    $do->jobId = $id;
  }
  // Don't show pending in history screen
  if (is_null($options['pending']) || $options['pending']) {
    // We're not on the history screen
    $do->find(false);
  } else if ($list == 'Jobs') {
    // We are on the history screen
    $do->query('SELECT * FROM Jobs WHERE status != 0 OR completed = 1', false);
  }
  show_header($list, $edit, $view);
  
  // If this is the records form and no job has been selected,
  // don't only show the records table header
  if ($list != 'Records' || strlen($id)) {
    // Keep track of even/odd rows and prev/next list items
    for ($i=0; $do->rows(); $i++) {
      $is_selected = ($do->$p_key == $id) ? ' selected' : '';
      $id_list[] = $do->$p_key;
      echo_row($list, $do, ($i%2 == 0) ? 'even' : 'odd', $is_selected, $edit, $view);
    }
    
    // Set $prev and $next
    for($j=0;$j<count($id_list);$j++){
      if ($id_list[$j] == $id && $j > 0){
        $prev = $id_list[$j-1];
      }
      if ($id_list[$j] == $id && $j < count($id_list)) {
        $next = $id_list[$j+1];
      }
    }
  }
  
  echo "</form></table></div>";
  echo "<script>var prev = '$prev'; var next = '$next';</script>";
}

// Function start: show_header, hides edit/view column if $edit is false
function show_header($list, $edit, $view) { ?>
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
<?php } else if ($list == 'Jobs') {
?>
<tr>
  <th>Job Id</th>
  <th>Name</th>
  <th>Phone</th>
  <th>Deadline</th>
  <th>Description</th>
  <th>Status</th>
<?php if ($edit): ?>
  <th>Edit</th>
<?php endif; ?>
<?php if ($view): ?>
  <th>View</th>
<?php endif; ?>
</tr>
<?php } else if ($list == 'Records') { ?>
<tr>
  <th>Record Id</th>
  <th>Job Id</th>
  <th>Hours Worked</th>
  <th>Material Cost</th>
  <th>Date Completed</th>
  <th>Materials Used</th>
  <th>Edit</th>
</tr>
<?php }

}
// Function end: show_header

function echo_row($list, $do, $even_or_odd, $is_selected, $edit, $view) {
  if ($list == 'assign') {
    // Get fullname of requestor
    $req = new Jobs();
    $req->id = $do->rid;
    $req->find();
    $requestor = $req->name;
    // Get fullname of person this job is assigned to
    $user = new Users();
    $user->uid = $do->aid;
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
  } else if ($list == 'Records') {
    if (strlen($do->materialsUsed) > 23) {
      $materials = substr($do->materialsUsed, 0, 20)."...";
    } else {
      $materials = $do->materialsUsed;
    }
    // Get fullname of User
    $user = new Users();
    $user->id = $do->uid;
    $user->find();
    $assigned_to = $user->fullname;
    echo "<tr class='$even_or_odd$is_selected'>" .
      "<td>{$do->recordId}</td>" .
      "<td>{$do->jobId}</td>" .
      "<td>{$do->hoursWorked}</td>" .
      "<td>\${$do->materialCost}</td>" .
      "<td>{$do->dateCompleted}</td>" .
      "<td>$materials</td>" .
      "<td><a href='{$_SERVER['PHP_SELF']}?rid={$do->recordId}'>Edit</a></td>" .
    "</td>";
      
  } else if ($list == 'Jobs') {
    $a = "<a href='{$_SERVER['PHP_SELF']}?id={$do->jobId}'>";
    if ($edit) {
      $a .= 'Edit</a>';
    } else if ($view) {
      $a .= 'View</a>';
    }
    // Get the name of the person who submitted the job
    $user = new Users();
    $user->userId = $do->userId;
    $user->find();
    if (strlen($do->description) > 23) {
      $description = substr($do->description, 0, 20)."...";
    } else {
      $description = $do->description;
    }
    $approved = $do->approved($do->status);
?>
<tr class='<?php echo $even_or_odd.$is_selected ?>'>
  <td><?php echo $do->jobId ?></td>
  <td><?php echo $user->fullName ?></td>
  <td><?php echo parse_phone($do->contactNumber) ?></td>
  <td><?php echo $do->dueDate ?></td>
  <td><?php echo $description ?></td>
  <td><?php echo $approved ?></td>
<?php if ($edit || $view): ?>
  <td><?php echo $a ?></td>
<?php endif; ?>
</tr>
<?
  }
}

/**
 * Replaces 1-integer-long strings with '-' to represent a phone number.
 * 
 * @param p an 10-digit phone number consisting of only digits (no '-')
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

/**
 * Takes a date string of the form YYYY-MM-DD and returns an HTML select
 * 
 * @param d
 *   The YYYY-MM-DD date string.
 */
function date_option($d, $disabled=false) {
  $disabled = ($disabled) ? ' disabled' : '';
  $year = substr($d, 0, 4);
  $month = substr($d, 5, 2);
  $day = substr($d, 8, 2);
  $select = "<select id='year' name='year'$disabled>";
  // Construct the year options
  for ($i=0; $i<5; $i++) {
    $select .= '<option value="'.($year+$i).'">'.($year+$i).'</option>';
  }
  $select .= "</select><select id='month' name='month'$disabled>";
  // Construct month options
  for ($i=1; $i<13; $i++) {
    $month_name = date('F', strtotime("$year-$i-$day"));
    $selected = ($month*1 == $i) ? 'selected="selected"' : '' ;
    $select .= "<option id='month_$i' $selected value='$month_name'>$month_name</option>";
  }
  $select .= "</select><select id='day' name='day'$disabled>";
  // Construct day options
  $days_in_month = date('t', strtotime($d));
  for ($i=1; $i<=$days_in_month; $i++) {
    $selected = ($day*1 == $i) ? 'selected="selected"' : '';
    $select .= "<option id='day_$i' $selected value='$i'>$i</option>";
  }
  $select .= '</select>';
  return $select;
}

/**
 * Returns a select containing the names of all workers
 */ 
function assign_to_option() {
  $do = new Workers();
  $do->find(false);
  $options = '';
  while ($do->rows()) {
    $user = new Users();
    $user->get($do->userId);
    $options .= "<option value='{$do->userId}'>{$user->fullName}</option>";
  }
  return $options;
}

?>
