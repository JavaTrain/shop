<?php

namespace Lokos\ShopBundle\Form\Type;

use Lokos\ShopBundle\Entity\OptionValue;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class OptionFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'general',
                'data_class'         => 'Lokos\ShopBundle\Entity\Option',
            )
        );
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add(
                'name',
                TextType::class,
                array(
                    'label'      => 'option.name_title',
                )
            )
            ->add(
                'category',
                EntityType::class,
                array(
                    'class' => 'LokosShopBundle:Category'
                )
            )
//            ->add('description')
//            ->add(
//                'optionValues',
//                CollectionType::class,
//                array(
//                    'entry_type'   => OptionValueFormType::class,
//                    'prototype'    => true,
//                    'allow_add'    => true,
//                    'allow_delete' => true,
//                    'required'     => false
//                )
//            )
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_shop_option';
    }
}
