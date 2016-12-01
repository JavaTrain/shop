<?php

namespace Lokos\ShopBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
//use FOS\UserBundle\Event\FormEvent;
use Doctrine\ORM\Query\Expr\Join;
use Lokos\ShopBundle\Entity\Option;
use Lokos\ShopBundle\Repositories\OptionRepository;
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
                'option',
                EntityType::class,
                array(
                    'class' => 'LokosShopBundle:Option',
                    'attr'  => ['class' => 'option-select'],
                    'query_builder' => function (OptionRepository $repository) use ($options) {
                        return $repository->createQueryBuilder('tbl')
                                          ->join('tbl.category', 'c', Join::WITH, 'c.name = :category')
                                          ->setParameter(':category', $options['attr']['product']->getCategory()->getName())
                                          ->orderBy('tbl.name', 'ASC');
                    },
                )
            )
//            ->add(
//                'optionValue',
//                null
//            )
        ;

        $builder->addEventListener(
            FormEvents::PRE_SET_DATA,
            function (FormEvent $event) {
                $data = $event->getData();
                $form = $event->getForm();

//                var_dump($data);die;

                if (null === $data) {
                    return;
                }
                $accessor = PropertyAccess::createPropertyAccessor();
                $category = $accessor->getValue($data, 'category');
                if ($category) {
                    $this->addProductSetForm($form, $data);
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

                var_dump($data);die;
                if (null === $data) {
                    return;
                }
                $category = $data['category'];
                if ($category) {
                    $category = $this->em->getRepository('LokosShopBundle:Category')
                                         ->findOneBy(
                                             array(
                                                 'id' => $data['category'],
                                             )
                                         );
                    $product  = $form->getData()->setCategory($category);
                    $this->addProductSetForm($form, $product);
                } else {
                    $form->remove('productSets');
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
