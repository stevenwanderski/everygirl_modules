<?php

class Category {

  public $term;

  public function __construct($term){
    $this->term = $term;
    $this->name = $this->name();
    $this->path = $this->path();
  }

  public function name(){
    return $this->term->name;
  }

  public function path(){
    $term_uri = taxonomy_term_uri($this->term);
    if(!empty($term_uri['path'])){
      return '/' . drupal_get_path_alias($term_uri['path']);
    }
    return '';
  }

  public function featured_image($image_style){
    if(!isset($this->term->field_featured_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->term->field_featured_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL
    ));
  }

}

?>