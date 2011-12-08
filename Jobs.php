<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Jobs extends DataObject {
  
  protected $fields = array(
    'jobId' => 'int',
    'userId' => 'int',
    'description' => 'text',
    'dueDate' => 'date',
    'status' => 'tinyint',
    'costEstimate' => 'decimal',
    'hoursEstimate' => 'decimal',
    'dateEstimate' => 'date',
    'completed' => 'tinyint',
  );
  protected $table = 'Jobs';
  
  public $primary = 'jobId';
  
  public $jobId;
  public $userId;
  public $description;
  public $dueDate;
  public $status;
  public $costEstimate;
  public $hoursEstimate;
  public $dateEstimate;
  public $completed;

  public static $approved = array(
    -1 => 'Denied',
     0 => 'Under Review',
     1 => 'Approved',
  );
  
  public function approved($i) {
    return self::$approved[$i];
  }
  
}

?>
