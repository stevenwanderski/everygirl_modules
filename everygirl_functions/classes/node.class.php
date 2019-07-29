<?php

class Node {

  public $node;

  public function __construct($node){
    $this->node = $node;
    $this->title = $this->title();
    $this->path = $this->path();
    $this->body = $this->body();
    $this->teaser = $this->teaser();
    $this->post_date = $this->post_date();
    $this->category = $this->category();
    $this->category_path = $this->category_path();
  }

  public function title(){
    return $this->node->title;
  }

  public function path(){
    return '/' . drupal_get_path_alias('node/' . $this->node->nid);
  }

  public function body(){
    if(!isset($this->node->field_body['und'][0])){
      return '';
    }
    return $this->node->field_body['und'][0]['value'];
  }

  public function teaser(){
    if(!isset($this->node->field_body['und'][0])){
      return '';
    }
    return trim_text($this->node->field_body['und'][0]['value'], 120);
  }

  public function category(){
    if(!isset($this->node->field_primary_category['und'][0])){
      return '';
    }
    $this->term = taxonomy_term_load($this->node->field_primary_category['und'][0]['tid']);
    return $this->term->name;
  }

  public function category_path(){
    if(!property_exists($this, 'term')) return;
    return '/' . drupal_get_path_alias('taxonomy/term/' . $this->term->tid);
  }

  public function post_date($format = 'F d, Y'){
    return date($format, $this->node->created);
  }

  public function featured_image($image_style){
    if(!isset($this->node->field_featured_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->node->field_featured_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL,
      'alt' => $this->node->title . ' #theeverygirl'
    ));
  }

  public function featured_image_url($image_style){
    if(!isset($this->node->field_featured_image['und'][0])){
      return '';
    }
    return image_style_url($image_style, $this->node->field_featured_image['und'][0]['uri']);
  }

}

?>