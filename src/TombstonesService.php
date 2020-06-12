<?php

namespace Drupal\tombstones;

/**
 * Class TombstonesService.
 */
class TombstonesService implements TombstonesServiceInterface {

  /**
   * Constructs a new TombstonesService object.
   */
  public function __construct() {
  }

  public function createTombstone($nid) {
    $time = time();
    $node = node_load($nid);
    $title = $node->getTitle();
    $path = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $nid])->toString();
    $database = \Drupal::database();
    $result = $database->query("INSERT INTO {tombstones} (nid, time, title, path) VALUES ('{$nid}', '{$time}', '{$title}', '{$path}');");
    return $result;
  }

}
