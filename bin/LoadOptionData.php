<?php
namespace Lokos\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Lokos\ShopBundle\Entity\Option;

class LoadOptionData implements FixtureInterface, ContainerAwareInterface
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

        $option = new Option();
        $option->setValue('black');
        $option->setAtribute('2');

        $option2 = new Option();
        $option2->setValue('green');
        $option->setAtribute('2');

        $option3 = new Option();
        $option3->setValue('11');
        $option3->setAtribute('1');

        $option4 = new Option();
        $option4->setValue('16.5');
        $option4->setAtribute('1');

        $manager->persist($option);
        $manager->persist($option2);
        $manager->persist($option3);
        $manager->persist($option4);
        $manager->flush();
    }
}