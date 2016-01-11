<?php

/**
 * @file
 * Contains \Drupal\wb_breadcrumbs\Plugin\Block\WbPathBasedBreadcrumbBlock.
 */

namespace Drupal\wb_breadcrumbs\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;


/**
 * Provides a block to display the breadcrumbs.
 *
 * @Block(
 *   id = "wb_breadcrumb_block",
 *   admin_label = @Translation("WeebDo Breadcrumbs")
 * )
 */
class WbPathBasedBreadcrumbBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * The breadcrumb manager.
   *
   * @var \Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface
   */
  protected $breadcrumbManager;

  /**
   * The current route match.
   *
   * @var \Drupal\Core\Routing\RouteMatchInterface
   */
  protected $routeMatch;

  /**
   * Constructs a new WbPathBasedBreadcrumbBlock object.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param \Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface $breadcrumb_manager
   *   The breadcrumb manager.
   * @param \Drupal\Core\Routing\RouteMatchInterface $route_match
   *   The current route match.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, BreadcrumbBuilderInterface $breadcrumb_manager, RouteMatchInterface $route_match) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->breadcrumbManager = $breadcrumb_manager;
    $this->routeMatch = $route_match;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('breadcrumb'),
      $container->get('current_route_match')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build() {
    $breadcrumb = $this->breadcrumbManager->build($this->routeMatch)->getLinks();
    $config = \Drupal::config('wb_breadcrumbs.settings');
    $separator = $config->get('breadcrumb_separator');
    $main_class = $config->get('main_class');
    if (!empty($breadcrumb)) {
      return [
        '#theme' => 'wb_breadcrumb',
        '#breadcrumb' => $breadcrumb,
        '#separator' => $separator,
        '#main_class' => $main_class,
      ];
    }
  }

}
