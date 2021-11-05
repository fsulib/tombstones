<?php

namespace Drupal\tombstones;

use Drupal\Core\Config\ConfigFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\TempStore\PrivateTempStoreFactory;
use Drupal\Core\Url;
use Drupal\node\Entity\Node;

/**
 * Class TombstonesService.
 */
class TombstonesService {

   /**
   * An entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config factory service
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * The private tempstore service
   *
   * @var \Drupal\Core\TempStore\PrivateTempstoreService
   */
  protected $privateTempstore;

  /**
   * Constructs a new TombstonesService object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *  An entity type manager
   * @param \Drupal\Core\Config\ConfigFactory $config_factory
   *  The config factory service
   * @param \Drupal\Core\TempStore\PrivateTempStoreFactory $tempstore_private
   *  The private tempstore service
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, ConfigFactory $config_factory, PrivateTempStoreFactory $tempstore_private) {
    $this->entityTypeManager = $entity_manager;
    $this->configFactory = $config_factory;
    $this->privateTempstore = $tempstore_private;
  }

  public function shouldTombstoneBeCreated($node) {
    $tombstone_settings = $this->configFactory->get('tombstones.settings');
    if ($tombstone_settings->get('tombstones_paused') == FALSE) {
      $ctypes = $tombstone_settings->get('tombstones_ctypes');
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
    $tombstone_settings = $this->configFactory->get('tombstones.settings');
    if ($tombstone_settings->get('tombstones_use_hooks') == TRUE) {
      return $this->shouldTombstoneBeCreated($node);
    }
    else {
      return FALSE;
    }
  }

  public function storeTombstonePredeleteMetadata($node) {
    $title = $node->getTitle();
    $path = Url::fromRoute('entity.node.canonical', ['node' => $node->id()])->toString();
    $tombstones_tempstore = $this->privateTempstore->get('tombstones_predelete_metadata');
    $tombstones_tempstore->set($node->id(), ['title' => $title, 'path' => $path]);
  }

  public function createTombstoneForDeletedNode($node) {
    $tombstones_tempstore = $this->privateTempstore->get('tombstones_predelete_metadata');
    $tombstone_metadata = $tombstones_tempstore->get($node->id());
    $tombstone = $this->entityTypeManager->getStorage('node')->create([
      'type' => 'tombstone',
      'title' => $tombstone_metadata['title'],
      'field_tombstone_path' => $tombstone_metadata['path'],
    ]);
    $tombstone->save();
    $path_alias = $this->entityTypeManager->getStorage('path_alias')->create([
      'path' => "/node/" . $tombstone->id(),
      'alias' => $tombstone_metadata['path'],
      'langcode' => "en",
    ]);
    $path_alias->save();
  }

}
