<?php

namespace App\Form;

use App\Entity\Lease;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use Symfony\Component\Validator\Constraints\Range;

class LeaseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $irlChoices = [];
        foreach ($options['irls'] as $irl) {
            $period = $irl['period'];
            $value  = $irl['value'];
            [$year, $quarter] = explode('-Q', $period);
            $label = $year . '-T' . $quarter . ' : ' . number_format($value, 2, ',', ' ');
            $encoded = $period . '|' . $value;
            $irlChoices[$label] = $encoded;
        }

        $builder
            ->add('type', ChoiceType::class, [
                'label' => 'Type de bail',
                'choices' => [
                    'Vide' => 'vide',
                    'Meublé' => 'meuble',
                    'Commercial' => 'commercial',
                ],
                'constraints' => [new NotBlank()],
            ])
            ->add('rentingAmount', NumberType::class, [
                'label' => 'Loyer HC (€)',
                'scale' => 2,
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('chargesAmount', NumberType::class, [
                'label' => 'Charges (€)',
                'scale' => 2,
                'required' => false,
            ])
            ->add('securityDeposit', NumberType::class, [
                'label' => 'Dépôt de garantie (€)',
                'scale' => 2,
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('leasedAt', \Symfony\Component\Form\Extension\Core\Type\DateType::class, [
                'label' => 'Date de début du bail',
                'widget' => 'single_text',
                'input' => 'datetime_immutable',
                'constraints' => [new NotBlank()],
            ])
            ->add('duration', IntegerType::class, [
                'label' => 'Durée (mois)',
                'constraints' => [new NotBlank(), new Positive()],
            ])
            ->add('dateOfPayment', IntegerType::class, [
                'label' => 'Jour de paiement du loyer',
                'constraints' => [
                    new NotBlank(),
                    new Range(['min' => 1, 'max' => 28]),
                ],
            ])
            ->add('irlSelection', ChoiceType::class, [
                'label'    => 'Indice IRL de référence',
                'choices'  => $irlChoices,
                'mapped'   => false,
                'constraints' => [new NotBlank()],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Lease::class,
            'irls'       => [],
        ]);
    }
}
