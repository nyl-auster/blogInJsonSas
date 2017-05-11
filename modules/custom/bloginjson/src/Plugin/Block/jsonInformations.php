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
 *  admin_label = @Translation("Vos webservices Json"),
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
    $url_articles_liste =  strip_tags("http://127.0.0.1/jsonblog/api/article?sort=created&filter[status][value]=1&filter[uid.name][value]=$user_name");
    $url_pages_liste =  strip_tags("http://127.0.0.1/jsonblog/api/page?sort=created&filter[status][value]=1&filter[uid.name][value]=$user_name");
    $url_single_article =  strip_tags("http://127.0.0.1/jsonblog/api/article/{id}");
    $url_single_page =  strip_tags("http://127.0.0.1/jsonblog/api/page/{id}");
    $build = [];
    $build['#markup'] = "<strong>Vos articles publiés </strong><br />
     <a target=\"_blank\" href=\"$url_articles_liste\">$url_articles_liste</a><br/><br/>
     <strong>Vos pages publiées </strong><br />
     <a target=\"_blank\" href=\"$url_pages_liste\">$url_pages_liste</a><br/><br/>
     
     <strong>Obtenir un article en particulier</strong><br />
    <a href=\"$url_single_article\">$url_single_article</a><br/>
    <br />
         
     <strong>Obtenir une page en particulier</strong><br />
    <a href=\"$url_single_page\">$url_single_page</a><br/>
    <br />
";
    return $build;
  }

}
