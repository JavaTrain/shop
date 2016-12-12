<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Lokos\ShopBundle\Entity\OptionValue;
use Lokos\ShopBundle\Form\DataTransformer\ProductToOptionTransformer;
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

class ProductSetFormType extends AbstractType
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
                'data_class'         => 'Lokos\ShopBundle\Entity\ProductSet',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
//        var_dump($builder, $options);die;
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $product = $event->getData();
//            var_dump($event);die;
//        });

//        $builder->addEventSubscriber(new AddCategoryFieldSubscriber('options'));
//        $builder->addEventSubscriber(new AddOptionFieldSubscriber('options'));
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label' => 'product_set.name_title',
                )
            )
            ->add('quantity')
            ->add(
                'product2Options',
                CollectionType::class,
                array(
                    'entry_type'     => ProductToOptionFormType::class,
                    'entry_options'  => array(
                        'attr' => ['product' => $options['attr']['product']]
                    ),
                    'prototype'      => true,
                    'allow_add'      => true,
                    'allow_delete'   => true,
                    'required'       => false,
                    'prototype_name' => '__prod_set__',
                    'attr'           => ['class' => 'product-2-options']
                )
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_shop_product_set';
    }
}
