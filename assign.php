<?php
require_once('check_cookie.php');
require_once('show_list.php');
require_once('Jobs.php');
require_once('Users.php');

if (check_cookie($_SERVER['PHP_SELF'], 3)) {
  $row_size = '1';
  if (!empty($_POST)) {
    // Update the 'approved' property
    $job = new Jobs();
    $approved = empty($_POST['approved']) ? 'false' : 'true';
    $query = 'UPDATE request SET approved=' . $approved .
             ' WHERE id=' . $_POST['rid'];
    $req->query($query);
    
    // What's the id of the person this job is assigned to?
    $user = new Users();
    $user->fullname = $_POST['assign_to'];
    $user->find();
    $aid = $user->uid;
    
    // Insert the new assignment
    $asn = new Assign();
    $asn->rid = $_POST['rid'];
    $asn->hours = $_POST['hours'];
    $asn->cost = str_replace('$', '', $_POST['cost']);
    $asn->complete = parse_date($_POST);
    $asn->aid = $aid;
    $asn->insert();
  
    header('Location: index.php');
  }
  // Initialize values!
  $name = '';
  $phone = '';
  $year = '';
  $month = '';
  $day = '';
  $desc = '';
  $checked = '';
  $est_date = '';
  $id = (isset($_GET['id'])) ? $_GET['id'] : null;
  if (strlen($id)) {
    $job = new Jobs();
    $job->jobId = $id;
    $job->find();
    
    $user = new Users();
    $user->get($job->userId);
  
    $name = $user->fullName;
    $phone = parse_phone($user->phone);
    $y = substr($job->dueDate, 0, 4);
    $m = substr($job->dueDate, 5, 2);
    $d = substr($job->dueDate, 8, 2);
    $time = strtotime("$y/$m/$d");
    $year = '<option>'.date('Y', $time).'</option>';
    $month = '<option>'.date('F', $time).'</option>';
    $day = '<option>'.date('j', $time).'</option>';
    $desc = $job->description;
    $status = $job->status;
    if (strtotime($job->dateEstimate)) {
      $est_date = date_option($job->dateEstimate);
    }
    //update size of textarea to fit description
    $row_size = 1 + strlen($desc) / 40;
  }
  if (!$est_date) {
    $est_date = date_option(date('Y-m-d'));
  }
?>
<!DOCTYPE html>
<head>
<title>Job Approval</title>
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
<link rel="stylesheet" type="text/css" href="style.css" />
<script type="text/javascript" src="jquery-1.7.min.js"></script>
<script type="text/javascript" src="validator.js"></script>
<script type="text/javascript" src="validate_assign.js"></script>
</head>
<body onload="loadEventHandlers();">
  <a href="index.php">Home</a>
  <a href="history.php">History</a>
  <a href="record.php">Record</a>
  <a href="request.php">Request</a>
  <a href="logout.php">Logout</a>
  <h1>Job Approval Form</h1>
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
        <td><textarea disabled rows="<?php echo $row_size ?>" cols="40"><?php echo $desc ?></textarea></td>
        </tr>
      <tr>
        <td>Estimated number of hours to complete:</td>
        <td><input id="hours" name="hours" type="text" /></td>
      </tr>
      <tr>
        <td>Estimated total cost:</td>
        <td><input id="cost" name="cost" type="text" /></td>
      </tr>
      <tr>
        <td>Estimated date of completion:</td>
        <td>
          <?php echo $est_date ?>
        </td>
      </tr>
      <tr>
        <td>Assign to:</td>
        <td><select name="assignedUserId" id="assign_to"><?php echo assign_to_option() ?></select></td>
      </tr>
      <tr>
          <td>Approved? </td>
          <td>
            <!--<input name="approved" type="checkbox" id="approved" <?php echo $checked ?> />-->
            <select>
              <option<?php echo ($job->status == 1) ? ' selected="selected"' : '' ?>>Approve</option>
              <option<?php echo ($job->status == 0) ? ' selected="selected"' : '' ?>>Review Later</option>
              <option<?php echo ($job->status == -1) ? ' selected="selected"' : '' ?>>Deny</option>
            </select>
          </td>
        </tr>
      </table>
      <input type="submit" value="Submit" />
    </div>  
  </form>
  <?php show_list('Jobs', $id); ?>
</body>
</html>
<?php } ?>
