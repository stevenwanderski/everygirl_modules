<?php

class Press extends Node {

  public function __construct($node){
    parent::__construct($node);
    $this->url = $this->url();
  }

  public function url(){
    if(!isset($this->node->field_website['und'][0])){
      return '';
    }
    return $this->node->field_website['und'][0]['value'];
  }
}

?>