<?php

namespace Lokos\ShopBundle\Form\EventListener;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\PropertyAccess\PropertyAccess;
use Doctrine\ORM\EntityRepository;
//use SMTC\MainBundle\Entity\Province;
//use SMTC\MainBundle\Entity\City;

class AddCategoryFieldSubscriber implements EventSubscriberInterface
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

    private function addCategoryForm($form, $country = null)
    {
        $formOptions = array(
            'class'         => 'LokosShopBundle:Category',
            'mapped'        => false,
            'label'         => 'Category',
//            'empty_value'   => 'Choose category',
            'empty_data' => function (FormInterface $form) {
                return 'Select';
//                return new Blog($form->get('title')->getData());
            },
            'attr'          => array(
                'class' => 'category_selector',
            ),
        );

        if ($country) {
            $formOptions['data'] = $country;
        }

        $form->add('category', EntityType::class, $formOptions);
    }

    public function preSetData(FormEvent $event)
    {
        $data = $event->getData();
        $form = $event->getForm();

        if (null === $data) {
            return;
        }
//var_dump($data);die;
        $accessor = PropertyAccess::createPropertyAccessor();

        $options    = $accessor->getValue($data, 'product2options'/*$this->propertyPathToCity*/);
//        var_dump($options[0]->getCategory()[0]);die;
        $category = ($options[0]) ? $options[0]->getOption()->getCategory() : null;

        $this->addCategoryForm($form, $category);
    }

    public function preSubmit(FormEvent $event)
    {
        $form = $event->getForm();

        $this->addCategoryForm($form);
    }
}