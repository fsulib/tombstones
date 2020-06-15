<?php

/**
 * @file
 * Contains \Drupal\tombstones\Controller\TombstonesResponseController.
 */
 
namespace Drupal\tombstones\Controller;

use Drupal\Core\Controller\ControllerBase;

class TombstonesResponseController extends ControllerBase {

  public function response($tombstone_id) {

    $tombstone = \Drupal::service('tombstones.service')->getTombstoneRecordById($tombstone_id);
    
    global $base_url;
    $date = date('Y-m-d', $tombstone->time);
    $response = "The requested resource '{$tombstone->title}' at {$base_url}{$tombstone->path} was removed on {$date}.";


    $build = array(
      '#type' => 'markup',
      '#markup' => $response,
    );

    return $build;
  }

}
