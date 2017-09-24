<?php

namespace IngredientBundle\Form;

use IngredientBundle\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddIngredient extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('Name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('Cost', IntegerType::class, array(
                'label' => 'Cost'
            ))
            ->add('Quantity', IntegerType::class, array(
                'label' => 'Quantity'
            ))
            ->add('IsActive', ChoiceType::class, array(
                'label' => 'Status',
                'choices' => array(
                    1 => 'Enable',
                    0 => 'Disable',
                )
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Add ingredient',
                'attr'  => array('class' => 'btn btn-default pull-right')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Ingredient::class
        ));
    }
}