<?php

namespace App\Services;

use App\Repository\ParticipantRepository;
use App\Repository\TicketRepository;
use App\Repository\UserRepository;

class AllRepositories
{


    public function __construct(
        private ParticipantRepository $participantRepository,
        private TicketRepository $ticketRepository,
        private UserRepository $userRepository,
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

    public function getUsers(string $username): array
    {
        $getUsers = $this->userRepository->findWithout(['username' => $username]);
        $users = [];

        foreach ($getUsers as $getUser) {
            $roles = $getUser->getRoles()[0] ?? $getUser->getRoles();
            $role = match ($roles) {
                'ROLE_ADMIN' => 'Administrateur',
                'ROLE_SUPER_ADMIN' => 'Super Administrateur',
                default => 'Utilisateur'
            };

            $users[] = [
                'id' => $getUser->getId(),
                'username' => $getUser->getUserIdentifier(),
                'role' => $role,
                'connexion' => $getUser->getConnexion(),
                'lastConnectedAt' => $getUser->getLastConnectedAt(),
            ];
        }

        return $users;
    }
}