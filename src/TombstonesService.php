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
    $node = node_load($node_id);
    $node_path = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node_id])->toString();
    $tombstone = Node::create([
		  'type' => 'tombstone',
		  'langcode' => 'en',
		  'created' => time(),
		  'changed' => time(),
		  'moderation_state' => 'published',
		  'title' => $node->getTitle(),
		  'field_tombstone_path' => $node_path,
		]);
    $node->save();
    $tombstone_path = \Drupal::service('path.alias_storage')->save("/node/" . $tombstone->id(), $node_path, "en");
    return $tombstone;
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
