<?php

require_once('DataObject.php');

class Users extends DataObject {

  public $uid;
  public $privileges;
  public $name;
  public $fullname;
  public $password;
  public $phone;
  
  public $table = 'users';
  
  public $fields = array(
    'uid' => 'int',
    'privileges' => 'int',
    'name' => 'text',
    'fullname' => 'text',
    'password' => 'text',
    'phone' => 'text',
  );

  public function get_uid() {
    return $this->uid;
  }

  public function get_privileges() {
    return $this->privileges;
  }
  
}

?>
