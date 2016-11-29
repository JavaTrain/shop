<?php

namespace Lokos\ShopBundle\Form\Fields;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * ImageType
 *
 * @package    Mindk\PublishBundle\Form\Fields
 */
class ExtendedCollectionType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'extended_data' => array(),
            )
        );
    }

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        parent::buildView($view, $form, $options);

        $view->vars['extended_data'] = $options['extended_data'];
    }

    /**
     * @inheritdoc
     */
    public function getParent()
    {
        return CollectionType::class;
    }

    /**
     * @inheritdoc
     */
    public function getBlockPrefix()
    {
        return 'ExtendedCollection';
    }
}
