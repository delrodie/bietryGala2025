<?php

namespace App\Services;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\Writer\PngWriter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Gestion
{
    const STATUT_MEMBRE = "MEMBRE";
    const STATUT_INVITE = "INVITE";
    const FLAG_INSTALLE = 1;
    const FLAG_NONINSTALLE = 0;

    private string $qrCodeDirector;
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TicketRepository $ticketRepository,
        string $qrCodeDirectory,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
        $this->qrCodeDirector = $qrCodeDirectory;
    }

    public function saveParticipation(object $participant)
    {
        $participant->setMontant((int)$participant->getPlace()*70000);
        $nb_place = $participant->getPlace();

        if ( $nb_place>=1){
            for ($i = 0; $i < $nb_place; $i++) {
                $code = $this->codeTicket();
                $ticket = new Ticket();
                $i === 0 ? $ticket->setStatut(self::STATUT_MEMBRE) : $ticket->setStatut(self::STATUT_INVITE);
                $ticket->setFlag(self::FLAG_NONINSTALLE);
                $ticket->setCode($code);
                $ticket->setMedia($this->qrCodeGenerator($code));
                if ($i === 0){
                    $ticket->setNom($participant->getNom());
                    $ticket->setPrenom($participant->getPrenom());
                    $ticket->setTelephone($participant->getTelephone());
                }

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

    public function qrCodeGenerator(string $code): string
    {
        $url = $this->urlGenerator->generate('app_checking_search', ['code'=> $code], UrlGeneratorInterface::ABSOLUTE_URL);
        $builder = new Builder(
            writer : new PngWriter(),
            data: $url,
            encoding: new Encoding('UTF-8'),
            errorCorrectionLevel: ErrorCorrectionLevel::High,
            size: 350,
            margin: 30,
        );
        $result = $builder->build();

        $filename = $code . '.png';
        $path = $this->qrCodeDirector . '/' . $filename;

        $result->saveToFile($path);

        return $filename;
    }
}