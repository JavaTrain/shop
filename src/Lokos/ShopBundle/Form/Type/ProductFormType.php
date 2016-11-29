<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Lokos\ShopBundle\Entity\OptionValue;
use Lokos\ShopBundle\Entity\ProductSet;
use Lokos\ShopBundle\Form\EventListener\AddCategoryFieldSubscriber;
use Lokos\ShopBundle\Form\EventListener\AddOptionFieldSubscriber;
use Lokos\ShopBundle\Form\Fields\ExtendedCollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormType extends AbstractType
{
//    /**
//     * @var EntityManager
//     */
//    private $em;
//
//    public function __construct(EntityManager $em)
//    {
//        $this->em = $em;
//    }


    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'general',
                'data_class'         => 'Lokos\ShopBundle\Entity\Product',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $product = $event->getData();
//            var_dump($event);die;
//        });

//        $builder->addEventSubscriber(new AddCategoryFieldSubscriber('options'));
//        $builder->addEventSubscriber(new AddOptionFieldSubscriber('options'));
//        var_dump($options);die;
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label'      => 'product.name_title',
                )
            )
            ->add(
                'description',
                null,
                array(
                    'label' => 'product.description',
                )
            )
            ->add('price',
                  null,
                  array(
                      'label' => 'product.price',
                  )
            )
            ->add('quantity',
                  null,
                  array(
                      'label' => 'product.quantity',
                  )
            )
            ->add('category')
            ->add(
                'productSets',
                ExtendedCollectionType::class,
                array(
                    'extended_data' => [$options['data']],
                    'entry_type'    => ProductSetFormType::class,
                    'prototype'     => true,
                    'allow_add'     => true,
                    'allow_delete'  => true,
                    'required'      => false
                )
            )
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_shop_product';
    }
}
