<?php

namespace IngredientBundle\Form;

use Doctrine\ORM\EntityRepository;
use IngredientBundle\Entity\Ingredient;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('name', TextType::class, array(
                'label' => 'Name'
            ))
            ->add('cost', IntegerType::class, array(
                'label' => 'Cost'
            ))
            ->add('quantity', IntegerType::class, array(
                'label' => 'Quantity',
                'data' => 1
            ))
            ->add('type', EntityType::class, array(
                'label' => 'Type',
                'class'=>'IngredientBundle\Entity\Type',
                'query_builder'=> function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name','ASC');},
                'choice_label' => 'name',
                'required' => false
            ))
            ->add('isActive', ChoiceType::class, array(
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