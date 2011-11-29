<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Assign extends DataObject {
  
  protected $fields = array(
    'id' => 'int',
    'rid' => 'int',
    'hours' => 'int',
    'cost' => 'decimal',
    'complete' => 'date',
    'aid' => 'int',
  );
  protected $table = 'assign';
  
  public $id;
  public $rid;
  public $hours;
  public $cost;
  public $complete;
  public $aid;
  
}

?>
