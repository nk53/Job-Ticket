<?php
require_once('check_cookie.php');
require_once('show_list.php');
require_once('Jobs.php');
require_once('Assign.php');
require_once('Users.php');

if (check_cookie($_SERVER['PHP_SELF'], 3)) {
if (!empty($_POST)) {
  // Update the 'approved' property
  $req = new Jobs();
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
$est_hours = '';
$act_hours = '';
$est_cost = '';
$act_cost = '';
$checked = '';
$id = (isset($_GET['id'])) ? $_GET['id'] : null;
if ($id) {
  // Get request information
  $req = new Request();
  $req->id = $id;
  $req->find();
  $name = $req->name;
  $phone = parse_phone($req->phone);
  $y = substr($req->deadline, 0, 4);
  $m = substr($req->deadline, 5, 2);
  $d = substr($req->deadline, 8, 2);
  $time = strtotime("$y/$m/$d");
  $year = '<option>'.date('Y', $time).'</option>';
  $month = '<option>'.date('F', $time).'</option>';
  $day = '<option>'.date('j', $time).'</option>';
  $desc = $req->description;
  $checked = ($req->approved) ? 'checked="checked"' : '';
  // Get estimate information
  $asn = new Assign();
  $asn->rid=$id;
  $asn->find();
  $est_hours = $asn->hours;
  $est_cost = $asn->cost;
  // Get actual spending information
  $rec = new Record();
  // FIX THIS!!!
  $rec->uid = $asn->rid;
  $rec->find(false);
  // Sum spending information together
  while ($rec->rows()) {
    $act_hours += $rec->hours;
    $act_cost += $rec->cost;
  }
}
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
        <td><textarea disabled rows="10" cols="40"><?php echo $desc ?>
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
        <td>
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
          <select id="day" name="day"></select>
        </td>
      </tr>
      <tr>
        <td>Assign to:</td>
        <td><select name="assign_to" id="assign_to"></select></td>
      </tr>
      <tr>
          <td>Approved? </td>
          <td>
            <input name="approved" type="checkbox" id="approved" <?php echo $checked ?> />
          </td>
        </tr>
      </table>
      <input type="button" value="Prev" id="prev" />
      <input type="submit" value="Submit" />
      <input type="button" value="Next" id="next" />
    </div>  
  </form>
  <?php show_list('history', $id); ?>
</body>
</html>
<?php } ?>
