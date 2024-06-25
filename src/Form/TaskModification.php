<?php 

namespace App\Form;

use App\Entity\TaskList;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TacheModificationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom de la tâche',
                'attr' => ['placeholder' => 'Entrez le nom de la tâche']
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'attr' => ['placeholder' => 'Entrez une description']
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => TaskList::class,
        ]);
    }
}
