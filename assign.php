<?php
require('check_cookie.php');
require('show_list.php');
if (check_cookie($_SERVER['PHP_SELF'], 2)) { ?>
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
  <a href="record.php">Record</a>
  <a href="request.php">Request</a>
  <a href="logout.php">Logout</a>
  <h1>Job Approval Form</h1>
  <form id="form" method="post" action="assign.php">
    <div class="form">
      <table border="0">
      <tr><td>Requested by:</td><td><input type="text" disabled id="rq_name" /></td></tr>
      <tr><td>Phone #:</td><td><input disabled type="text" id="rq_phone" /></td></tr>
      <tr><td>Deadline:</td><td>
        <input disabled type="select" id="rq_year" />
        <input disabled type="select" id="rq_month" />
        <input disabled type="select" id="rq_day" />
      <tr><td>Description:</td><td><textarea disabled id="rq_description" rows="10" cols="40"></textarea></td></tr>
      <tr><td>Estimated number of hours to complete:</td><td><input id="hours" type="text" /></td></tr>
	    <tr><td>Estimated total cost:</td><td><input id="cost" type="text" /></td></tr>
		<tr><td>Estimated date of completion:</td><td>
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
		<tr><td>Assign to:</td><td><select id="assign_to"></select></td></tr>
        <tr><td>Approved? </td><td><input type="checkbox" id="approved" /></td></tr>
      </table>
      <input type="submit" value="Submit" />
    </div>  
  </form>
  <div>
    <?php show_list('assign'); ?>
  </div>
</body>
</html>
<?php } ?>
