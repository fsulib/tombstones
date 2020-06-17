<?php

namespace Drupal\tombstones;

/**
 * Class TombstonesService.
 */
class TombstonesService {

  /**
   * Constructs a new TombstonesService object.
   */
  public function __construct() {
  }

  public function shouldTombstoneBeCreated($entity) {
    $ctypes = \Drupal::config('tombstones.settings')->get('tombstones_ctypes');
    $ctypes_shallow = [];
    foreach ($ctypes as $key => $value) {
      if ($value != 0) {
        $ctypes_shallow[] = $value;
      }
    }
    if (in_array($entity->bundle(), $ctypes_shallow)) {
      return TRUE;
    }
    else {
      return FALSE;
    }
  }


  public function shouldTombstoneBeCreatedFromHook($entity) {
    if (\Drupal::config('tombstones.settings')->get('tombstones_use_hooks') == TRUE) {    
      return \Drupal::service('tombstones.service')->shouldTombstoneBeCreated($entity);
    }
    else {
      return FALSE;
    }
  }

}
