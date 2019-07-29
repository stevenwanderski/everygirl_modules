<?php

class ArticleEntityFieldQuery extends EntityFieldQuery {

  public function __construct(){
    $this->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'article')
    ->propertyCondition('status', 1)
    ->fieldOrderBy('field_date', 'value', 'DESC');
  }

}

?>