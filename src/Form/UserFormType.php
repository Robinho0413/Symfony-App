<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'required' => true,
                'label' => 'Nom',
                'attr' => [
                    'placeholder' => 'Nom'
                ]
            ])
            ->add('prenom', TextType::class)
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'choices' => [
                    'Admin' => 'ROLE_ADMIN',
                    'Gestion' => 'ROLE_GESTION',
                    'User' => 'ROLE_USER',
                ],
                'multiple' => true,
                'expanded' => false,
            ])
            ->add('actif', TextType::class)
            ->add('plainPassword', PasswordType::class, [
                'mapped' => false,  // This field is not directly mapped to the User entity
                'label' => 'Mot de passe',
                'required' => $options['is_edit'] ? false : true,  // Required only for new users
                'attr' => [
                    'placeholder' => 'Mot de passe'
                ]
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
            'is_edit' => false,  // Add this option to control required fields based on the form's context
        ]);
    }
}


?>
