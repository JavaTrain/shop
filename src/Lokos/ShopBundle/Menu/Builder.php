<?php

namespace Lokos\ShopBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Lokos\ShopBundle\Entity\Category;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;

class Builder implements ContainerAwareInterface
{
    use ContainerAwareTrait;

    public function mainMenu(FactoryInterface $factory, array $options)
    {
        $menu = $factory->createItem('root');

        $dropdown = $menu->addChild('Config', ['dropdown' => true, 'caret' => true]);
        $dropdown2 = $dropdown->addChild(
            'User',
            array(
                'route' => 'lokos_shop_homepage',
            )
        );

        // Secondary Menu -> Edit (but child of Menu -> Config -> User)
        $dropdown2->addChild(
            'Edit',
            array(
                'route'           => 'lokos_shop_homepage',
//                'routeParameters' => array('name' => $options['id']),
            )
        );




//        $menu->addChild('Home', array('route' => 'lokos_shop_homepage'));
//
//        // access services from the container!
//        /** @var EntityManager $em */
//        $em = $this->container->get('doctrine')->getManager();
//        // findMostRecent and Blog are just imaginary examples
//        $categories = $em->getRepository('LokosShopBundle:Category')->findAll();
//
//        /** @var Category $category */
//        foreach ($categories as $category) {
//            $menu->addChild($category->getName(), array(
//                'route' => 'lokos_shop_overview',
//                'routeParameters' => array('id' => $category->getId())
//            ));
//        }
//
//        // create another menu item
//        $menu->addChild('About Me', array('route' => 'lokos_shop_homepage'));
//        // you can also add sub level's to your menu's as follows
//        $menu['About Me']->addChild('Edit profile', array('route' => 'lokos_shop_homepage'));
//
//        // ... add more children

        return $menu;
    }
}