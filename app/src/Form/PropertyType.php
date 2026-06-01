<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Validator\Constraints as Assert;

class PropertyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Nom du bien',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length([
                        'min' => 2,
                        'max' => 64,
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de bien',
                'choices' => [
                    'Maison' => 'maison',
                    'Appartement' => 'appartement',
                    'Terrain' => 'terrain',
                    'Parking' => 'parking',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('area', NumberType::class, [
                'label' => 'Surface (m²)',
                'scale' => 2,
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Positive(),
                ],
            ])
            ->add('numberOfRooms', IntegerType::class, [
                'label' => 'Nombre de pièces',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('numberOfBedrooms', IntegerType::class, [
                'label' => 'Nombre de chambres',
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('ecoNote', ChoiceType::class, [
                'label' => 'Note énergie',
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('gesNote', ChoiceType::class, [
                'label' => 'Note gestion (GES)',
                'choices' => [
                    'A' => 'A',
                    'B' => 'B',
                    'C' => 'C',
                    'D' => 'D',
                    'E' => 'E',
                    'F' => 'F',
                    'G' => 'G',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => 'disponible',
                    'Loué' => 'loue',
                    'En travaux' => 'en_travaux',
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('criteria', ChoiceType::class, [
                'label' => 'Critères / Équipements',
                'choices' => [
                    'Cuisine aménagée' => 'cuisine_amenagee',
                    'Balcon/Terrasse' => 'balcon_terrasse',
                    'Garage/Box' => 'garage_box',
                    'Ascenseur' => 'ascenseur',
                    'Chauffage central' => 'chauffage_central',
                    'Climatisation' => 'climatisation',
                    'Parking' => 'parking',
                    'Jardin' => 'jardin',
                    'Piscine' => 'piscine',
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('address', AddressType::class, [
                'label' => 'Adresse',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
        ]);
    }
}
