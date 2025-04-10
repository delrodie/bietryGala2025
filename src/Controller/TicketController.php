<?php

declare(strict_types=1);

namespace App\Controller;

use App\Form\TicketAffectedType;
use App\Services\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/ticket')]
class TicketController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/', name: 'app_ticket_liste')]
    public function liste(Request $request)
    {
        $session = $request->getSession();
        $session->remove('telephone');

        return $this->redirectToRoute('app_checking_ticket');
    }

    #[Route('/{code}', name:'app_ticket_show', methods:['GET'])]
    public function show($code)
    {
        $ticket = $this->allRepositories->getTicketByCode($code);

        if (!$ticket){
            throw new NotFoundHttpException("Oups!! le ticket recherché n'a pas été trouvé.");
        }

        return $this->render('frontend/checking_search.html.twig',[
            'ticket' => $ticket,
        ]);
    }

    #[Route('/{code}/affecter', name: 'app_ticket_affecter', methods: ['GET','POST'])]
    public function affecter(Request $request, $code): Response
    {
        $ticket = $this->allRepositories->getTicketByCode($code);
        if (!$ticket){
            throw new NotFoundHttpException("Le ticket N°:{$code} n'a pas été trouvé");
        }

        $form = $this->createForm(TicketAffectedType::class, $ticket);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_checking_search', ['code' => $ticket->getCode()]);
        }

        return $this->render('frontend/ticket_affecter.html.twig', [
            'ticket' => $ticket,
            'form' => $form
        ]);
    }
}
