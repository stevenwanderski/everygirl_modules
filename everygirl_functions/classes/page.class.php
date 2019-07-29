<?php

class Page extends Node {

  public function __construct($node){
    parent::__construct($node);
    $this->list_title = $this->list_title();
    $this->list_items = $this->list_items();
  }

  public function list_title(){
    if(!isset($this->node->field_list_title['und'][0])){
      return '';
    }
    return $this->node->field_list_title['und'][0]['value'];
  }

  public function list_items(){
    $list_items = array();
    if(empty($this->node->field_list_items)) return $list_items;
    foreach($this->node->field_list_items['und'] as $list_item){
      $entity = entity_load('field_collection_item', array($list_item['value']));
      $list_items[] = new ListItem(array_shift($entity));
    }
    return $list_items;
  }

}

?>