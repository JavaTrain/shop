<?php

namespace Lokos\ShopBundle\Form\Type;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AttributeFormType extends AbstractType
{
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'general',
                'data_class'         => 'Lokos\ShopBundle\Entity\Attribute',
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
                'description',
                null,
                array(
                    'label' => 'option.description'
                )
            )
            ->add(
                'category',
                EntityType::class,
                array(
                    'label'    => 'attribute.categories',
                    'class'    => 'LokosShopBundle:Category',
                    'multiple' => true,
                    'attr'     => ['class' => 'chosen-select']
                )
            )
            ->add(
                'attributeValues',
                CollectionType::class,
                array(
                    'entry_type'   => AttributeValueFormType::class,
                    'prototype'    => true,
                    'allow_add'    => true,
                    'allow_delete' => true,
                    'required'     => false,
                )
            )
            ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_shop_attribute';
    }
}
