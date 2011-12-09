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
  
}

?>
