<?php

namespace Drupal\bloginjson\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Class RouteSubscriber.
 *
 * @package Drupal\bloginjson\Routing
 * Listens to the dynamic route events.
 */
class RouteSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    // on garde le theme de base pour la page d'accueil
    if (\Drupal::service('path.matcher')->isFrontPage()) {
      return;
    }
    $admin_routes = [
      'view.jsonblog_user_content.page_1',
      //'entity.user.canonical',
      //'entity.node.canonical'
    ];
    foreach ($collection->all() as $name => $route) {
      if (in_array($name, $admin_routes)) {
        $route->setOption('_admin_route', TRUE);
      }
    }
  }
}
