<?php

class JobEntityFieldQuery extends EntityFieldQuery {

  public function __construct(){
    $this->entityCondition('entity_type', 'node')
    ->entityCondition('bundle', 'job')
    ->propertyCondition('status', 1)
    ->fieldOrderBy('field_date', 'value', 'DESC');
  }

}

?>