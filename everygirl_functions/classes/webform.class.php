<?php

class Webform extends Node {

  public function __construct($node){
    parent::__construct($node);
    $this->form = $this->form();
  }

  public function form(){
    webform_node_view($this->node, 'full');
    return theme_webform_view($this->node->content);
  }

}

?>