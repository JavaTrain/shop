<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Entity\OptionValue;
use Lokos\ShopBundle\Entity\ProductSet;
use Lokos\ShopBundle\Form\EventListener\AddCategoryFieldSubscriber;
use Lokos\ShopBundle\Form\EventListener\AddOptionFieldSubscriber;
use Lokos\ShopBundle\Form\Fields\ExtendedCollectionType;
use Lokos\ShopBundle\Repositories\BrandRepository;
use Lokos\ShopBundle\Repositories\OptionValueRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraints\NotBlank;

class ProductFormType extends AbstractType
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
                'data_class'         => 'Lokos\ShopBundle\Entity\Product',
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
            ->add(
                'quantity',
                null,
                array(
                    'label' => 'product.quantity',
                )
            )
            ->add(
                'category',
                EntityType::class,
                array(
                    'class'       => 'LokosShopBundle:Category',
                    'placeholder' => 'Choose category'
                )
            );

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

                if (null === $data) {
                    return;
                }
                $accessor = PropertyAccess::createPropertyAccessor();
                $category = $accessor->getValue($data, 'category');
                if ($category) {
                    $this->addProductSetForm($form, $data);
                    $this->addBrandForm($form, $category->getId());
                    $this->addProduct2AttributeForm($form, $category->getId());
                } else {
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

                if (array_key_exists('category', $data)) {
                    $category = $this->em->getRepository('LokosShopBundle:Category')
                                         ->findOneBy(
                                             array(
                                                 'id' => $data['category'],
                                             )
                                         );
                    if ($category) {
                        $product = $form->getData()->setCategory($category);
                        $this->addBrandForm($form, $data['category']);
                        $this->addProductSetForm($form, $product);
                        $this->addProduct2AttributeForm($form, $product);
                    } else {
//                        var_dump('bad');die;
                        $form->remove('productSets');
                    }
                }
            }
        );


    }

    /**
     * @param $form
     * @param $catId
     */
    protected function addBrandForm($form, $catId)
    {
        $formOptions = array(
            'class'         => 'LokosShopBundle:Brand',
            'attr'          => ['class' => 'option-select'],
            'query_builder' => function (BrandRepository $repository) use ($catId) {
                return $repository->createQueryBuilder('tbl')
                                  ->join('tbl.categories', 'c', Join::WITH, 'c.id = :category')
                                  ->setParameter(':category', $catId)
                                  ->orderBy('tbl.name', 'ASC');
            },
            'placeholder'   => 'Choose a brand',
        );

        $form->add('brand', EntityType::class, $formOptions);
    }

    /**
     * @param $form
     * @param $data
     */
    protected function addProductSetForm($form, $data)
    {
        $formOptions = array(
            'entry_type'    => ProductSetFormType::class,
            'entry_options' => array(
                'attr' => ['product' => $data]
            ),
            'prototype'     => true,
            'allow_add'     => true,
            'allow_delete'  => true,
            'required'      => false,
        );

        $form->add('productSets', CollectionType::class, $formOptions);
    }

    /**
     * @param $form
     * @param $data
     */
    protected function addProduct2AttributeForm($form, $data)
    {
        $formOptions = array(
            'entry_type'    => Product2AttributeFormType::class,
            'entry_options' => array(
                'attr' => ['product' => $data]
            ),
            'prototype'     => true,
            'allow_add'     => true,
            'allow_delete'  => true,
            'required'      => false,
        );

        $form->add('product2Attributes', CollectionType::class, $formOptions);
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_shop_product';
    }
}
