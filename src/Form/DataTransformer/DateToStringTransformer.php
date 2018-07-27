<?php

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\Exception\TransformationFailedException;

class DateToStringTransformer implements DataTransformerInterface
{
    /**
     * {@inheritdoc}
     */
    public function transform($value)
    {
        $value = $value ?? new \DateTime('today');
        return $value->format('m/d/Y');
    }

    /**
     * {@inheritdoc}
     */
    public function reverseTransform($value)
    {
        return new \DateTime($value);
    }
}
