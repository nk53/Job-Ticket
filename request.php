<?php
require_once('Jobs.php');
require_once('Records.php');
require_once('Users.php');
require_once('check_cookie.php');
require_once('show_list.php');
if (check_cookie($_SERVER['PHP_SELF'], 1)) {
  $user = new Users();
  $user->userId = $_COOKIE['uid'];
  $user->find();
  $name = $user->fullName;
  $phone = parse_phone($user->phone);
  if (!empty($_POST)) {
    $job = new Jobs();
    $job->insert_job($_POST);
  }
  $rec = new Records();
  $actual = $rec->get_actual($job->jobId);
  $act_hours = $actual['hours'];
  $act_cost = $actual['cost'];
  $options = array(
    'show_spending' => true,
    'edit' => false,
    'limit_to_user' => true
  );
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Job Request Form</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type="text/javascript" src="validator.js"></script>
<script type="text/javascript" src="validate_request.js"></script>
</head>
<body onload="loadEventHandlers();">
<?php include('header.php'); ?>
<a href="logout.php">Logout</a>
<form id="form" method="post" action="request.php">
<div class="form">
  <h1>Job Request Form</h1>
  <table border="0">
    <tr><td>Name:</td>
      <td><input type="text" id="name" name="name" readonly="readonly" value="<?php echo $name ?>" /></td>
    </tr>
    <tr><td>Phone #:</td>
      <td><input type="text" id="phone" name="phone" value="<?php echo $phone ?>" /></td>
    </tr>
    <tr><td>Job Deadline:</td><td><select id="year" name="year"></select>
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
    <tr><td>Job Description:</td>
      <td>
        <textarea id="description" name="description" rows="10" cols="40"></textarea>
      </td>
    </tr>
  </table>
  <input type="submit" value="Submit" />
</div>  
</form>
<?php show_list('Jobs', NO_HIGHLIGHT, $options); ?>
</body>
</html>
<?php } ?>
