<?php

declare(strict_types=1);

namespace App\Controller;

use App\Services\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/checking')]
class CheckingController extends AbstractController
{
    public function __construct(
        private AllRepositories $allRepositories,
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
}
