<?php
require_once('check_cookie.php');
require_once('show_list.php');
require_once('Jobs.php');
require_once('Users.php');

if (check_cookie($_SERVER['PHP_SELF'], 3)) {
  // Initialize values!
  $name = '';
  $phone = '';
  $year = '';
  $month = '';
  $day = '';
  $desc = '';
  $est_hours = '';
  $act_hours = '';
  $est_cost = '';
  $act_cost = '';
  $checked = '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : null;
  $row_size = 1;
  if (strlen($id)) {
    // Get request information
    $job = new Jobs();
    $job->get($id);
    $user = new Users();
    $name = $user->user_name($job->userId);
    $phone = parse_phone($job->contactNumber);
    $assigned_to = $user->user_name($job->assignedUserId);
    $y = substr($job->dueDate, 0, 4);
    $m = substr($job->dueDate, 5, 2);
    $d = substr($job->dueDate, 8, 2);
    $time = strtotime("$y/$m/$d");
    $year = '<option>'.date('Y', $time).'</option>';
    $month = '<option>'.date('F', $time).'</option>';
    $day = '<option>'.date('j', $time).'</option>';
    $dateEstimate = date_option($job->dateEstimate, true);
    $desc = $job->description;
    $status = $job->approved($job->status);
    // Get estimate information
    $est_hours = $job->hoursEstimate;
    $est_cost = '$'.$job->costEstimate;
    // Get actual spending information
    $rec = new Records();
    $actual = $rec->get_actual($job->jobId);
    $act_hours = $actual['hours'];
    $act_cost = $actual['cost'];
    $row_size = 1 + strlen($desc) / 40;
  }
  $options = array('view' => true, 'edit' => false, 'pending' => false);
?>
<!DOCTYPE html>
<head>
<title>Job History</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type="text/javascript" src="validator.js"></script>
<script type="text/javascript" src="validate_history.js"></script>
</head>
<body onload="loadEventHandlers();">
  <?php include('header.php'); ?>
  <a href="logout.php">Logout</a>
  <h1>View Job History</h1>
  <form id="form" method="post" action="<?php echo $_SERVER['PHP_SELF'] ?>">
    <input name="rid" type="hidden" value="<?php echo $id ?>" />
    <div class="form">
      <table border="0">
      <tr>
        <td>Requested by:</td>
        <td><input type="text" value="<?php echo $name ?>" disabled /></td>
      </tr>
      <tr>
        <td>Phone #:</td>
        <td><input type="text" value="<?php echo $phone ?>" disabled /></td>
      </tr>
      <tr>
        <td>Deadline:</td>
        <td>
          Year: <select disabled type="select"><?php echo $year ?></select>
          Month: <select disabled type="select"><?php echo $month ?></select>
          Day: <select disabled type="select"><?php echo $day ?></select>
        </td>
      </tr>
      <tr>
        <td>Description:</td>
        <td><textarea disabled rows="<?php echo $row_size ?>" cols="40"><?php echo $desc ?>
          </textarea></td>
        </tr>
      <tr>
        <td>Estimated number of hours to complete:
        <td><input id="hours" class="num" name="hours" type="text" value="<?php echo $est_hours ?>" disabled /></td>
      </tr>
      <tr>
        <td>Actual hours:</td>
        <td><input class="num" type="text" value="<?php echo $act_hours ?>" disabled /></td>
      </tr>
      <tr>
        <td>Estimated total cost:</td>
        <td><input id="cost" class="num" name="cost" type="text" value="<?php echo $est_cost ?>" disabled  /></td>
      </tr>
      <tr>
        <td>Total spent:</td>
        <td><input class="num" type="text" value="<?php echo $act_cost ?>" disabled /></td>
      </tr>
      <tr>
        <td>Estimated date of completion:</td>
        <td><?php echo $dateEstimate ?></td>
      </tr>
      <tr>
        <td>Assigned to:</td>
        <td><input type="text" name="assign_to" value="<?php echo $assigned_to ?>" disabled /></td>
      </tr>
      <tr>
          <td>Status:</td>
          <td>
            <input name="approved" type="text" id="status" value="<?php echo $status ?>" disabled />
          </td>
        </tr>
      </table>
      <input type="button" value="Prev" id="prev" />
      <input type="button" value="Next" id="next" />
    </div>  
  </form>
  <?php show_list('Jobs', $id, $options); ?>
</body>
</html>
<?php } ?>
