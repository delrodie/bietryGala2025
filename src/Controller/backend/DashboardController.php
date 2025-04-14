<?php

declare(strict_types=1);

namespace App\Controller\backend;

use App\Services\AllRepositories;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DashboardController extends AbstractController
{
    public function __construct(private readonly AllRepositories $allRepositories)
    {
    }

    #[Route('/dashboard', name:'app_backend_dashboard')]
    public function dashboard(): Response
    {
        return $this->render('backend/dashboard.html.twig', [
            'tickets' => $this->allRepositories->getAllTickets()
        ]);
    }
}
