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

  public function createTombstone($node_id) {
    $time = time();
    $node = node_load($node_id);
    $title = $node->getTitle();
    $path = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node_id])->toString();
    $database = \Drupal::database();
    $result = $database->query("INSERT INTO {tombstones} (nid, time, title, path) VALUES ('{$node_id}', '{$time}', '{$title}', '{$path}');");
    return $result;
  }

  public function getTombstoneRecordByPath($tombstoned_path) {
    $database = \Drupal::database();
    $result = $database->query("SELECT * FROM {tombstones} WHERE path='{$tombstoned_path}';");
    return $result->fetchAll()[0];
  }

  public function getTombstoneRecordById($tombstone_id) {
    $database = \Drupal::database();
    $result = $database->query("SELECT * FROM {tombstones} WHERE id='{$tombstone_id}';");
    return $result->fetchAll()[0];
  }

}
