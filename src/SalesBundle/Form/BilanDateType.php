<?php

namespace SalesBundle\Form;

use SalesBundle\Entity\BilanDate;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BilanDateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('startDate', DateType::class, array(
                'data' => new \DateTime("now")
            ))
            ->add('finishDate', DateType::class, array(
                'data' => new \DateTime("now")
            ))
            ->add('Afficher',  SubmitType::class, array(
                'attr' => array('class' => 'submit-form-button')
            ))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => BilanDate::class,
        ));
    }
}