<?php

namespace Drupal\bloginjson\EventSubscriber;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Event subscriber subscribing to KernelEvents::REQUEST.
 */
class HideFrontSubscriber implements EventSubscriberInterface {

  public function __construct() {
    //$this->user = \Drupal::currentUser();
  }

  /**
   * We want to hide html front-end from the world.
   *
   * @param \Symfony\Component\HttpKernel\Event\GetResponseEvent $event
   */
  public function hideFront(GetResponseEvent $event) {



    if (\Drupal::currentUser()->id()) {
      return false;
    }

    // si on est sur la page d'accueil, la page profil utilisateurs, on ne fait rien
    if (\Drupal::service('path.matcher')->isFrontPage()) {
      return false;
    }

    // laisser les pages profils utilisateurs
    if (strpos(\Drupal::service('path.current')->getPath(), '/user') === 0) {
      return false;
    }

    // laisser  nos json :)
    if (strpos(\Drupal::service('path.current')->getPath(), '/jsonapi') === 0) {
      return false;
    }

    // dans les autres cas, on redirige automatiquement vers la page profil
    $response = new RedirectResponse($GLOBALS['base_url'] . '/user', 301);
    $event->setResponse($response);
    $event->stopPropagation();

  }

  public static function getSubscribedEvents() {
    $events[KernelEvents::REQUEST][] = ['hideFront'];
    return $events;
  }

}
