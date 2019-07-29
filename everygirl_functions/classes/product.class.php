<?php

class Product {

  public function __construct($product){
    $this->product = $product;
    $this->title = $this->title();
    $this->price = $this->price();
    $this->link = $this->link();
    $this->image_src = $this->image_src();
  }

  public function title(){
    if(!property_exists($this->product, 'title')){
      return '';
    }
    return $this->product->title;
  }

  public function price(){
    if(empty($this->product->variants)){
      return '';
    }
    return $this->product->variants[0]->price;
  }

  public function link(){
    if(!property_exists($this->product, 'handle')){
      return '';
    }
    return 'http://' . variable_get('shopify_domain', '') . '/products/' . $this->product->handle;
  }

  public function image_src(){
    if(!property_exists($this->product, 'image')){
      return '';
    }
    // append "_large" to use the thumbnail
    return substr_replace($this->product->image->src, '_large', strrpos($this->product->image->src, '.'), 0);
  }

}

?>