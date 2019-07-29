<?php

class EverygirlContributorMap {

  public function __construct(){
    $this->articles = array();
  }

  public function run(){
    $this->get_articles();
    $this->check_fields();
  }

  private function get_articles(){
    $query = new EntityFieldQuery();
    $query->entityCondition('entity_type', 'node')
      ->entityCondition('bundle', 'article');
      // ->propertyCondition('nid', 2922);
    $results = $query->execute();

    if(empty($results['node'])) return;

    $articles = array();
    foreach ($results['node'] as $result) {
      $articles[] = node_load($result->nid);
    }

    $this->articles = $articles;
  }

  private function check_fields(){

    $found = array();
    $not_found = array();

    foreach ($this->articles as $article) {

      if(isset($article->field_photography_by['und'][0])){
        $photography = $article->field_photography_by['und'][0]['value'];

        if($photography){
          $query = new EntityFieldQuery();
          $query->entityCondition('entity_type', 'node')
            ->entityCondition('bundle', 'contributor')
            ->propertyCondition('title', $photography, 'CONTAINS');
          $results = $query->execute();

          if(empty($results['node'])){
            $not_found[$photography] = $photography;
          }else{
            $found[$photography] = $photography;
          }
        }

      }

    }

    ksort($found);
    ksort($not_found);

    // dpm("Matched:");
    // dpm($found);
    // dpm("Not matched:");
    // dpm($not_found);

  }

}

?>