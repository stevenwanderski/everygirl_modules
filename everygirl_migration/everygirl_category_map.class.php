<?php
class EverygirlCategoryMap {
  public $article_vid = 1;
  public $article_vocabulary_name = 'article_category';

  public $new_terms;
  public $rename_terms;
  public $term_articles_containing;
  public $term_articles_exact_match;
  public $remove_terms;
  public $terms_heirarchy;

  private $includes = array(
    'inc/category/new_terms.inc.php',
    'inc/category/rename_terms.inc.php',
    'inc/category/term_articles_containing.inc.php',
    'inc/category/term_articles_exact_match.inc.php',
    'inc/category/remove_terms.inc.php',
    'inc/category/terms_hierarchy.inc.php',
    'inc/category/migrate_categories.inc.php'
  );

  private function load_data(){
    foreach($this->includes as $file) {
      include_once $file;
    }
    $this->new_terms = $new_terms;
    $this->rename_terms = $rename_terms;
    $this->term_articles_containing = $term_articles_containing;
    $this->term_articles_exact_match = $term_articles_exact_match;
    $this->remove_terms = $remove_terms;
    $this->terms_heirarchy = $terms_heirarchy;
    $this->migrate_categories = $migrate_categories;
  }

  function run(){
    $this->load_data();
    $this->create_new_categories();
    $this->rename_terms();
    $this->migrate_categories();
    $this->assign_term_to_article_containing_bulk();
    $this->assign_term_to_article_exact_match_bulk();
    $this->remove_unused_terms();
    $this->set_terms_heirarchy();
    $this->purge_legacy_article_terms();
  }

  /**
   * Create new categories using the new categories array
   */
  function create_new_categories(){
    // Ensure that terms do not already exist that you are trying to create
    $existing = array();
    foreach ($this->new_terms as $category) {
      $term = taxonomy_get_term_by_name($category);
      if(!empty($term)){
        $existing[] = $term;
      }
    }

    if(!empty($existing)){
      drupal_set_message('Hang on! Some of the new terms already exist:', 'warning');
      // dpm($existing);
      return FALSE;
    }

    // Our new terms do not exist yet, so let's create them
    foreach ($this->new_terms as $category) {
      $term = (object)array(
        'name' => $category,
        'vid' => $this->article_vid,
      );
      if(taxonomy_term_save($term)){
        drupal_set_message('Term created: ' . $term->name);
      }else{
        drupal_set_message('Term was not created: ' . $term->name, 'error');
      }
    }

    return TRUE;
  }

  /**
   * Assign a new label to existing terms
   */
  function rename_terms(){
    foreach ($this->rename_terms as $old_name => $new_name) {
      $terms = taxonomy_get_term_by_name($old_name);
      if(!empty($terms)){
        $term = array_shift($terms);
        $term->name = $new_name;
        if(taxonomy_term_save($term)){
          drupal_set_message($old_name . ' term was renamed to ' . $term->name);
        }
      }else{
        drupal_set_message('The following term could not be renamed because it was not found: ' . $old_name);
      }
    }
    return TRUE;
  }

  /**
   * Migrate entire categories to a new category (ex. Finance Tip to Finance)
   */
  function migrate_categories(){
    foreach ($this->migrate_categories as $old_term => $new_term) {
      $old_terms_existing = taxonomy_get_term_by_name($old_term);
      $new_terms_existing = taxonomy_get_term_by_name($new_term);
      if(!empty($old_terms_existing) && !empty($new_terms_existing)){
        $old_term_obj = array_shift($old_terms_existing);
        $new_term_obj = array_shift($new_terms_existing);
        foreach (taxonomy_select_nodes($old_term_obj->tid) as $nid) {
          $node = node_load($nid);
          foreach($node->field_category['und'] as $index => $cat){
            if($cat['tid'] == $old_term_obj->tid){
              $node->field_category['und'][$index]['tid'] = $new_term_obj->tid;
            }
          }
          // keep the old "changed" value
          $node->override_changed = $node->changed;
          // disable pathauto (will only uncheck the UI box if alias is different than pathauto-generated path)
          $node->path['pathauto'] = FALSE;
          node_save($node);
        }
      }else{
        drupal_set_message('The following term could not be renamed because it was not found: ' . $old_name);
      }
    }
  }

  /**
   * Remove existing terms, and assign the supplied term to
   * all nodes containg the supplided $containing in the node title
   */
  function assign_term_to_article_containing($new_term, $contains) {

    // get the nodes
    $efq = new EntityFieldQuery();
    $efq->entityCondition('entity_type', 'node');
    $efq->propertyCondition('title', $contains, 'CONTAINS');
    $result = $efq->execute();

    // no node results
    if(empty($result['node'])){
      drupal_set_message("Could not find any nodes containing: $contains", 'error');
      return;
    }

    // get the term
    $terms = taxonomy_get_term_by_name($new_term, $this->article_vocabulary_name);
    if(empty($terms)){
      drupal_set_message("Could not find the term: $new_term", 'error');
      return;
    }

    $term = array_shift($terms);

    // assign the term to each node
    foreach ($result['node'] as $nid => $result_obj) {
      $node = node_load($nid);
      if($node->field_category['und'][0]['tid'] != $term->tid){
        $node->field_category['und'][0]['tid'] = $term->tid;
        // keep the old "changed" value
        $node->override_changed = $node->changed;
        // disable pathauto (will only uncheck the UI box if alias is different than pathauto-generated path)
        $node->path['pathauto'] = FALSE;
        node_save($node);
        drupal_set_message($node->title . ' has been assigned term: ' . $term->name);
      }
    }
  }

