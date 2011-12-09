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
    'contactNumber' => 'varchar',
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
  public $contactNumber;

  public static $approved = array(
    -1 => 'Denied',
     0 => 'Under Review',
     1 => 'Approved',
  );
  
  public function approved($i) {
    return self::$approved[$i];
  }
  
  /**
   * Takes $_POST and inserts it into the Jobs table
   */
  public function insert_job($post) {
    $args = array(
      $_COOKIE['uid'],
      $post['description'],
      parse_date($post),
      str_replace('-', '', $post['phone']),
    );
    $this->call_procedure('insert_job', $args);
  }
  
  /**
   * Takes $_POST and updates it into the Jobs table
   */
  public function update_job($post) {
    $args = array(
      $_GET['id'],
      $post['description'],
      $post['dueDate'],
      $post['contactNumber'],
      $post['assignedUserId'],
      $post['status'],
      $post['costEstimated'],
      $post['hoursEstimated'],
      $post['dateEstimated'],
      $post['completed'],
    );
    $this->call_procedure('update_job', $args);
  }
  
}

?>
