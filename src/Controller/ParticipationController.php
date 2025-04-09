<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Services\AllRepositories;
use App\Services\Gestion;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/participation')]
class ParticipationController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Gestion $_gestion,
        private AllRepositories $allRepositories
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

            return $this->redirectToRoute('app_participation_show', ['telephone' => $participation->getTelephone()]);
        }
        return $this->render('frontend/participantion_new.html.twig', [
            'form' => $form->createView(),
            'participation' => $participation
        ]);
    }

    #[Route('/{telephone}', name:'app_participation_show', methods:['GET'])]
    public function show($telephone)
    {
        $participant = $this->allRepositories->getParticipantByTelephone($telephone);

        if (!$participant) {
            throw new NotFoundHttpException("Aucun participant trouvé avec le téléphone : $telephone");
        }

        $tickets = $participant->getTickets();

        $ticketMembre = null;
        $ticketsInvites = [];

        foreach ($tickets as $ticket) {
            if ($ticket->getStatut() === $this->_gestion::STATUT_MEMBRE) {
                $ticketMembre = $ticket;
            } else {
                $ticketsInvites[] = $ticket;
            }
        }

        return $this->render('frontend/participation_show.html.twig',[
            'participant' => $participant,
            'ticket_membre' => $ticketMembre,
            'ticket_invite' => $ticketsInvites
        ]);
    }
}
