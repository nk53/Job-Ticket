<?php
/**
 * @file Users.php
 * 
 * This file defines the class that interfaces with the Users table.
 */

require_once('DataObject.php');

class Users extends DataObject {

  public $username;
  public $password;
  public $fullName;
  public $phone;
  public $accessRights;
  public $userId;
  
  public $primary = 'userId';
  
  protected $table = 'Users';
  
  protected $fields = array(
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
  
  public function check_perm($uid) {
    $args = array($uid);
    $permission = $this->call_function('check_permission', $args);
    return array_shift($permission);
  }
  
  public function user_name($uid) {
    $args = array($uid);
    $fullname = $this->call_function('user_name', $args);
    return array_shift($fullname);
  }
  
  public function user_phone($uid) {
    $args = array($uid);
    $phone = $this->call_function('user_phone', $args);
    return array_shift($phone);
  }
  
}

?>
