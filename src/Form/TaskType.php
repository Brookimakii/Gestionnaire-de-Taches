<?php

namespace App\Form;

use App\Entity\Task;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

// Todo: add a Assign field

class TaskType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class)
            ->add('description', TextareaType::class)
//            ->add('creation_date', DateType::class, [
//                'widget' => 'single_text',
//                'data' => new \DateTime(),
//            ])
            
            ->add('priority', ChoiceType::class, [
                'choices' => [
                    'Forte' => 'rouge',
                    'Moyenne' => 'orange',
                    'Faible' => 'verte',
                    'Pas de priorité' => 'gris',
                ],
                'placeholder' => 'Choisir une priorité',
                'required' => false,
                'expanded' => false,
                'multiple' => false,
            ])
            ->add('finished', CheckboxType::class, [
                'required' => false, 
            ])
            ->add('due_date', DateType::class, [
                'widget' => 'single_text',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
