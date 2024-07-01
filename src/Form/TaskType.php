<?php

namespace App\Form;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
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
            ->add('creation_date', DateType::class, [
                'widget' => 'single_text',
                'data' => new \DateTime(),
            ])
            
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
            ])
					->add('assignees', EntityType::class, [
						'class' => User::class,
						'multiple' => true,
						'expanded' => false,
						'choice_label' => 'email',
						'query_builder' => function(EntityRepository $er) use ($builder){
							$taskList = $builder->getData()->getTaskList();
							return $er->createQueryBuilder('u')
								->join('u.taskLists', 'o')
								->join('u.collaborateOn', 'a')
								->where('o.id = :val')
								->orWhere('a.id = :val')
								->setParameter('val', $taskList)
								->orderBy('u.id', 'ASC');
						},
						'attr' => [
							'class' => 'select2', // Add this line to include the select2 class
						],
					]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Task::class,
        ]);
    }
}
