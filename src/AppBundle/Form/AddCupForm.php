<?php

namespace AppBundle\Form;

use AppBundle\Entity\Cup;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddCupForm extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('user_id', ChoiceType::class, array(
            'label' => 'Coffee addict Name',
            'choices' => array(
                1 => 'Vasiliy',
                2 => 'Stepan',
                3 => 'Max',
                4 => 'Yury',
                5 => 'Vitaly',
                6 => 'Evgeny_S',
                7 => 'Evgeny_N'
            )
        ))
        ->add('cups', ChoiceType::class, array(
            'label' => 'Count of cups',
            'choices' => array(
                1 => 'One',
                2 => 'Two',
            )
        ))
        ->add('submit', SubmitType::class, array(
            'label' => 'Add cup',
            'attr'  => array('class' => 'btn btn-default pull-right')
        ));
}

public function configureOptions(OptionsResolver $resolver)
{
    $resolver->setDefaults(array(
            'data_class' => Cup::class
        ));
    }
}