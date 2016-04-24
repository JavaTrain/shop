<?php
namespace Lokos\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Lokos\ShopBundle\Entity\Atribute;

class LoadAtributeData implements FixtureInterface, ContainerAwareInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    public function load(ObjectManager $manager)
    {

        $atribute = new Atribute();
        $atribute->setName('size');

        $atribute2 = new Atribute();
        $atribute2->setName('color');

//        $atribute3 = new Atribute();
//        $atribute3->setValue('512');
//        $atribute3->setOption(2);
//        $atribute3->setPrice(250);
//
//        $atribute4 = new Atribute();
//        $atribute4->setValue('1024');
//        $atribute4->setOption(2);
//        $atribute4->setPrice(750);


        $manager->persist($atribute);
        $manager->persist($atribute2);
//        $manager->persist($atribute3);
//        $manager->persist($atribute4);
        $manager->flush();
    }
}