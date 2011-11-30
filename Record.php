<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Record extends DataObject {
  
  protected $fields = array(
    'id' => 'int',
    'aid' => 'int',
    'uid' => 'int',
    'date' => 'date',
    'hours' => 'decimal',
    'materials' => 'text',
    'cost' => 'decimal',
  );
  protected $table = 'record';
  
  public $id;
  public $aid;
  public $uid;
  public $date;
  public $materials;
  public $cost;
  
}

?>
