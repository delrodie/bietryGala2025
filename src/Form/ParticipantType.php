<?php

namespace App\Form;

use App\Entity\Participant;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ParticipantType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => "off", 'placeholder'=>"Nom de famille"],
                'label' => "Nom de famille"
            ])
            ->add('prenom', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete' => "off", 'placeholder'=>"Prenoms"],
                'label' =>  "Prenoms"
            ])
            ->add('telephone', TelType::class, [
                'attr' => ['class' => 'form-control', 'autocomplete'=>'off', 'placeholder'=>"Numero de telephone"],
                'label' => "Téléphone"
            ])
            ->add('club', TextType::class, [
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'placeholder'=>"Club"],
                'label' => "Votre club"
            ])
//            ->add('montant')
            ->add('place', ChoiceType::class,[
                'attr' => ['class' => 'form-select', 'placeholder'=>"Nombre de ticket"],
                'label' => "Nombre de tickets",
                'choices' => [
                    '-- Selectionnez --' => '',
                    1 => 1,
                    2 => 2,
                    3 => 3,
                    4 => 4,
                    5 => 5,
                    6 => 6,
                    7 => 7,
                    8 => 8,
                    9 => 9,
                    10 => 10
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Participant::class,
        ]);
    }
}
