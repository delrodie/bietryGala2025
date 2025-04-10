<?php

namespace App\Form;

use App\Entity\Participant;
use App\Entity\Ticket;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TicketAffectedType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('nom', TextType::class, [
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'placeholder'=>"Nom de famille"],
                'label' => "Nom de famille de l'invité"
            ])
            ->add('prenom', TextType::class, [
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'placeholder'=>"Prénoms"],
                'label' => "Prénoms de l'invité"
            ])
            ->add('telephone', TelType::class, [
                'attr' => ['class' => 'form-control', 'autocomplete'=>"off", 'placeholder'=>""],
                'label' => "Téléphone de l'invité"
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Ticket::class,
        ]);
    }
}
