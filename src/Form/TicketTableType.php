<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketTableType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('telephone', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off"],
                'label' => "Numéro de telephone"
            ])
            ->add('code', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'readonly' => true, 'style'=>'color: darkred;'],
                'label' => "Code ticket"
            ])
            ->add('nom', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off"],
                'label' => "Nom de famille"
            ])
            ->add('prenom', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off"],
                'label' => "Prénoms"
            ])
//            ->add('media')
            ->add('place', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off"],
                'label' => "Table affectée"
            ])
            ->add('statut', TextType::class,[
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'readonly' => true],
                'label' => "Statut"
            ])
//            ->add('flag')
//            ->add('createdAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('updatedAt', null, [
//                'widget' => 'single_text',
//            ])
//            ->add('participant', EntityType::class, [
//                'class' => Participant::class,
//                'choice_label' => 'nom',
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
