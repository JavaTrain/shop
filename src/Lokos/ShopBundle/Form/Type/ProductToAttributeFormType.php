<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Repositories\AttributeRepository;
use Lokos\ShopBundle\Repositories\AttributeValueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;

class ProductToAttributeFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    private $em;

    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }
    
    /**
     * {@inheritDoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'translation_domain' => 'general',
                'data_class'         => 'Lokos\ShopBundle\Entity\Product2Attribute',
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
                'attribute',
                EntityType::class,
                array(
                    'class' => 'LokosShopBundle:Option',
                    'attr'  => ['class' => 'option-select'],
                    'query_builder' => function (AttributeRepository $repository) use ($options) {
                        return $repository->createQueryBuilder('tbl')
                                          ->join('tbl.category', 'c', Join::WITH, 'c.id = :category')
                                          ->setParameter(':category', $options['attr']['product']->getCategory()->getId())
                                          ->orderBy('tbl.name', 'ASC');
                    },
                    'placeholder' => 'Choose an attribute',
                )
            )
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (null === $data) {
                    return;
                }
                $accessor = PropertyAccess::createPropertyAccessor();
                $attribute = $accessor->getValue($data, 'attribute');
                if ($attribute) {
                    $form->add(
                        'optionValue',
                        EntityType::class,
                        array(
                            'class' => 'LokosShopBundle:AttributeValue',
                            'query_builder' => function (AttributeValueRepository $repository) use ($attribute) {
                                return $repository->createQueryBuilder('tbl')
                                                  ->where('tbl.attribute = :attributeId')
                                                  ->setParameter(':attributeId', $attribute->getId())
                                    ;
                            },
                            'placeholder' => 'Choose a value',
                        )
                    );
//                    $form->add('price');
//                    $form->remove('productSets');
                }
            }
        );

        $builder->addEventListener(
            FormEvents::PRE_SUBMIT,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (null === $data) {
                    return;
                }
                $attribute = $data['attribute'];
                if ($attribute) {
                    $form->add(
                        'attributeValue',
                        EntityType::class,
                        array(
                            'class' => 'LokosShopBundle:AttributeValue',
                            'query_builder' => function (AttributeValueRepository $repository) use ($data) {
                                return $repository->createQueryBuilder('tbl')
                                                    ->where('tbl.attribute = :attributeId')
                                                    ->setParameter(':attributeId', $data['attribute'])
                                    ;
                            },
                            'placeholder' => 'Choose a value',
                        )
                    );
//                    $form->add('price');
                } else {
                    var_dump('Very bad');die;
//                    $form->remove('productSets');
                }
            }
        );
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_product_to_attribute';
    }
}
