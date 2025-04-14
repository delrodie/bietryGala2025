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

    public function getTicketByCode(string $code)
    {
        return $this->ticketRepository->findOneBy(['code' => $code]);
    }

    public function getTicketByTelephone(string $telephone)
    {
        return $this->ticketRepository->findOneBy(['telephone' => $telephone]);
    }

    public function getAllTickets()
    {
        return $this->ticketRepository->findAllTicket();
    }
}