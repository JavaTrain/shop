<?php

namespace Lokos\ShopBundle\Form\EventListener;

use Lokos\ShopBundle\Form\Type\OptionFormType;
use Lokos\ShopBundle\Form\Type\OptionValueFormType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
//use SMTC\MainBundle\Entity\Province;
//use SMTC\MainBundle\Entity\City;

class AddOptionFieldSubscriber implements EventSubscriberInterface
{
    private $propertyPathToCity;

    public function __construct($propertyPathToCity)
    {
        $this->propertyPathToCity = $propertyPathToCity;
    }

    public static function getSubscribedEvents()
    {
        return array(
            FormEvents::PRE_SET_DATA => 'preSetData',
            FormEvents::PRE_SUBMIT   => 'preSubmit'
        );
    }

    private function addProvinceForm($form, $country_id, $province = null)
    {
        $formOptions = array(
                'entry_type'   => OptionFormType::class,
                'prototype'    => true,
                'allow_add'    => true,
                'allow_delete' => true,
                'required'     => false


//            'class'         => 'LokosShopBundle:Option',
////            'empty_value'   => 'Provincia',
//            'label'         => 'Option',
//            'mapped'        => false,
//            'attr'          => array(
//                'class' => 'option_selector',
//            ),
//            'query_builder' => function (EntityRepository $repository) use ($country_id) {
//                $qb = $repository->createQueryBuilder('tbl')
//                                 ->innerJoin('tbl.category', 'c')
//                                 ->where('c.id = :category')
//                                 ->setParameter('category', $country_id)
//                ;
//
//                return $qb;
//            }
        );

        if ($province) {
            $formOptions['data'] = $province;
        }

        $form->add('option', CollectionType::class, $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }

        $accessor = PropertyAccess::createPropertyAccessor();

        $options        = $accessor->getValue($data, $this->propertyPathToCity);
//        var_dump($options[0]);die;
        $categories    = ($options[0]) ? $options[0]->getCategory() : null;
        $country_id  = ($categories) ? $categories[0]->getId() : null;

        $this->addProvinceForm($form, $country_id, $options);
    }

    public function preSubmit(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        $country_id = array_key_exists('country', $data) ? $data['country'] : null;

        $this->addProvinceForm($form, $country_id);
    }
}