<?php

namespace Drupal\tombstones;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Tombstone entity.
 *
 * @see \Drupal\tombstones\Entity\Tombstone.
 */
class TombstoneAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\tombstones\Entity\TombstoneInterface $entity */

    switch ($operation) {

      case 'view':

        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished tombstone entities');
        }


        return AccessResult::allowedIfHasPermission($account, 'view published tombstone entities');

      case 'update':

        return AccessResult::allowedIfHasPermission($account, 'edit tombstone entities');

      case 'delete':

        return AccessResult::allowedIfHasPermission($account, 'delete tombstone entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add tombstone entities');
  }


}
