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
//        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
//            $product = $event->getData();
//            var_dump($event);die;
//        });

//        var_dump($builder, $options);die;
        $builder
            ->add(
                'option',
                EntityType::class,
                array(
                    'class' => 'LokosShopBundle:Option',
//                    'query_builder' => function (OptionRepository $repository) use ($options) {
//                        return $repository->createQueryBuilder('tbl')
//                                          ->join('tbl.category', 'c', Join::WITH, 'c.name = :category')
//                                          ->setParameter(':category', $options['attr']['product']->getCategory()->getName())
//                                          ->orderBy('tbl.name', 'ASC');
//                    },
                )
            )
            ->add(
                'optionValue',
                null
            )
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'lokos_product_to_option';
    }
}
