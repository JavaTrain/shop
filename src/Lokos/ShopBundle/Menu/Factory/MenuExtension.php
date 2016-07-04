<?php
/**
 * Knp Menu Factory extension class file
 *
 * PHP version 5.3
 *
 * @package    Mindk\ScmBundle\Menu\Factory
 * @author     Maxim Lyatsky <mlyatsky@mindk.com>
 * @copyright  2011-2014 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */

namespace Lokos\ShopBundle\Menu\Factory;

use Knp\Menu\Factory\ExtensionInterface;
use Knp\Menu\ItemInterface;

/**
 * Class MenuExtension
 *
 * @package Mindk\ScmBundle\Menu\Factory
 */
class MenuExtension implements ExtensionInterface
{
    /**
     * Build an item based on options
     *
     * @param ItemInterface $item
     * @param array         $options
     */
    public function buildItem(ItemInterface $item, array $options)
    {
        if ($options['navbar']) {
            $item->setChildrenAttribute('class', 'nav navbar-nav');
        }

        if ($options['pills']) {
            $item->setChildrenAttribute('class', 'nav nav-pills');
        }

        if ($options['stacked']) {
            $class = $item->getChildrenAttribute('class');
            $item->setChildrenAttribute('class', $class.' nav-stacked');
        }

        if ($options['dropdown-header']) {
            $item
                ->setAttribute('role', 'presentation')
                ->setAttribute('class', 'dropdown-header')
                ->setUri(null);
        }
        if ($options['list-group']) {
            $item->setChildrenAttribute('class', 'list-group');
            $item->setAttribute('class', 'list-group-item');
        }

        if ($options['list-group-item']) {
            $item->setAttribute('class', 'list-group-item');
        }

        if ($options['dropdown']) {
            $item
                ->setUri('#')
                ->setAttribute('class', 'dropdown')
                ->setLinkAttribute('class', 'dropdown-toggle')
                ->setLinkAttribute('data-toggle', 'dropdown')
                ->setChildrenAttribute('class', 'dropdown-menu')
                ->setChildrenAttribute('role', 'menu');

            if ($options['caret']) {
                $item->setExtra('caret', 'true');
            }
        }

        if ($options['divider']) {
            $item
                ->setLabel('')
                ->setUri(null)
                ->setAttribute('role', 'presentation')
                ->setAttribute('class', 'divider');
        }

        if ($options['pull-right']) {
            $class = $item->getChildrenAttribute('class', '');
            $item->setChildrenAttribute('class', $class.' pull-right');
        }

        if ($options['icon']) {
            $item->setExtra('icon', $options['icon']);
        }

        if ($options['allRoutes']) {
            $item->setExtra('allRoutes', $options['allRoutes']);
        }

        if ($options['notification']) {
            $item->setExtra('notification', $options['notification']);
        }
    }

    /**
     * Build options for extension
     *
     * @param array $options
     *
     * @return array $options
     */
    public function buildOptions(array $options)
    {
        return array_merge(
            array(
                'navbar'          => false,
                'pills'           => false,
                'stacked'         => false,
                'dropdown-header' => false,
                'dropdown'        => false,
                'list-group'      => false,
                'list-group-item' => false,
                'caret'           => false,
                'pull-right'      => false,
                'icon'            => false,
                'divider'         => false,
                'notification'    => false,
                'allRoutes'       => false,
            ),
            $options
        );
    }
}
