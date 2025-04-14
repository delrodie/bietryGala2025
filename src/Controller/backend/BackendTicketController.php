<?php

declare(strict_types=1);

namespace App\Controller\backend;

use App\Form\TicketTableType;
use App\Services\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/backend/ticket')]
class BackendTicketController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories, private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/{code}', name:'app_backend_ticket_table', methods: ['GET', 'POST'])]
    public function table(Request $request, $code): Response
    {
        $ticket = $this->allRepositories->getTicketByCode($code);
        if (!$ticket){
            notyf()->error("Le ticket {$ticket} n'a pas été trouvé");
            return $this->redirectToRoute('app_backend_dashboard');
        }

        $form = $this->createForm(TicketTableType::class, $ticket);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $this->entityManager->flush();

            notyf()
                ->position('x', 'center')
                ->position('y', 'center')
                ->success("Le ticket {$ticket->getCode()} a été modifié avec succès!");

            return $this->redirectToRoute('app_backend_dashboard');
        }

        return $this->render('backend/ticket_table.html.twig', [
            'ticket' => $ticket,
            'form' => $form
        ]);
    }
}
