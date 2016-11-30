<?php

namespace Lokos\ShopBundle\Form\DataTransformer;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;

class ProductToOptionTransformer implements DataTransformerInterface
{


    /**
     * @inheritdoc
     */
    public function transform($value)
    {
//        $result = null;
//
//        if($value) {
//            $result = $value->getId();
//        }

        return $value;
    }

    /**
     * @inheritdoc
     */
    public function reverseTransform($value)
    {
//       $result = null;
//        if($value) {
//            $result = $this->em->getRepository('MindkPublishBundle:Channel')->find($value);
//        }

        return $value;
    }
}
