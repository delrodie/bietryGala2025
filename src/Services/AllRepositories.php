<?php

namespace App\Services;

use App\Repository\ParticipantRepository;
use App\Repository\TicketRepository;

class AllRepositories
{
    public function __construct(
        private ParticipantRepository $participantRepository,
        private TicketRepository $ticketRepository
    )
    {
    }

    public function getParticipantByTelephone(string $telephone)
    {
        return $this->participantRepository->findOneBy(['telephone' => $telephone]);
    }
}