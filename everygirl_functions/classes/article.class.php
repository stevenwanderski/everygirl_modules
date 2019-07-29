<?php

class Article extends Node {

  public function __construct($node){
    parent::__construct($node);
    $this->subtitle = $this->subtitle();
    $this->photography_by = $this->photography_by();
    $this->photography_link = $this->photography_link();
    $this->styling_by = $this->styling_by();
    $this->styling_link = $this->styling_link();
    $this->copy_by = $this->copy_by();
    $this->copy_link = $this->copy_link();
    $this->addl_field_1 = $this->addl_field_1();
    $this->addl_url_1 = $this->addl_url_1();
    $this->addl_label_1 = $this->addl_label_1();
    $this->addl_field_2 = $this->addl_field_2();
    $this->addl_url_2 = $this->addl_url_2();
    $this->addl_label_2 = $this->addl_label_2();
    $this->addl_field_3 = $this->addl_field_3();
    $this->addl_url_3 = $this->addl_url_3();
    $this->addl_label_3 = $this->addl_label_3();
    $this->addl_field_4 = $this->addl_field_4();
    $this->addl_url_4 = $this->addl_url_4();
    $this->addl_label_4 = $this->addl_label_4();
    $this->contributors = $this->contributors();
    $this->credits = $this->credits();
    $this->layout = $this->layout();
    $this->header_image_style = $this->header_image_style();
    $this->date = $this->date();
  }

  public function date($format = 'F d, Y'){
    return date($format, $this->node->field_date['und'][0]['value']);
  }

  public function subtitle(){
    if(!isset($this->node->field_subtitle['und'][0])){
      return '';
    }
    return $this->node->field_subtitle['und'][0]['value'];
  }

  public function images($image_style){
    if(!isset($this->node->field_image['und'][0])){
      return $this->featured_image($image_style);
    }
    $slider_class = count($this->node->field_image['und']) > 1 ? 'slider' : '';
    $output = '<div class="image-wrap ' . $slider_class . '">';
    $maps = array();
    foreach ($this->node->field_image['und'] as $image) {

      // // check for a legacy imagemap
      // $map_id = '';
      // if(!empty($image['field_file_image_map'])){
      //   $map = $image['field_file_image_map']['und'][0]['value'];
      //   $dom = new DOMDocument();
      //   $dom->loadHTML($map);
      //   $items = $dom->getElementsByTagName('map');
      //   foreach($items as $item){
      //     $map_id = $item->getAttribute('id');
      //     $maps[$map_id] = $map;
      //   }
      // }

      $image_markup_array = array(
        'style_name' => $image_style,
        'path' => $image['uri'],
        'width' => NULL,
        'height' => NULL,
        'alt' => $this->node->title . ' #theeverygirl'
      );

      // if($map_id != ''){
      //   $image_markup_array['attributes'] = array(
      //     'usemap' => '#' . $map_id,
      //   );
      // }

      $image_markup = theme_image_style($image_markup_array);
      $output .= '<div class="image">' . $image_markup . '</div>';
    }
    $output .= '</div>';

    // foreach($maps as $map){
    //   $output .= $map;
    // }

    return $output;
  }

  public function first_image($image_style){
    if(!isset($this->node->field_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->node->field_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL
    ));
  }

  public function header_image_style(){
    if($this->layout == 'small') return 'article_small';
    return 'article_full';
  }

  public function contributors(){
    $output = '';
    if($this->photography_markup()){
      $output .= '<div class="item">' . $this->photography_markup() . '</div>';
    }
    if($this->styling_markup()){
      $output .= '<div class="item">' . $this->styling_markup() . '</div>';
    }
    if($this->copy_markup()){
      $output .= '<div class="item">' . $this->copy_markup() . '</div>';
    }
    $output .= $this->addl_field_markup();
    return $output;
  }

  public function photography_by(){
    if(!empty($this->node->field_photography_by)){
      return $this->node->field_photography_by['und'][0]['value'];
    }
    return '';
  }

  public function photography_link(){
    if(!empty($this->node->field_photography_credit_url)){
      return $this->node->field_photography_credit_url['und'][0]['value'];
    }
    return '';
  }

  public function photography_markup(){
    $output = '';
    if($this->photography_by){
      $output .= '<h4 class="label">Photography by</h4>';
      $name = $this->photography_by;
      if($this->photography_link){
        $name = '<a href="' . $this->photography_link . '" target="_blank">' . $name . '</a>';
      }
      $output .= '<p class="name">' . $name . '</p>';
    }
    return $output;
  }

  public function styling_by(){
    if(!empty($this->node->field_styling_by)){
      return $this->node->field_styling_by['und'][0]['value'];
    }
    return '';
  }

  public function styling_link(){
    if(!empty($this->node->field_styling_credit_url)){
      return $this->node->field_styling_credit_url['und'][0]['value'];
    }
    return '';
  }

  public function styling_markup(){
    $output = '';
    if($this->styling_by){
      $output .= '<h4 class="label">Styling by</h4>';
      $name = $this->styling_by;
      if($this->styling_link){
        $name = '<a href="' . $this->styling_link . '" target="_blank">' . $name . '</a>';
      }
      $output .= '<p class="name">' . $name . '</p>';
    }
    return $output;
  }

  public function copy_by(){
    if(!empty($this->node->field_copy_by)){
      return $this->node->field_copy_by['und'][0]['value'];
    }
    return '';
  }

  public function copy_link(){
    if(!empty($this->node->field_copy_credit_url)){
      return $this->node->field_copy_credit_url['und'][0]['value'];
    }
    return '';
  }

