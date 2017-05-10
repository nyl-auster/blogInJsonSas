<?php

namespace Drupal\bloginjson\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber subscribing to KernelEvents::REQUEST.
 */
class demoAccountReadOnly implements EventSubscriberInterface {

  public function __construct() {
    //$this->user = \Drupal::currentUser();
  }

  /**
   * We want to hide html front-end from the world.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   */
  public function denyAccessToEditPage(GetResponseEvent $event) {

    if (\Drupal::routeMatch()->getRouteName() == "entity.user.edit_form" && \Drupal::currentUser()->getAccountName() == 'démo') {
      drupal_set_message("Vous ne pouvez pas modifier l'utilisateur de démonstration.", 'error');
      $response = new RedirectResponse($GLOBALS['base_url'] . '/user', 301);
      $event->setResponse($response);
      $event->stopPropagation();
    }

  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['denyAccessToEditPage'];
    return $events;
  }

}
