<?php

require_once('dataobject.inc.php');

class DataObject {
  
  private $link;
  private $result;
  
  protected function db_connect() {
    $this->link = msqyl_connect(
      $database['host'],
      $database['user'],
      $database['password']
    ) or die('Could not connect: ' . mysql_error());
    
    mysql_select_db($database['db_name'])
      or die('Could not select database: ' . mysql_error());

    
  }

  public function get_db_link() {
    return $this->link;
  }

  public function query($query) {
    $this->db_connect();
    $this->set_result(mysql_query($query));
      or die('Query failed: ' . mysql_error());
    $this->set_vals(mysql_fetch_assoc($this->get_result()));
  }
  
  protected function set_vals($values=array()) {
    foreach ($values as $key => $value) {
      $this->$key = $value;
    }
  }
  
  protected function get_result() {
    return $this->result;
  }

  protected function set_result($value) {
    $this->result = $value;
  }
  
  public function rows() {
    $row = mysql_fetch_assoc($this->get_result());
    if ($row) {
      $this->set_vals($row);
    }
    return $row;
  }
  
}

?>
