<?php

// Include information about the database and the generic data object.
require_once('dataobject.inc.php');

class Workers extends DataObject {
  
  protected $fields = array(
    'userId' => 'int',
  );
  protected $table = 'Workers';
  
  public $primary = 'userId';
  
  public $userId;
  
}

?>
