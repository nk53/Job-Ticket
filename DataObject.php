<?php

require_once('dataobject.inc.php');

class DataObject {
  
  private $link;
  /*private $quote = array(
    'date' => true,
    'int' => false,
    'smallint' => false,
    'tinyint' => false,
    'decimal' => false,
    'text' => true,
    'varchar' => true,
    'bool' => false,
  );*/
  
  protected $result; 
  protected $fields;
  protected $table;
  protected $limit;
  protected $order_by;
  
  public $primary; // Primary Key
  
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
    //echo "<pre>$query</pre><br />";
    $this->db_connect();
    if (isset($this->order_by) && !empty ($this->order_by)) {
      $query .= ' ORDER BY ' . $this->order_by;
    }
    if (isset($this->limit) && !empty ($this->limit)) {
      $query .= ' LIMIT ' . $this->limit;
    }
    $result = mysql_query($query)
      or die('Query failed: ' . mysql_error());
    if (strstr($query, 'SELECT')) {
      $this->set_result($result);
      if ($fetch_row) {
        $this->set_vals(mysql_fetch_assoc($result));
      }
    }
  }
  
  public function call_function($func, $args=array()) {
    $this->db_connect();
    $query = "SELECT $func(";
    foreach ($args as $arg) {
      $query .= "'$arg', ";
    }
    if (!empty($args)) {
      $query = substr($query, 0, -2);
    }
    $query .= ")";
    $result = mysql_query($query)
      or die('Query failed: ' . mysql_error());
    return mysql_fetch_row($result);
  }
  
  public function call_procedure($proc, $args=array()) {
    $this->db_connect();
    $query = "CALL $proc(";
    foreach ($args as $arg) {
      $query .= "'$arg', ";
    }
    if (!empty($args)) {
      $query = substr($query, 0, -2);
    }
    $query .= ")";
    $result = mysql_query($query)
      or die('Query failed: ' . mysql_error());
  }
  
  protected function set_vals($values=array()) {
    if (!empty($values)) {
      foreach ($values as $key => $value) {
        $this->$key = $value;
      }
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
        $col_vals .= '"' . $this->$field . '", ';
      }
    }
    // Remove trailing ", "
    $col_names = substr($col_names, 0, -2);
    $col_vals = substr($col_vals, 0, -2);
    $query .= "($col_names) VALUES ($col_vals);";
    $this->query($query);
  }
  
  /*public function update() {
    $query = "UPDATE $this->table SET ";
    $cols = '';
    foreach ($this->fields as $field => $type) {
      if (!is_null($this->$field)) {
        $cols .= "$field=";
        if ($this->quote[$type]) {
          $cols .= '"' . $this->$field . '", ';
        } else {
          $cols .= $this->$field . ', ';
        }
      }
    }
    // Remove trailing ", "
    $cols = substr($cols, 0, -2);
    $query .= $cols;
    $this->query($query);
  }*/
  
  public function get($id) {
    $primary = $this->primary;
    $this->$primary = $id;
    $this->find();
  }
  
  public function find($fetch_row=true) {
    $query = "SELECT * FROM {$this->table}";
    $where = '';
    foreach ($this->fields as $field => $type) {
      if (!is_null($this->$field)) {
        if ($this->quote[$type]) {
          $where .= "$field='{$this->$field}' AND ";
        } else {
          $where .= "$field={$this->$field} AND ";
        }
      }
    }
    if (!empty($where)) {
      // Remove trailing "AND "
      $where = substr($where, 0, -5);
      $query .= ' WHERE ' . $where;
    }
    $this->query($query, $fetch_row);
  }
  
  public function limit($lim) {
    $this->limit = $lim;
  }
  
  public function order_by($ob) {
    $this->order_by = $ob;
  }
  
  public function rows() {
    if (!is_null($this->get_result())) {
      $row = mysql_fetch_assoc($this->get_result());
    }
    if ($row) {
      $this->set_vals($row);
    }
    return $row;
  }
  
}

?>
