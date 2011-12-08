<?php

require_once('DataObject.php');

class Users extends DataObject {

  public $username;
  public $password;
  public $fullName;
  public $phone;
  public $accessRights;
  public $userId;
  
  public $table = 'Users';
  
  public $fields = array(
    'username' => 'varchar',
    'password' => 'varchar',
    'fullName' => 'varchar',
    'phone' => 'varchar',
    'accessRights' => 'smallint',
    'userId' => 'tinyint',
  );

  public function get_uid() {
    return $this->uid;
  }

  public function get_privileges() {
    return $this->privileges;
  }
  
  public function check_user($user, $pass) {
    // True means the value should be qutoed
    // False means the value should not be quoted
    $args = array($user, $pass);
    $uid = $this->call_function('check_user', $args);
    return array_shift($uid);
  }
  
  public function check_perm($user) {
    $args = array($user);
    $permission = $this->call_function('check_permission', $args);
    return array_shift($permission);
  }
  
  public function user_name($username) {
    $args = array($username);
    $fullname = $this->call_function('user_name', $args);
    return array_shift($fullname);
  }
  
  public function user_phone($username) {
    $args = array($username);
    $phone = $this->call_function('user_phone', $args);
    return array_shift($phone);
  }
  
}

?>