  /**
   * Using the $term_articles_containing array, cycle through each and assign the new term
   */
  function assign_term_to_article_containing_bulk(){
    foreach ($this->term_articles_containing as $new_term => $containing) {
      $this->assign_term_to_article_containing($new_term, $containing);
    }
  }

  /**
   * Remove existing terms, and assign the supplied term to
   * all nodes with matching titles
   */
  function assign_term_to_article_exact_match($new_term, $titles) {

    // get the nodes
    $efq = new EntityFieldQuery();
    $efq->entityCondition('entity_type', 'node');
    $efq->propertyCondition('title', $titles);
    $result = $efq->execute();

    // no node results
    if(empty($result['node'])){
      drupal_set_message("Could not find any nodes with titles: " . implode(', ', $titles), 'error');
      return;
    }

    // get the term
    $terms = taxonomy_get_term_by_name($new_term, $this->article_vocabulary_name);
    if(empty($terms)){
      drupal_set_message("Could not find the term: $new_term", 'error');
      return;
    }

    $term = array_shift($terms);

    // assign the term to each node
    foreach ($result['node'] as $nid => $result_obj) {
      $node = node_load($nid);

      // check if term is already assigned
      $term_exists = FALSE;
      foreach ($node->field_category['und'] as $node_term) {
        if($node_term['tid'] == $term->tid) $term_exists = TRUE;
      }
      if($term_exists) continue;

      $node->field_category['und'][]['tid'] = $term->tid;
      // keep the old "changed" value
      $node->override_changed = $node->changed;
      // disable pathauto (will only uncheck the UI box if alias is different than pathauto-generated path)
      $node->path['pathauto'] = FALSE;
      node_save($node);
      drupal_set_message($node->title . ' has been assigned term: ' . $term->name);
    }
  }

  /**
   * Using the $term_articles_containing array, cycle through each and assign the new term
   */
  function assign_term_to_article_exact_match_bulk(){
    foreach ($this->term_articles_exact_match as $new_term => $titles) {
      $this->assign_term_to_article_exact_match($new_term, $titles);
    }
  }

  /**
   * Remove all terms that are no longer used
   */
  function remove_unused_terms(){
    foreach ($this->remove_terms as $term_name) {
      // get the term
      $terms = taxonomy_get_term_by_name($term_name, $this->article_vocabulary_name);
      if(empty($terms)){
        drupal_set_message("Could not find the term: $term_name", 'error');
        continue;
      }
      $term = array_shift($terms);
      taxonomy_term_delete($term->tid);
      drupal_set_message('Term: ' . $term->name . ' has been removed');
    }
  }

  /**
   * Order the terms heirarchy into parents and children
   */
  function set_terms_heirarchy(){
    foreach ($this->terms_heirarchy as $parent => $children) {
      $parent_terms = taxonomy_get_term_by_name($parent, $this->article_vocabulary_name);
      if(empty($parent_terms)){
        drupal_set_message("Could not find the parent term: $parent");
        continue;
      }
      $parent_term = array_shift($parent_terms);
      foreach ($children as $child) {
        // get the term
        $terms = taxonomy_get_term_by_name($child, $this->article_vocabulary_name);
        if(empty($terms)){
          drupal_set_message("Could not find the term: $child", 'error');
          continue;
        }
        $term = array_shift($terms);
        $term->parent = $parent_term->tid;
        taxonomy_term_save($term);
        drupal_set_message('Term: ' . $term->name . ' parent has been updated to: ' . $parent_term->name);
      }
    }
  }

  /**
   * Remove any lingering legacy terms from articles
   */
  function purge_legacy_article_terms(){
    // get all articles
    $efq = new EntityFieldQuery();
    $efq->entityCondition('entity_type', 'node');
    $efq->entityCondition('bundle', 'article');
    $results = $efq->execute();

    foreach ($results['node'] as $result) {
      $node = node_load($result->nid);
      $changed = FALSE;
      if(isset($node->field_category['und'])){
        foreach($node->field_category['und'] as $index => $cat){
          if(!taxonomy_term_load($cat['tid'])){
            unset($node->field_category['und'][$index]);
            $changed = TRUE;
          }
        }
        if($changed){
          // keep the old "changed" value
          $node->override_changed = $node->changed;
          // disable pathauto (will only uncheck the UI box if alias is different than pathauto-generated path)
          $node->path['pathauto'] = FALSE;
          node_save($node);
        }
      }
    }
  }
}

?>