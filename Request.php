<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Request extends DataObject {
  
  protected $fields = array(
    'id' => 'int',
    'name' => 'varchar',
    'phone' => 'int',
    'deadline' => 'date',
    'description' => 'text',
    'approved' => 'bool',
  );
  protected $table = 'request';
  
  public $id;
  public $name;
  public $phone;
  public $deadline;
  public $description;
  public $approved;
  
  public function get_id() {
    return $this->id;
  }
  
}

?>
