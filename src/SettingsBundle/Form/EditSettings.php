<?php

namespace SettingsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class EditSettings extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('value', TextType::class, array(
                'label' => 'Value'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Update setting',
                'attr'  => array('class' => 'btn btn-default pull-right')
            ));
    }
}