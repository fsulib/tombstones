<?php

namespace Drupal\tombstones;

/**
 * Interface TombstonesServiceInterface.
 */
interface TombstonesServiceInterface {

  function createTombstone($node_id);
  function getTombstoneRecordByPath($tombstoned_path);
  function getTombstoneRecordById($tombstone_id);

}
