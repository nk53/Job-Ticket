<?php

require_once('dataobject.inc.php');

class DataObject {
  
  private $link;
  private $quote = array(
    'date' => true,
    'int' => false,
    'text' => true,
    'varchar' => true,
    'bool' => false,
  );
  
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

  public function query($query, $fetch_row=true) {
    $this->db_connect();
    $result = mysql_query($query)
      or die('Query failed: ' . mysql_error());
    if (strstr($query, 'SELECT')) {
      $this->set_result($result);
      if ($fetch_row) {
        $this->set_vals(mysql_fetch_assoc($result));
      }
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
  }
  
  public function insert() {
    $query = "INSERT INTO $this->table ";
    $col_names = '';
    $col_vals = '';
    foreach ($this->fields as $field => $type) {
      if (!is_null($this->$field)) {
        $col_names .= "$field, ";
        if ($this->quote[$type]) {
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
  
  public function find($fetch_row=true) {
    $query = "SELECT * FROM {$this->table}";
    $where = '';
    foreach ($this->fields as $field => $type) {
      if (!is_null($this->$field)) {
        if ($this->quote[$type]) {
          $where .= "$field = '{$this->$field}' AND ";
        } else {
          $where .= "$field = {$this->$field} AND ";
        }
      }
    }
    if (!empty($where)) {
      // Remove trailing "AND "
      $where = substr($where, 0, -4);
    }
    $query .= $where;
    $this->query($query, $fetch_row);
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
