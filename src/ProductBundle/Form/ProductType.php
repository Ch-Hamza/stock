<?php

namespace ProductBundle\Form;

use ProductBundle\Entity\Product;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProductType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class)
            ->add('originPrice', IntegerType::class, array(
                'attr' => array('min' => 1),
            ))
            ->add('price', IntegerType::class, array(
                'attr' =>  array('min' => 1),
            ))
            ->add('quantity', IntegerType::class, array(
                'attr' =>  array('min' => 1),
            ))
            ->add('category', EntityType::class, array(
                'class' => 'ProductBundle\Entity\Category',
                'choice_label' => 'name',
            ))
            ->add('image', ProductImageType::class, array(
                'required' => false,
            ))
            ->add('save',  SubmitType::class)
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Product::class,
        ));
    }
}