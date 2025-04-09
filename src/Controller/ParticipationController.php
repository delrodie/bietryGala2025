<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Services\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Gestion $_gestion,
    )
    {
    }

    #[Route('/', name: 'app_participation_new', methods:['GET','POST'])]
    public function create(Request $request): Response
    {
        $participation = new Participant();
        $form = $this->createForm(ParticipantType::class, $participation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->_gestion->saveParticipation($participation);
            $this->entityManager->persist($participation);
            $this->entityManager->flush();

            dd($participation);
        }
        return $this->render('frontend/participantion_new.html.twig', [
            'form' => $form->createView(),
            'participation' => $participation
        ]);
    }
}
