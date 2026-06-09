<?php

namespace App\Form;

use App\Entity\Property;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
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
                    new Assert\Length(min: 2, max: 64),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type de bien',
                'choices' => [
                    'Maison'      => Property::TYPE_MAISON,
                    'Appartement' => Property::TYPE_APPARTEMENT,
                    'Terrain'     => Property::TYPE_TERRAIN,
                    'Parking'     => Property::TYPE_PARKING,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('area', NumberType::class, [
                'label' => 'Surface (m²)',
                'scale' => 2,
                'attr' => [
                    'type' => 'text',
                    'inputmode' => 'decimal',
                    'oninput' => "this.value = this.value
                        .replace(',', '.')
                        .replace(/[^0-9.]/g, '')
                        .replace(/(\..*)\./g, '$1')",
                ],
                'constraints' => [
                    new Assert\NotBlank([
                        'message' => 'La surface est obligatoire.',
                    ]),
                    
                ],
            ])
            ->add('numberOfRooms', IntegerType::class, [
                'label' => 'Nombre de pièces',
                'required' => false,
                'attr' => ['type' => 'text', 'inputmode' => 'numeric', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'')"],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('numberOfBedrooms', IntegerType::class, [
                'label' => 'Nombre de chambres',
                'required' => false,
                'attr' => ['type' => 'text', 'inputmode' => 'numeric', 'oninput' => "this.value=this.value.replace(/[^0-9]/g,'')"],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\PositiveOrZero(),
                ],
            ])
            ->add('ecoNote', ChoiceType::class, [
                'label' => 'Note énergie',
                'choices' => [
                    'A' => Property::ECO_A,
                    'B' => Property::ECO_B,
                    'C' => Property::ECO_C,
                    'D' => Property::ECO_D,
                    'E' => Property::ECO_E,
                    'F' => Property::ECO_F,
                    'G' => Property::ECO_G,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('gesNote', ChoiceType::class, [
                'label' => 'Note gestion (GES)',
                'choices' => [
                    'A' => Property::ECO_A,
                    'B' => Property::ECO_B,
                    'C' => Property::ECO_C,
                    'D' => Property::ECO_D,
                    'E' => Property::ECO_E,
                    'F' => Property::ECO_F,
                    'G' => Property::ECO_G,
                ],
                'constraints' => [
                    new Assert\NotBlank(),
                ],
            ])
            ->add('status', ChoiceType::class, [
                'label' => 'Statut',
                'choices' => [
                    'Disponible' => Property::STATUS_DISPONIBLE,
                    'Loué'       => Property::STATUS_LOUE,
                    'En travaux' => Property::STATUS_EN_TRAVAUX,
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
                    'Cuisine aménagée'  => Property::CRITERIA_CUISINE_AMENAGEE,
                    'Balcon/Terrasse'   => Property::CRITERIA_BALCON_TERRASSE,
                    'Garage/Box'        => Property::CRITERIA_GARAGE_BOX,
                    'Ascenseur'         => Property::CRITERIA_ASCENSEUR,
                    'Chauffage central' => Property::CRITERIA_CHAUFFAGE_CENTRAL,
                    'Climatisation'     => Property::CRITERIA_CLIMATISATION,
                    'Parking'           => Property::CRITERIA_PARKING,
                    'Jardin'            => Property::CRITERIA_JARDIN,
                    'Piscine'           => Property::CRITERIA_PISCINE,
                ],
                'multiple' => true,
                'expanded' => true,
                'required' => false,
            ])
            ->add('address', AddressType::class, [
                'label' => 'Adresse',
            ])
            ->add('images', CollectionType::class, [
                'entry_type' => ImageType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'by_reference' => false,
                'required' => false,
                'label' => false,
            ])
        ;

        $typesWithoutRooms = [Property::TYPE_TERRAIN, Property::TYPE_PARKING];

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) use ($typesWithoutRooms): void {
            $data = $event->getData();
            $type = $data['type'] ?? null;

            if (in_array($type, $typesWithoutRooms, true)) {
                $data['numberOfRooms'] = null;
                $data['numberOfBedrooms'] = null;
                $data['ecoNote'] = null;
                $data['gesNote'] = null;
                $event->setData($data);

                $event->getForm()->remove('numberOfRooms');
                $event->getForm()->remove('numberOfBedrooms');
                $event->getForm()->remove('ecoNote');
                $event->getForm()->remove('gesNote');
            }
        });
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Property::class,
            'allow_extra_fields' => true,
        ]);
    }
}
