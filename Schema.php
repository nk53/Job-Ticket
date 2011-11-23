<?php

/**
 * Parses schema.xml
 */
class Schema {
  
  private $xml = null;
  public $fields = array();
  
  private function __construct() {
    
  }

  public function init($location) {
    $xml->load($location);
  }
  
  public function parse() {

  }
  
}

?>
