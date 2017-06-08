<?php

namespace UserBundle\Form;

use UserBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddUser extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('Type', ChoiceType::class, array(
                'label' => 'Type',
                'choices' => array(
                    'owner' => 'Owner',
                    'buyer' => 'Buyer',
                )
            ))
            ->add('Amortization', ChoiceType::class, array(
                'label' => 'Amortization',
                'choices' => array(
                    0 => 'No',
                    1 => 'Yes',
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Add User',
                'attr'  => array('class' => 'btn btn-default pull-right')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => User::class
        ));
    }
}