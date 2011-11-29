<?php

require('Request.php');

function show_list() {
  
  $req = new Request();
  
  $req->find(false);
  
  show_header();
  
  for ($i=1; $req->rows(); $i++) {
    echo_row($req, ($i%2 == 0) ? 'even' : 'odd');
  }
  
  echo "</table>";
  
}

// Function start: show_header
function show_header() { ?>
<table class="list">
<tr>
  <th>Name</th>
  <th>Phone</th>
  <th>Deadline</th>
  <th>Description</th>
  <th>Approved</th>
  <th>Edit</th>
</tr>
<?php }
// Function end: show_header

function echo_row($req, $even_or_odd) {
  if (strlen($req->description) > 23) {
    $description = substr($req->description, 0, 20)."...";
  } else {
    $description = $req->description;
  }
  $approved = ($req->approved) ? 'Yes' : 'No';
  echo "<tr class='$even_or_odd'><td>{$req->name}</td>" .
           "<td>{$req->phone}</td>" .
           "<td>{$req->deadline}</td>" .
           "<td>$description</td>" .
           "<td>$approved</td>" .
           "<td><a href='{$_SERVER['PHP_SELF']}?id={$req->id}'>Edit</a></td></tr>";
}

?>