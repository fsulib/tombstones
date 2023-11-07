<?php

namespace Drupal\tombstones\EventSubscriber;

use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class AddHTTPHeaders implements EventSubscriberInterface {
  /**
   * Set the HTTP response status to 410 when viewing a tombstone.
   */
  public function onRespond(ResponseEvent $event) {
    $response = $event->getResponse();
    $response->setStatusCode(410, 'Gone');
  }

  /**
   * @inheritDoc
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::RESPONSE][] = ['onRespond'];
    return $events;
  }

}
