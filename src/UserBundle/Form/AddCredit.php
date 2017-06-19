<?php

namespace UserBundle\Form;

use UserBundle\Entity\Credit;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class AddCredit extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('user_id', EntityType::class, array(
                'label' => 'Coffee addict Name',
                'class'=>'UserBundle\Entity\User',
                'query_builder'=> function(EntityRepository $er){
                    return $er->createQueryBuilder('c')
                        ->orderBy('c.name','ASC');},
                'choice_label' => 'name'
            ))
            ->add('value', IntegerType::class, array(
                'label' => 'Value'
            ))
            ->add('submit', SubmitType::class, array(
                'label' => 'Add Credit',
                'attr'  => array('class' => 'btn btn-default pull-right')
            ));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => Credit::class
        ));
    }
}