<?php
if ($_GET['item'] == 'year') {
  for ($i=0; $i<5; $i++) {
    $date = date('Y')+$i; ?>
<option value="<?php echo $date ?>"><?php echo $date ?></option>
<?php } // End for loop
} // End if-statement

else if($_GET['item'] == 'day') {
  $date = date('t');
  if (isset($_GET['month']) && isset($_GET['year'])) {
    $year = $_GET['year'];
    $month = $_GET['month'];
    $date = date('t',strtotime("$year $month"));
  }
  for ($i=1; $i<=$date; $i++) { ?>
<option id="day_<?php echo $i?>"value="<?php echo $i;?>"><?php echo $i; ?></option>
<?php  } // End for loop
} // End if-statement
