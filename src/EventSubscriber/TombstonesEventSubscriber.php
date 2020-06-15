<?php

namespace Drupal\tombstones\EventSubscriber;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Drupal\Core\Site\Settings;

/**
 * Class TombstonesEventSubscriber.
 *
 * @package Drupal\tombstones\EventSubscriber
 */
class TombstonesEventSubscriber implements EventSubscriberInterface {

  /**
   * Ensures Tombstones output returned upon NotFoundHttpException.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent $event
   *   The response for exception event.
   */
  public function onNotFoundException(GetResponseForExceptionEvent $event) {
    if ($event->getException() instanceof NotFoundHttpException) {
      $path = \Drupal::service('path.current')->getPath();
      $tombstone = \Drupal::service('tombstones.service')->getTombstoneRecordByPath($path);
      if ($tombstone) {
        $event->setResponse(new RedirectResponse("/tombstones/{$tombstone->id}", 301, []));
      }
    }
  }

  /**
   * Registers the methods in this class that should be listeners.
   *
   * @return array
   *   An array of event listener definitions.
   */
  public static function getSubscribedEvents() {
    //$events[KernelEvents::REQUEST][] = ['onKernelRequest', 100];
    $events[KernelEvents::EXCEPTION][] = ['onNotFoundException', 0];
    return $events;
  }

}

