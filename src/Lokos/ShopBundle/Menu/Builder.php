<?php

namespace Lokos\ShopBundle\Menu;

use Doctrine\ORM\EntityManager;
use Knp\Menu\FactoryInterface;
use Lokos\ShopBundle\Entity\Category;
use Lokos\ShopBundle\Repositories\CategoryRepository;
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


                /** @var CategoryRepository $repo */
                $repo = $this->container->get('doctrine')->getManager()->getRepository('LokosShopBundle:Category');

                /** @var EntityManager $em */
        $em = $this->container->get('doctrine.orm.entity_manager');
//        $phones = new Category();
//        $phones->setName('Phones');
//
//        $smartPh = new Category();
//        $smartPh->setName('Smart Phones');
//        $smartPh->setParent($phones);
//
//        $ph = new Category();
//        $ph->setName('Dial Phone');
//        $ph->setParent($phones);
//
////        $carrots = new Category();
////        $carrots->setName('Carrots');
////        $carrots->setParent($vegetables);
//
//        $em->persist($phones);
//        $em->persist($smartPh);
//        $em->persist($ph);
////        $em->persist($carrots);
//        $em->flush();
//        die;
//        $a = new Category();
//        $a->setName('Expensive');
//        $b = new Category();
//        $b->setName('Cheap');
//        $smart = $repo->findOneByName('Smart Phones');
//        $repo->persistAsFirstChild($smart)
//            ->persistAsFirstChildOf($a, $smart)
//            ->persistAsLastChildOf($b, $smart);
//
//        $em->flush();
//
//
        $rendered = [];
//        $htmlTree = $repo->childrenHierarchy(
//            null, /* starting from root nodes */
//            false, /* false: load all children, true: only direct */
//            array(
//                'decorate' => true,
//                'representationField' => 'slug',
//                'html' => true
//            )
//        );
//        print_r($htmlTree);die;

        $options = array(
            'decorate'      => true,
            'rootOpen'      => function ($tree) {
            },
            'rootClose'     => '</ul></li>'."\n",
            'childOpen'     => function ($tree) {
                if (!empty($tree['__children'])) {
                    $res = '<li><a href="#" class="dropdown-toggle" data-toggle="dropdown">'.$tree["name"].'<b class="caret"></b></a><ul class="dropdown-menu">'."\n";
                } else {
                    $res = '<li>'."\n";
                }
                return $res;
            },
            'childClose'    => '</li>'."\n",
            'nodeDecorator' => function ($node) {
                if (!empty($node['__children'])) {
                    $res = ''."\n";
                } else {
                    $res = '<a href="/page/">'.$node['name'].'</a>'."\n";
                }
                return $res;
            }
        );

        $tree = $repo->childrenHierarchy(null,false, $options);

        echo $tree;//die;

//        return $menu;
    }
}