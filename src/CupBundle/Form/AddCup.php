<?php

namespace CupBundle\Form;

use CupBundle\Entity\Cup;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Doctrine\ORM\EntityRepository;

class AddCup extends AbstractType
{
public function buildForm(FormBuilderInterface $builder, array $options)
{
    $builder
        ->add('user_id', EntityType::class, array(
            'label' => 'Coffee addict Name',
            'class'=>'UserBundle\Entity\User',
            'query_builder'=> function(EntityRepository $er){
                return $er->createQueryBuilder('c')->orderBy('c.name','ASC');},
            'choice_label' => 'name'
        ))
        ->add('cost', ChoiceType::class, array(
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