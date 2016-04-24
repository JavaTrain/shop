<?php
namespace Lokos\ShopBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Lokos\ShopBundle\Entity\Brand;
use Lokos\ShopBundle\Entity\Product;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Lokos\ShopBundle\Entity\Category;

class LoadProductData extends AbstractFixture implements OrderedFixtureInterface
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
        $product = new Product();
        $product->setName('Sony experia z1');
        $product->setDescription('Some description');
        $product->setPrice('3560.8');
        $product->setCategory($this->getReference('category-phone'));
        $product->setBrand($this->getReference('brand-sony'));

        $product2 = new Product();
        $product2->setName('Samsung laptop 24');
        $product2->setDescription('Some description');
        $product2->setPrice('23560.5');
        $product->setCategory($this->getReference('category-laptop'));
        $product->setBrand($this->getReference('brand-samsung'));

        $manager->persist($product);
        $manager->persist($product2);

        $manager->flush();
    }

    public function getOrder()
    {
        // the order in which fixtures will be loaded
        // the lower the number, the sooner that this fixture is loaded
        return 5;
    }
}