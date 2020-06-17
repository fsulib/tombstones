<?php

namespace Drupal\tombstones;

use \Drupal\node\Entity\Node;

/**
 * Class TombstonesService.
 */
class TombstonesService {

  /**
   * Constructs a new TombstonesService object.
   */
  public function __construct() {
  }

  public function shouldTombstoneBeCreated($node) {
    if (\Drupal::config('tombstones.settings')->get('tombstones_paused') == FALSE) {    
      $ctypes = \Drupal::config('tombstones.settings')->get('tombstones_ctypes');
      $ctypes_shallow = [];
      foreach ($ctypes as $key => $value) {
        if ($value) {
          $ctypes_shallow[] = $value;
        }
      }
      if (in_array($node->bundle(), $ctypes_shallow)) {
        return TRUE;
      }
      else {
        return FALSE;
      }
    }
  }

  public function shouldTombstoneBeCreatedFromHook($node) {
    if (\Drupal::config('tombstones.settings')->get('tombstones_use_hooks') == TRUE) {    
      return \Drupal::service('tombstones.service')->shouldTombstoneBeCreated($node);
    }
    else {
      return FALSE;
    }
  }

  public function storeTombstonePredeleteMetadata($node) {
    $title = $node->getTitle();
    $path = \Drupal\Core\Url::fromRoute('entity.node.canonical', ['node' => $node->id()])->toString();
    $tombstones_tempstore = \Drupal::service('tempstore.private')->get('tombstones_predelete_metadata');
    $tombstones_tempstore->set($node->id(), ['title' => $title, 'path' => $path]);
  }

  public function createTombstoneForDeletedNode($node) {
    $tombstones_tempstore = \Drupal::service('tempstore.private')->get('tombstones_predelete_metadata');
    $tombstone_metadata = $tombstones_tempstore->get($node->id());
    $tombstone = Node::create([
      'type' => 'tombstone',
      'title' => $tombstone_metadata['title'],
      'field_tombstone_path' => $tombstone_metadata['path'],
    ]);
    $tombstone->save();
    \Drupal::service('path.alias_storage')->save("/node/" . $tombstone->id(), $tombstone_metadata['path'], "en");
  }

}
