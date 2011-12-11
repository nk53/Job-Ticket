<?php
require_once('check_cookie.php');
require_once('show_list.php');
require_once('Records.php');
require_once('Jobs.php');
require_once('Users.php');
if (check_cookie($_SERVER['PHP_SELF'], 2)) {
  if (!empty($_POST)) {
    if (!empty($_POST['rid'])) {
      $asn = new Assign();
      $asn->id = $_POST['rid'];
      $asn->find();
      $rec = new Record();
      $rec->aid = $_POST['rid'];
      $rec->uid = $asn->rid;
      $rec->date = parse_date($_POST);
      $rec->hours = $_POST['hours'];
      $rec->materials = $_POST['materials'];
      $rec->cost = str_replace('$', '', $_POST['cost']);
      $rec->insert();
    } else if (!empty($_POST['id'])) {
      $rec = new Record();
      $date = parse_date($_POST);
      $cost = str_replace('$', '', $_POST['cost']);
      $query = 'UPDATE record ' . 
               "SET date='$date', " .
               "hours={$_POST['hours']}, " .
               "materials='{$_POST['materials']}', " .
               "cost=$cost " .
               "WHERE id={$_POST['id']}";
      $rec->query($query);
    }
  }
  // Initialize values!
  $rid = (isset($_GET['rid'])) ? $_GET['rid'] : '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : '';
  $requestor = '';
  $requestor_phone = '';
  $deadline = '';
  $description = '';
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
    $hours = $rec->hoursWorked;
    $materials = $rec->materialsUsed;
    $cost = '$'.$rec->materialCost;
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

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Job Approval</title>
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
          <td>Deadline</td>
          <td><input type="text" value="<?php echo $deadline ?>" disabled /></td>
        </tr>
        <tr>
          <td>Description</td>
          <td><textarea rows="<?php echo $row_size ?>" cols="40" disabled><?php echo $description ?></textarea></td>
        </tr>
        <tr><td>Date:</td><td>
        <select id="year" name="year"></select>
        <select id="month" name="month">
          <option id="month_0" value="January">January</option>
          <option id="month_1" value="February">February</option>
          <option id="month_2" value="March">March</option>
          <option id="month_3" value="April">April</option>
          <option id="month_4" value="May">May</option>
          <option id="month_5" value="June">June</option>
          <option id="month_6" value="July">July</option>
          <option id="month_7" value="August">August</option>
          <option id="month_8" value="September">September</option>
          <option id="month_9" value="October">October</option>
          <option id="month_10" value="November">November</option>
          <option id="month_11" value="December">December</option>
        </select>
        <select id="day" name="day"></select></td></tr>
        <tr><td>Number of hours:</td><td><input name="hours" id="hours" type="text" value="<?php echo $hours ?>"/></td></tr>
        <tr><td>Materials used:</td><td><textarea name="materials" id="materials" rows="3" cols="40"><?php echo $materials ?></textarea></td></tr>
        <tr><td>Cost of materials:</td><td><input name="cost" id="cost" type="text" value="<?php echo $cost ?>" /></td></tr>
      </table>
      <input type="submit" value="Submit" />
    </div>  
  </form>
  <?php show_list('Records', $id); ?>
  <?php show_list('Jobs', $id, array('assigned_to_user' => true)); ?>
</body>
</html>
<?php } ?>
