<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\AllRepositories;
use App\Services\Gestion;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/checking')]
class CheckingController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories, private readonly Gestion $_gestion,
    )
    {
    }

    #[Route('/', name:'app_checking_search', methods: ['GET'])]
    public function search(Request $request): Response
    {
        $code = $request->get('code');
        $ticket = $this->allRepositories->getTicketByCode($code);
        if (!$ticket){
            throw new NotFoundHttpException("Oups! Le ticket {$code} n'a pas été trouvé");
        }

        return $this->render('frontend/checking_search.html.twig',[
            'ticket' => $ticket,
        ]);
    }

    #[Route('/ticket', name: 'app_checking_ticket', methods:['GET'])]
    public function ticket(Request $request): Response
    {
        $session = $request->getSession();
        $sessionTelephone = $session->get('telephone');
        if (!$sessionTelephone){
            $telephone = $request->get('telephone');
            if (!$telephone){
                return $this->render('frontend/checking_ticket.html.twig');
            }
        }else{
            $telephone = $sessionTelephone;
        }


        $ticket = $this->allRepositories->getTicketByTelephone($telephone);
        if (!$ticket){
            throw  new NotFoundHttpException("Aucun ticket n'est associé au numero: {$telephone}");
        }

        $session->set('telephone', $telephone);

        if ($ticket->getStatut() === $this->_gestion::STATUT_MEMBRE){
            return $this->redirectToRoute('app_participation_show',['telephone' => $telephone]);
        }

        return $this->render('frontend/checking_search.html.twig',[
            'ticket' => $ticket,
        ]);

    }
}
