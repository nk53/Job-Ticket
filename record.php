<?php
require_once('check_cookie.php');
require_once('show_list.php');
require_once('Records.php');
require_once('Jobs.php');
require_once('Users.php');
if (check_cookie($_SERVER['PHP_SELF'], 2)) {
  print_r($_POST);
  if (strlen($_POST)) {
    if (strlen($_POST['rid'])) {
      $rec = new Records();
      $rec->update_record($_POST);
    } else if (strlen($_POST['id'])) {
      $rec = new Records();
      $rec->insert_record($_POST);
    }
  }
  // Initialize values!
  $rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  $requestor = '';
  $requestor_phone = '';
  $deadline = '';
  $description = '';
  $date = '';
  $hours = '';
  $materials = '';
  $cost = '';
  $row_size = '1';
  if (strlen($rid)) {
    $rec = new Records();
    $rec->get($rid);
    $job = new Jobs();
    $job->get($rec->jobId);
    $id = $job->jobId;
    $date = date_option($rec->dateCompleted);
    $hours = $rec->hoursWorked;
    $materials = $rec->materialsUsed;
    $cost = '$'.$rec->materialCost;
    $completed = ($job->completed) ? 'checked="checked"' : '';
    $user = new Users();
    $requestor = $user->user_name($job->userId);
    $requestor_phone = parse_phone($job->contactNumber);
    $deadline = $job->dueDate;
    $description = $job->description;
    $row_size = 1 + strlen($description) / 40;
  } else if (strlen($id)) {
    $job = new Jobs();
    $job->get($id);
    $user = new Users();
    $requestor = $user->user_name($job->userId);
    $requestor_phone = parse_phone($job->contactNumber);
    $deadline = $job->dueDate;
    $description = $job->description;
    $materials = $job->materials;
    $row_size = 1 + strlen($description) / 40;
  }
  $disabled = (!strlen($id) && !strlen($rid)) ? ' disabled' : '';
  if (!$date) {
    $date = date_option(date('Y-m-d'), $disabled);
  }

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Job Records</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type="text/javascript" src="validator.js"></script>
<script type="text/javascript" src="validate_record.js"></script>
</head>
<body onload="loadEventHandlers();">
  <?php include('header.php'); ?>
  <a href="logout.php">Logout</a>
  <h1>Job Record Form</h1>
  <form id="form" method="post" action="record.php">
    <div class="form">
      <input type="hidden" id="rid" name="rid" value="<?php echo $rid ?>" />
      <input type="hidden" id="id" name="id" value="<?php echo $id ?>" />
      <table border="0">
        <tr>
          <td>Requested by:</td>
          <td><input type="text" value="<?php echo $requestor ?>" disabled /></td>
        </tr>
        <tr>
          <td>Phone:</td>
          <td><input type="text" value="<?php echo $requestor_phone ?>" disabled /></td>
        </td>
        <tr>
          <td>Deadline:</td>
          <td><input type="text" value="<?php echo $deadline ?>" disabled /></td>
        </tr>
        <tr>
          <td>Description:</td>
          <td><textarea rows="<?php echo $row_size ?>" cols="40" disabled><?php echo $description ?></textarea></td>
        </tr>
        <tr>
          <td>Date:</td>
          <td><?php echo $date ?></td>
        </tr>
        <tr>
          <td>Number of hours:</td>
          <td><input name="hours" id="hours" type="text" value="<?php echo $hours ?>"<?php echo $disabled ?> /></td>
       </tr>
        <tr>
          <td>Materials used:</td>
          <td><textarea name="materials" id="materials" rows="3" cols="40"<?php echo $disabled ?>><?php echo $materials ?></textarea></td>
       </tr>
        <tr>
          <td>Cost of materials:</td>
          <td><input name="cost" id="cost" type="text" value="<?php echo $cost ?>"<?php echo $disabled ?> /></td>
        </tr>
        <tr>
          <td>Completed:</td>
          <td><input name="completed" type="checkbox"<?php echo $completed ?> /></td>
        </tr>
      </table>
      <input type="submit" value="Submit" />
    </div>  
  </form>
  <?php show_list('Records', $id); ?>
  <?php show_list('Jobs', $id, array('assigned_to_user' => true)); ?>
</body>
</html>
<?php } ?>
