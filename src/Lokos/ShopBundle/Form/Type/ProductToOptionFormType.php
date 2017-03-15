<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
//use FOS\UserBundle\Event\FormEvent;
use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Entity\Option;
use Lokos\ShopBundle\Repositories\AttributeRepository;
use Lokos\ShopBundle\Repositories\AttributeValueRepository;
use Lokos\ShopBundle\Repositories\OptionRepository;
use Lokos\ShopBundle\Repositories\OptionValueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductToOptionFormType extends AbstractType
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
                'data_class'         => 'Lokos\ShopBundle\Entity\Product2Option',
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
                    'class' => 'LokosShopBundle:Attribute',
                    'attr'  => ['class' => 'option-select'],
                    'query_builder' => function (AttributeRepository $repository) use ($options) {
                        return $repository->createQueryBuilder('tbl')
                                          ->join('tbl.category', 'c', Join::WITH, 'c.id = :category')
                                          ->setParameter(':category', $options['attr']['product']->getCategory()->getId())
                                          ->orderBy('tbl.name', 'ASC');
                    },
                    'placeholder' => 'Choose an option',
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
                var_dump($data);die;
                $option = $accessor->getValue($data, 'option');
                if ($option) {
                    $form->add(
                        'optionValue',
                        EntityType::class,
                        array(
                            'class' => 'LokosShopBundle:OptionValue',
                            'query_builder' => function (AttributeValueRepository $repository) use ($option) {
                                return $repository->createQueryBuilder('tbl')
                                                  ->where('tbl.attribute = :optionId')
                                                  ->setParameter(':optionId', $option->getId())
                                    ;
                            },
                            'placeholder' => 'Choose a value',
                        )
                    );
                    $form->add('price');
                    $form->remove('productSets');
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
//                var_dump($data);die;
                $attribute = $data['attribute'];
                if ($attribute) {
                    $form->add(
                        'attributeValue',
                        EntityType::class,
                        array(
                            'class' => 'LokosShopBundle:AttributeValue',
                            'query_builder' => function (AttributeValueRepository $repository) use ($attribute) {
                                return $repository->createQueryBuilder('tbl')
                                                    ->where('tbl.attribute = :attributeId')
                                                    ->setParameter(':attributeId', $attribute)
                                    ;
                            },
                            'placeholder' => 'Choose a value',
                        )
                    );
                    $form->add('price');
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
        return 'lokos_product_to_option';
    }
}
