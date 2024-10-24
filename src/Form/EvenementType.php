<?php

namespace App\Form;

use App\Entity\Evenement;
use App\Entity\User;
use Doctrine\ORM\Mapping\Entity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\ChoiceList\Factory\Cache\ChoiceLabel;

class EvenementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('startDateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de début'
            ])
            ->add('endDateTime', DateTimeType::class, [
                'widget' => 'single_text',
                'label' => 'Date et heure de fin'
            ])
            ->add('title')
            ->add('description')
            ->add('visibility', ChoiceType::class, [
                'choices' => [
                    'Public' => 'public',
                    'Privé' => 'private',
                ],
                'label' => 'Visibilité'
            ])
            ->add('users', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'email',
                'multiple' => true,
                'expanded' => true,
                'label' => 'Associer un utilisateur'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Evenement::class,
        ]);
    }
}
