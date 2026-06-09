<?php

namespace App\Form;

use App\Entity\Address;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Validator\Constraints as Assert;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('number', TextType::class, [
                'label' => 'Numéro',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('zipCode', TextType::class, [
                'label' => 'Code Postal',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('city', TextType::class, [
                'label' => 'Ville',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('addressInfo', TextType::class, [
                'label' => 'Complément d\'adresse',
                'required' => false,
            ])
            ->add('country', TextType::class, [
                'label' => 'Pays',
                'required' => false,
                'empty_data' => 'France',
                'attr' => [
                    'placeholder' => 'France',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Address::class,
        ]);
    }
}
