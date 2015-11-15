<?php

/**
 * @file
 * Contains \Drupal\wb_breadcrumbs\WbPathBasedBreadcrumbBuilder.
 */

namespace Drupal\wb_breadcrumbs;

use Drupal\Core\Breadcrumb\Breadcrumb;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\Menu\MenuLinkTreeInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class to define the menu_link breadcrumb builder.
 */
class WbPathBasedBreadcrumbBuilder implements BreadcrumbBuilderInterface {
  use StringTranslationTrait;

  /**
   * The menu tree service.
   *
   * @var \Drupal\Core\Menu\MenuLinkTreeInterface
   */
  protected $menuTree;

  /**
   * The configuration object factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */

  protected $configFactory;

  /**
   * Constructs a WbPathBasedBreadcrumbBuilder object.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeInterface $menu_tree
   *   The menu tree service.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   A configuration object.
   */
  public function __construct(MenuLinkTreeInterface $menu_tree, ConfigFactoryInterface $config_factory) {
    $this->menuTree = $menu_tree;
    $this->configFactory = $config_factory;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('menu.link_tree'),
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function applies(RouteMatchInterface $route_match) {
    return strpos($route_match->getRouteObject()->getPath(), '/admin') !== 0;
  }

  /**
   * {@inheritdoc}
   */
  public function build(RouteMatchInterface $route_match) {
    $menu_tree = $this->menuTree;
    $config = $this->configFactory->get('wb_breadcrumbs.settings');
    $menu_name = $config->get('breadcrumb_menu');
    $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
    $tree = $menu_tree->load($menu_name, $parameters);
    $manipulators = array(
      array('callable' => 'menu.default_tree_manipulators:checkAccess'),
      array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
    );
    $tree = $menu_tree->transform($tree, $manipulators);
    $home_link = [Link::createFromRoute($this->t('Home'), '<front>')];
    //disable home page link if checkbox unchecked in breadcrumbs config
    if ($config->get('disable_front_page_link')) {
      $home_link = [];
    }
    $links = array_merge(
      $home_link,
      $this->linksFromTree($tree)
    );

    // Generate Breadcrumb object
    $breadcrumb = new Breadcrumb();
    $breadcrumb->setLinks($links);
    $breadcrumb->addCacheContexts(['route']);
    return $breadcrumb;
  }

  /**
   * {@inheritdoc}
   */
  public function linksFromTree($tree) {
    $out = [];
    foreach ($tree as $element) {
      if ($element->inActiveTrail) {
        $text = $element->link->getTitle();
        $url_object = $element->link->getUrlObject();
        if ($url_object->getRouteName() != "<front>") {
          $out = Link::fromTextAndUrl($text, $url_object);
          $out[] = $out;
        }
        if ($element->subtree) {
          $out = array_merge($out, $this->linksFromTree($element->subtree));
        }
      }
    }
    return $out;
  }

}
