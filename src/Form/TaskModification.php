<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TaskModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre',
                'attr' => ['class' => 'form-control']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['class' => 'form-control']
            ])
            ->add('dueDate', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date d\'échéance',
                'attr' => ['class' => 'form-control']
            ])
            ->add('priority', ChoiceType::class, [
                'label' => 'Priorité',
                'choices' => [
                    'Basse' => 'low',
                    'Moyenne' => 'medium',
                    'Haute' => 'high'
                ],
                'attr' => ['class' => 'form-control']
            ]);
            // ->add('assignees', EntityType::class, [
            //     'class' => User::class,
            //     'choice_label' => 'pseudo',
            //     'multiple' => true,
            //     'expanded' => false,
            //     'label' => 'Assignés',
            //     'attr' => ['class' => 'form-control']
            // ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
