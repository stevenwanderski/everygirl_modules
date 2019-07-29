<?php

class ListItem {

  public function __construct($field_collection_item){
    $this->field_collection_item = $field_collection_item;
    $this->header = $this->header();
    $this->content = $this->content();
  }

  public function header(){
    if(!isset($this->field_collection_item->field_header['und'][0])){
      return '';
    }
    return $this->field_collection_item->field_header['und'][0]['value'];
  }

  public function content(){
    if(!isset($this->field_collection_item->field_content['und'][0])){
      return '';
    }
    return $this->field_collection_item->field_content['und'][0]['value'];
  }

}

?>