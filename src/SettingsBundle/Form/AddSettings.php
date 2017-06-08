<?php

namespace SettingsBundle\Form;

use SettingsBundle\Entity\Settings;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddSettings extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('Value', TextType::class, array(
                'label' => 'Value'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Add setting',
                'attr'  => array('class' => 'btn btn-default pull-right')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Settings::class
        ));
    }
}