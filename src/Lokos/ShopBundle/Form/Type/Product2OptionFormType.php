<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
//use FOS\UserBundle\Event\FormEvent;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class Product2OptionFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'general',
                'data_class'         => 'Lokos\ShopBundle\Entity\Product2Option',
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

//        var_dump($options);die;
        $builder
            ->add(
                'option',
                EntityType::class,
                array(
                    'class' => 'LokosShopBundle:Option',
                    'query_builder' => function (EntityRepository $er) {
                        return $er->createQueryBuilder('o')
                                  ->orderBy('o.name', 'ASC');
                    },
                )
            )
//            ->add(
//                'option',
//                CollectionType::class,
//                array(
//                    'entry_type'   => OptionFormType::class,
//                    'prototype'    => true,
//                    'allow_add'    => true,
//                    'allow_delete' => true,
//                    'required'     => false
//                )
//            )
//            ->add('product')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_product_2_option';
    }
}
