<?php

namespace Drupal\tombstones\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;

/**
 * Provides an interface for defining Tombstone entities.
 *
 * @ingroup tombstones
 */
interface TombstoneInterface extends ContentEntityInterface, EntityChangedInterface, EntityPublishedInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Tombstone name.
   *
   * @return string
   *   Name of the Tombstone.
   */
  public function getName();

  /**
   * Sets the Tombstone name.
   *
   * @param string $name
   *   The Tombstone name.
   *
   * @return \Drupal\tombstones\Entity\TombstoneInterface
   *   The called Tombstone entity.
   */
  public function setName($name);

  /**
   * Gets the Tombstone creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Tombstone.
   */
  public function getCreatedTime();

  /**
   * Sets the Tombstone creation timestamp.
   *
   * @param int $timestamp
   *   The Tombstone creation timestamp.
   *
   * @return \Drupal\tombstones\Entity\TombstoneInterface
   *   The called Tombstone entity.
   */
  public function setCreatedTime($timestamp);

}
