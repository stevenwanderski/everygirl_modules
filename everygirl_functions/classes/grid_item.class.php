<?php

class GridItem {

  public function __construct($field_collection_item){
    $this->field_collection_item = $field_collection_item;
    $this->text = $this->text();
    $this->link = $this->link();
    $this->price = $this->price();
    $this->size = $this->size();
    $this->image_style = $this->image_style();
  }

  public function text(){
    if(!isset($this->field_collection_item->field_grid_item_text['und'][0])){
      return '';
    }
    return $this->field_collection_item->field_grid_item_text['und'][0]['value'];
  }

  public function link(){
    if(!isset($this->field_collection_item->field_grid_item_link['und'][0])){
      return '';
    }
    return $this->field_collection_item->field_grid_item_link['und'][0]['value'];
  }

  public function image($image_style){
    if(!isset($this->field_collection_item->field_grid_item_image['und'][0])){
      return '';
    }
    return theme_image_style(array(
      'style_name' => $image_style,
      'path' => $this->field_collection_item->field_grid_item_image['und'][0]['uri'],
      'width' => NULL,
      'height' => NULL
    ));
  }

  public function image_url($image_style){
    if(!isset($this->field_collection_item->field_grid_item_image['und'][0])){
      return '';
    }
    return image_style_url($image_style, $this->field_collection_item->field_grid_item_image['und'][0]['uri']);
  }

  public function size(){
    if(empty($this->field_collection_item->field_grid_item_size)){
      return '';
    }
    return $this->field_collection_item->field_grid_item_size['und'][0]['value'];
  }

  public function price(){
    if(empty($this->field_collection_item->field_grid_item_price)){
      return '';
    }
    return $this->field_collection_item->field_grid_item_price['und'][0]['value'];
  }

  public function image_style(){
    return 'article-grid-' . $this->size;
  }

}

?>