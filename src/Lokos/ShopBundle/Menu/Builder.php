<?php
/**
 * Menu Builder
 *
 * PHP version 5.3
 *
 * @package    Mindk\ScmBundle\Menu
 * @author     Mikhail Lantukh <mlantukh@mindk.com>
 * @copyright  2011-2014 mindk (http://mindk.com). All rights reserved.
 * @license    http://mindk.com Commercial
 * @link       http://mindk.com
 */

namespace Lokos\ShopBundle\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Knp\Menu\MenuItem;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class Builder
 *
 * @package Mindk\ScmBundle\Menu
 */
class Builder implements ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * Sets the container.
     *
     * @param ContainerInterface|null $container A ContainerInterface instance or null
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @var string mainMenuDomain translation domain for menu
     */
    protected $mainMenuDomain;

    /**
     * Builds main menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menuData    = $this->container->getParameter('lokos.shop.main_menu');
        $menuOptions = $menuData['options'];

        /**
         * @var MenuItem $menu
         */
        $menu = $factory->createItem('root');

        if (isset($menuOptions['class'])) {
            $menu->setChildrenAttribute('class', $menuOptions['class']);
        }

        $this->mainMenuDomain = $menuData['translation_domain'];
        $this->addItems($menu, $menuData['items'], $menuOptions);
        return $menu;
    }

    /**
     * Builds main menu
     *
     * @param FactoryInterface $factory
     * @param array            $options
     *
     * @return \Knp\Menu\ItemInterface
     */
    public function dropdownMenu(FactoryInterface $factory, array $options)
    {
        $menuData    = $this->container->getParameter('lokos.shop.dropdown_menu');
        $menuOptions = $menuData['options'];

        /**
         * @var MenuItem $menu
         */
        $menu = $factory->createItem('root');

        if (isset($menuOptions['class'])) {
            $menu->setChildrenAttribute('class', $menuOptions['class']);
        }

        $this->mainMenuDomain = $menuData['translation_domain'];
        $this->addItems($menu, $menuData['items'], $menuOptions);
        return $menu;
    }

    /**
     * Adds items to the menu
     *
     * @param MenuItem      $menu      Menu object
     * @param array         $itemsList Config data for menu
     *
     * @param callback|bool $callback
     *
     * @return NodeElement
     */
    private function addItems($menu, $itemsList, $options, $callback = false)
    {
        $security    = $this->container->get('security.authorization_checker');
        $translator  = $this->container->get('translator');
        $menuOptions = $options;

        foreach ($itemsList as $item) {
            if (!isset($item['permission']) || ($security->isGranted($item['permission']))) {
                $title  = $translator->trans($item['title'], array(), $this->mainMenuDomain);
                $params = array();
                if (isset($item['uri'])) {
                    $params['uri'] = $item['uri'];
                }

                if (!empty($item['all_routes'])) {
                    $params['allRoutes'] = $item['all_routes'];
                }

                if (isset($item['route'])) {
                    if (strpos($item['route'], ':') === false) {
                        $params['route'] = $item['route'];
                    } else {
                        //Adding an ability to use SonataBundle routes
                        list($serviceId, $action) = explode(':', $item['route']);
                        $url             = $this->container->get($serviceId)->generateMenuUrl($action);
                        $params['route'] = $url['route'];
                    }
                    $params['uri'] = '#';
                }
                if (isset($item['routeParameters'])) {
                    $params['routeParameters'] = $item['routeParameters'];
                }

                if (isset($item['background'])) {
                    $params['background'] = $item['background'];
                }

                $child = $menu->addChild($title, $params);
                if (isset($item['icon'])) {
                    $child->setLabelAttribute('class', $item['icon']);
                }

                $menu[$title]->setCurrent($this->isCurrent($item));

                if ($callback !== false) {
                    $callback($child);
                }
            }
        }
    }

    /**
     * Checks that menu item is current
     *
     * @param array $item config of menu item
     *
     * @return bool true if item is current, otherwise false
     */
    private function isCurrent($item)
    {
        $result  = false;
        $current = $this->container->get('request_stack')->getCurrentRequest()->get('_route');
        if (isset($item['route'])) {
            $result = ($current == $item['route']) ||
                (!empty($item['all_routes']) && in_array($current, $item['all_routes']));
        }
        return $result;
    }
}