  public function copy_markup(){
    $output = '';
    if($this->copy_by){
      $output .= '<h4 class="label">Copy by</h4>';
      $name = $this->copy_by;
      if($this->copy_link){
        $name = '<a href="' . $this->copy_link . '" target="_blank">' . $name . '</a>';
      }
      $output .= '<p class="name">' . $name . '</p>';
    }
    return $output;
  }

  public function addl_field_markup(){
    $output = '';
    if($this->addl_field_1_markup()){
      $output .= '<div class="item">' . $this->addl_field_1_markup() . '</div>';
    }
    if($this->addl_field_2_markup()){
      $output .= '<div class="item">' . $this->addl_field_2_markup() . '</div>';
    }
    if($this->addl_field_3_markup()){
      $output .= '<div class="item">' . $this->addl_field_3_markup() . '</div>';
    }
    if($this->addl_field_4_markup()){
      $output .= '<div class="item">' . $this->addl_field_4_markup() . '</div>';
    }
    return $output;
  }

  public function addl_field_1(){
    if(!empty($this->node->field_addl_field_1)){
      return $this->node->field_addl_field_1['und'][0]['value'];
    }
    return '';
  }

  public function addl_url_1(){
    if(!empty($this->node->field_addl_url_1)){
      return $this->node->field_addl_url_1['und'][0]['value'];
    }
    return '';
  }

  public function addl_label_1(){
    if(!empty($this->node->field_addl_label_1)){
      return $this->node->field_addl_label_1['und'][0]['value'];
    }
    return '';
  }

  public function addl_field_1_markup(){
    $output = '';
    if ($this->addl_label_1) $output .= '<h4 class="label">' . $this->addl_label_1 . '</h4>';
    if($this->addl_field_1){
      $value = $this->addl_field_1;
      if ($this->addl_url_1) $value = '<a href="' . $this->addl_url_1 . '" target="_blank">' . $value . '</a>';
      $output .= '<p class="name">' . $value . '</p>';
    }
    return $output;
  }

  public function addl_field_2(){
    if(!empty($this->node->field_addl_field_2)){
      return $this->node->field_addl_field_2['und'][0]['value'];
    }
    return '';
  }

  public function addl_url_2(){
    if(!empty($this->node->field_addl_url_2)){
      return $this->node->field_addl_url_2['und'][0]['value'];
    }
    return '';
  }

  public function addl_label_2(){
    if(!empty($this->node->field_addl_label_2)){
      return $this->node->field_addl_label_2['und'][0]['value'];
    }
    return '';
  }

  public function addl_field_2_markup(){
    $output = '';
    if ($this->addl_label_2) $output .= '<h4 class="label">' . $this->addl_label_2 . '</h4>';
    if($this->addl_field_2){
      $value = $this->addl_field_2;
      if ($this->addl_url_2) $value = '<a href="' . $this->addl_url_2 . '" target="_blank">' . $value . '</a>';
      $output .= '<p class="name">' . $value . '</p>';
    }
    return $output;
  }

  public function addl_field_3(){
    if(!empty($this->node->field_addl_field_3)){
      return $this->node->field_addl_field_3['und'][0]['value'];
    }
    return '';
  }

  public function addl_url_3(){
    if(!empty($this->node->field_addl_url_3)){
      return $this->node->field_addl_url_3['und'][0]['value'];
    }
    return '';
  }

  public function addl_label_3(){
    if(!empty($this->node->field_addl_label_3)){
      return $this->node->field_addl_label_3['und'][0]['value'];
    }
    return '';
  }

  public function addl_field_3_markup(){
    $output = '';
    if ($this->addl_label_3) $output .= '<h4 class="label">' . $this->addl_label_3 . '</h4>';
    if($this->addl_field_3){
      $value = $this->addl_field_3;
      if ($this->addl_url_3) $value = '<a href="' . $this->addl_url_3 . '" target="_blank">' . $value . '</a>';
      $output .= '<p class="name">' . $value . '</p>';
    }
    return $output;
  }

  public function addl_field_4(){
    if(!empty($this->node->field_addl_field_4)){
      return $this->node->field_addl_field_4['und'][0]['value'];
    }
    return '';
  }

  public function addl_url_4(){
    if(!empty($this->node->field_addl_url_4)){
      return $this->node->field_addl_url_4['und'][0]['value'];
    }
    return '';
  }

  public function addl_label_4(){
    if(!empty($this->node->field_addl_label_4)){
      return $this->node->field_addl_label_4['und'][0]['value'];
    }
    return '';
  }

  public function addl_field_4_markup(){
    $output = '';
    if ($this->addl_label_4) $output .= '<h4 class="label">' . $this->addl_label_4 . '</h4>';
    if($this->addl_field_4){
      $value = $this->addl_field_4;
      if ($this->addl_url_4) $value = '<a href="' . $this->addl_url_4 . '" target="_blank">' . $value . '</a>';
      $output .= '<p class="name">' . $value . '</p>';
    }
    return $output;
  }

  public function credits(){
    if(empty($this->node->field_credits)){
      return '';
    }
    $credits = array();
    foreach ($this->node->field_credits['und'] as $credit_array) {
      $node = node_load($credit_array['nid']);
      $credits[] = new Contributor($node);
    }
    return $credits;
  }

  public function layout(){
    if(empty($this->node->field_layout)){
      return 'large';
    }
    return $this->node->field_layout['und'][0]['value'];
  }

  public function grid_items(){
    $grid_items = array();
    if($this->layout != 'grid' || empty($this->node->field_grid_items)) return $grid_items;
    foreach($this->node->field_grid_items['und'] as $grid_item){
      $entity = entity_load('field_collection_item', array($grid_item['value']));
      $grid_items[] = new GridItem(array_shift($entity));
    }
    return $grid_items;
  }

}

?>