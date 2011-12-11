<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Records extends DataObject {
  
  protected $fields = array(
    'recordId' => 'int',
    'jobId' => 'int',
    'hoursWorked' => 'int',
    'materialCost' => 'decimal',
    'dateCompleted' => 'date',
  );
  protected $table = 'Records';
  
  public $primary = 'recordId';
  
  public $recordId;
  public $jobId;
  public $hoursWorked;
  public $materialCost;
  public $dateCompleted;
  
  public function get_actual($jobId) {
    $rec = new Records();
    $rec->jobId = $jobId;
    $rec->find(false);
    while ($rec->rows()) {
      $hours += $rec->hoursWorked;
      $cost += $rec->materialCost;
    }
    if (strlen($cost)) {
      $cost = '$'.$cost;
    }
    return array(
      'hours' => $hours,
      'cost' => $cost,
    );
  }

}

?>
