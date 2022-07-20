<?php

namespace Drupal\brightcove\EventSubscriber;

use Brightcove\API\Client;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Subscribes to Drupal initialization event.
 */
class BrightcoveInitSubscriber implements EventSubscriberInterface {

  /**
   * Initialize Brightcove client proxy.
   *
   * @param \Symfony\Component\HttpKernel\Event\RequestEvent $event
   *   GET response event.
   */
  public function initializeBrightcoveClient(RequestEvent $event) {
    $extension_info = \Drupal::service('extension.list.module')->getExtensionInfo('brightcove');
    Client::$consumer = 'Drupal/' . \Drupal::VERSION . ' Brightcove/' . ($extension_info['version'] ?: 'dev');
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['initializeBrightcoveClient'];
    return $events;
  }

}
