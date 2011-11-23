<?php

require_once('DataObject.php');

class Users extends DataObject {
  
  private $uid;
  private $privileges;

  public $name;
  public $fullname;
  public $password;
  public $phone;

  public function get_uid() {
    return $this->uid;
  }

  public function get_privileges() {
    return $this->privileges;
  }
  
}

?>
