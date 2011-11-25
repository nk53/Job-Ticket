<?php

require_once('dataobject.inc.php');

class DataObject {
  
  private $link;
  protected $result;
  
  protected $fields;
  protected $table;
  
  protected function db_connect() {
    $database = parse_ini_file("db.ini", true);
    $database = $database['Database'];
    $this->link = mysql_connect(
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
    $result = mysql_query($query);
    if (strstr($query, 'SELECT')) {
      $this->set_result(mysql_query($query))
        or die('Query failed: ' . mysql_error());
      $this->set_vals(mysql_fetch_assoc($this->get_result()));
    }
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
    echo $this->result;
  }
  
  public function insert() {
    $quote = array(
      'date' => true,
      'int' => false,
      'text' => true,
      'varchar' => true,
      'bool' => false,
    );
    $query = "INSERT INTO $this->table ";
    $col_names = '';
    $col_vals = '';
    foreach ($this->fields as $field => $type) {
      if (!is_null($this->$field)) {
        $col_names .= "$field, ";
        if ($quote[$type]) {
          $col_vals .= '"' . $this->$field . '", ';
        } else {
          $col_vals .= $this->$field . ', ';
        }
      }
    }
    // Remove trailing ", "
    $col_names = substr($col_names, 0, -2);
    $col_vals = substr($col_vals, 0, -2);
    $query .= "($col_names) VALUES ($col_vals);";
    $this->query($query);
  }
  
  public function find() {
    
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
