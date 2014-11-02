<?php

/**
 * @file
 * Contains \Drupal\system\PathBasedBreadcrumbBuilder.
 */

namespace Drupal\wb_breadcrumbs;

use Drupal\Core\Breadcrumb\BreadcrumbBuilderInterface;
use Drupal\Core\Link;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Class to define the menu_link breadcrumb builder.
 */
class WbPathBasedBreadcrumbBuilder implements BreadcrumbBuilderInterface
{
    use StringTranslationTrait;

    /**
     * {@inheritdoc}
     */
    public function applies(RouteMatchInterface $route_match)
    {
        $path = $route_match->getRouteObject();
        $path = $path->getPath();
        $pos = strpos($path, '/admin');
        if ($pos === false) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * {@inheritdoc}
     */
    public function build(RouteMatchInterface $route_match)
    {
        $menu_tree = \Drupal::menuTree();
        $menu_name = \Drupal::config('wb_breadcrumbs.settings')->get('breadcrumb_menu');
        $parameters = $menu_tree->getCurrentRouteMenuTreeParameters($menu_name);
        $tree = $menu_tree->load($menu_name, $parameters);
        $manipulators = array(
            array('callable' => 'menu.default_tree_manipulators:checkAccess'),
            array('callable' => 'menu.default_tree_manipulators:generateIndexAndSort'),
        );
        $tree = $menu_tree->transform($tree, $manipulators);
        $link = $this->linksFromTree($tree);
        return $link;
    }

    private function linksFromTree($tree)
    {
        $out = [];
        foreach ($tree as $element) {
            if ($element->inActiveTrail) {
                $text = $element->link->getTitle();
                    $urlObject = $element->link->getUrlObject();
                    $out[] = $this->createFromRoute($text, $urlObject);
                if ($element->subtree) {
                    $out = array_merge($out, $this->linksFromTree($element->subtree));
                }
            }
        }
        return $out;
    }

    private function createFromRoute($text, $urlObject)
    {
        return new Link ($text, $urlObject);
    }
}
