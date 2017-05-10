<?php

namespace Drupal\bloginjson\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Provides a 'jsonInformations' block.
 *
 * @Block(
 *  id = "json_informations",
 *  admin_label = @Translation("Json informations"),
 * )
 */
class jsonInformations extends BlockBase {

  function __construct(array $configuration, $plugin_id, $plugin_definition) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->current_route_name = \Drupal::routeMatch()->getRouteName();
  }

  public function access(AccountInterface $account, $return_as_object = FALSE) {
    if (\Drupal::currentUser()->id()) {
      return AccessResult::allowed();
    }
    return AccessResult::forbidden();
  }

  /**
   * {@inheritdoc}
   */
  public function getCacheMaxAge() {
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $base_url = $GLOBALS['base_url'];
    $user_name = \Drupal::currentUser()->getAccountName();
    $url_articles_liste =  strip_tags("http://127.0.0.1/jsonblog/jsonapi/node/article?sort=created&filter[status][value]=1&filter[uid.name][value]=$user_name");
    $url_single_article =  strip_tags("http://127.0.0.1/jsonblog/jsonapi/node/article/{id}");
    $build = [];
    $build['#markup'] = "Liste de vos posts publiés :<br />
     <a target=\"_blank\" href=\"$url_articles_liste\">$url_articles_liste</a><br/><br/>
     Obtenir un article en particulier<br />
    <a href=\"$url_single_article\">$url_single_article</a><br/>
    <br />
";
    return $build;
  }

}
