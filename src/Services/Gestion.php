<?php

namespace App\Services;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Self_;

class Gestion
{
    const STATUT_MEMBRE = "MEMBRE";
    const STATUT_INVITE = "INVITE";
    const FLAG_INSTALLE = 1;
    const FLAG_NONINSTALLE = 0;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TicketRepository $ticketRepository,
    )
    {
    }

    public function saveParticipation(object $participant)
    {
        $participant->setMontant((int)$participant->getPlace()*70000);
        $nb_place = $participant->getPlace();

        if ( $nb_place>=1){
            for ($i = 0; $i < $nb_place; $i++) {
                $ticket = new Ticket();
                $ticket->setTelephone($participant->getTelephone());
                $i === 0 ? $ticket->setStatut(self::STATUT_MEMBRE) : $ticket->setStatut(self::STATUT_INVITE);
                $ticket->setFlag(self::FLAG_NONINSTALLE);
                $ticket->setCode($this->codeTicket());

                $participant->addTicket($ticket);

//                $this->entityManager->persist($ticket);
            }
        }

        return $participant;
    }

    public function codeTicket(): string
    {
        do{
            $code = str_pad((int)random_int(0, 999), 3, '0', STR_PAD_LEFT);
            $matricule = date('ymd') . $code;
        } while($this->ticketRepository->findOneBy(['code' => $matricule]));

        return $matricule;
    }
}