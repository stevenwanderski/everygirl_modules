<?php

class Job {

  public $node;

  public function __construct($node){
    $this->node = $node;
    $this->title = $this->title();
    $this->body = $this->body();
    $this->path = $this->path();
    $this->post_date = $this->post_date();
    $this->date = $this->date();
    $this->company = $this->company();
    $this->company_url = $this->company_url();
    $this->city = $this->city();
    $this->state = $this->state();
    $this->location = $this->location();
    $this->position = $this->position();
    $this->compensation = $this->compensation();
    $this->address = $this->address();
    $this->hours = $this->hours();
    $this->who_we_are = $this->who_we_are();
    $this->responsibilities = $this->responsibilities();
    $this->requirements = $this->requirements();
    $this->apply = $this->apply();
    $this->has_company_info = $this->has_company_info();
  }

  public function title(){
    return $this->node->title;
  }

  public function body(){
    if(!isset($this->node->field_legacy_body['und'][0])){
      return '';
    }
    return $this->node->field_legacy_body['und'][0]['value'];
  }

  public function path(){
    return '/' . drupal_get_path_alias('node/' . $this->node->nid);
  }

  public function post_date($format = 'F d, Y'){
    return date($format, $this->node->created);
  }

  public function date($format = 'F d, Y'){
    return date($format, $this->node->field_date['und'][0]['value']);
  }

  public function featured_image($image_style){
    if(!isset($this->node->field_featured_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->node->field_featured_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL
    ));
  }

  public function featured_image_url($image_style){
    if(!isset($this->node->field_featured_image['und'][0])){
      return '';
    }
    return image_style_url($image_style, $this->node->field_featured_image['und'][0]['uri']);
  }

  public function company_image($image_style){
    if(!isset($this->node->field_company_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->node->field_company_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL
    ));
  }

  public function company(){
    if(!isset($this->node->field_company['und'][0])){
      return '';
    }
    return $this->node->field_company['und'][0]['value'];
  }

  public function company_url(){
    if(!isset($this->node->field_company_url['und'][0])){
      return '';
    }
    return $this->node->field_company_url['und'][0]['value'];
  }

  public function city(){
    // if(!empty($this->node->field_city_term)){
    //   $term = taxonomy_term_load($this->node->field_city_term['und'][0]['tid']);
    //   return $term->name;
    // }
    if(!isset($this->node->field_city['und'][0])){
      return '';
    }
    return $this->node->field_city['und'][0]['value'];
  }

  public function state(){
    if(!isset($this->node->field_state['und'][0])){
      return '';
    }
    return $this->node->field_state['und'][0]['value'];
  }

  public function location(){
    $output = '';
    $output .= ($this->city) ? $this->city . ', ' : '';
    $output .= ($this->state) ? $this->state : '';
    return $output;
  }

  public function position(){
    if(!isset($this->node->field_position['und'][0])){
      return '';
    }
    return $this->node->field_position['und'][0]['value'];
  }

  public function compensation(){
    if(!isset($this->node->field_compensation['und'][0])){
      return '';
    }
    return $this->node->field_compensation['und'][0]['value'];
  }

  public function address(){
    if(!isset($this->node->field_address['und'][0])){
      return '';
    }
    return $this->node->field_address['und'][0]['value'];
  }

  public function hours(){
    if(!isset($this->node->field_hours['und'][0])){
      return '';
    }
    return $this->node->field_hours['und'][0]['value'];
  }

  public function who_we_are(){
    if(!isset($this->node->field_who_we_are['und'][0])){
      return '';
    }
    return $this->node->field_who_we_are['und'][0]['value'];
  }

  public function responsibilities(){
    if(!isset($this->node->field_responsibilities['und'][0])){
      return '';
    }
    return $this->node->field_responsibilities['und'][0]['value'];
  }

  public function requirements(){
    if(!isset($this->node->field_requirements['und'][0])){
      return '';
    }
    return $this->node->field_requirements['und'][0]['value'];
  }

  public function apply(){
    if(!isset($this->node->field_apply['und'][0])){
      return '';
    }
    return $this->node->field_apply['und'][0]['value'];
  }

  public function has_company_info(){
    return !empty($this->node->field_company_image) || !empty($this->node->field_company_url);
  }

}

?>