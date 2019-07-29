<?php

class Contributor extends Node {

  public function __construct($node){
    parent::__construct($node);
    $this->job_position = $this->job_position();
    $this->facebook = $this->facebook();
    $this->twitter = $this->twitter();
    $this->instagram = $this->instagram();
    $this->email = $this->email();
    $this->website = $this->website();
  }

  public function job_position(){
    if(empty($this->node->field_job_position)){
      return '';
    }
    return $this->node->field_job_position['und'][0]['value'];
  }

  public function facebook(){
    if(empty($this->node->field_facebook)){
      return '';
    }
    return $this->node->field_facebook['und'][0]['value'];
  }

  public function twitter(){
    if(empty($this->node->field_twitter)){
      return '';
    }
    return $this->node->field_twitter['und'][0]['value'];
  }

  public function instagram(){
    if(empty($this->node->field_instagram)){
      return '';
    }
    return $this->node->field_instagram['und'][0]['value'];
  }

  public function email(){
    if(empty($this->node->field_email)){
      return '';
    }
    return $this->node->field_email['und'][0]['value'];
  }

  public function website(){
    if(empty($this->node->field_website)){
      return '';
    }
    return $this->node->field_website['und'][0]['value'];
  }

  public function contains_social(){
    return $this->facebook || $this->twitter || $this->email || $this->instagram || $this->website;
  }


}

?>