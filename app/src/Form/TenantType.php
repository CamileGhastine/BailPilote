<?php

namespace App\Form;

use App\Entity\Tenant;
use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class TenantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {   
        $user = $options['user'];

        $builder
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'mapped' => false,
                'data' => $user ? $user->getLastname() : null,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le nom est obligatoire.',
                    ]),
                    new Length([
                        'min'=> 2, 'max' => 50,
                        'minMessage' => 'Le nom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le nom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'mapped' => false,
                'data' => $user ? $user->getFirstname() : null,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Le prénom est obligatoire.',
                    ]),
                    new Length([
                        'min'=> 2, 'max' => 50,
                        'minMessage' => 'Le prénom doit comporter au moins {{ limit }} caractères.',
                        'maxMessage' => 'Le prénom ne peut pas dépasser {{ limit }} caractères.',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email',
                'mapped' => false,
                'data' => $user ? $user->getEmail() : null,
                'constraints' => [
                    new NotBlank([
                        'message' => "L'email est obligatoire.",
                    ]),
                    new Email([
                        'message' => "L'email '{{ value }}' n'est pas valide.",
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'label' => 'Téléphone',
                'mapped' => false,
                'data' => $user ? $user->getPhone() : null,
                'constraints' => [
                    new NotBlank([
                        'message' => "Le téléphone est obligatoire.",
                    ]),
                    new Regex([
                        'pattern' => '/^(\+33|0)[1-9](\s*\d{2}){4}$/',
                        'message' => "Le numéro de téléphone n'est pas valide.",
                    ]),
                ],
            ])
            ->add('sendEmail', CheckboxType::class, [
                'label' => 'Envoyer un email au locataire',
                'mapped' => false,
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Tenant::class,
            'user' => null,
        ]);

        $resolver->setAllowedTypes('user', [User::class, 'null']);
    }
}
