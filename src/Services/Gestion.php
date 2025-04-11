<?php

namespace App\Services;

use App\Entity\Ticket;
use App\Repository\TicketRepository;
use App\Services\AllRepositories;
use Doctrine\ORM\EntityManagerInterface;
use Endroid\QrCode\Builder\Builder;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\RoundBlockSizeMode;
use Endroid\QrCode\Writer\PngWriter;
use Flasher\Notyf\Prime\NotyfInterface;
use Flasher\Prime\FlasherInterface;
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
        private TicketRepository       $ticketRepository,
        string                         $qrCodeDirectory,
        private UrlGeneratorInterface  $urlGenerator, private readonly AllRepositories $allRepositories,
        private FlasherInterface $flasher,
        private NotyfInterface $notyf,
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
            margin: 25,
            roundBlockSizeMode: RoundBlockSizeMode::Margin,
            logoPath: __DIR__.'/../../public/assets/image/fanion.png',
            logoResizeToWidth: 80,
            logoPunchoutBackground: true,
        );
        $result = $builder->build();

        $filename = $code . '.png';
        $path = $this->qrCodeDirector . '/' . $filename;

        $result->saveToFile($path);

        return $filename;
    }

    public function existParticipant(object $participant): bool
    {
        $telephone = $participant->getTelephone();
        $verif = $this->allRepositories->getParticipantByTelephone($telephone);
        if ($verif){
            $this->notyf
                ->position('x', 'center')
                ->position('y', 'center')
                ->error("Ce numero de téléphone {$telephone} a déjà été enregistré. Veuillez enregistrer un autre numéro", ['position', 'bottom-left'], 'Participant existe déjà');
            return true;
        }
        return false;
    }

    public function existTelephone(object $ticket): bool
    {
        $telephone = $ticket->getTelephone();
        $verif = $this->allRepositories->getTicketByTelephone($telephone);
        $notification =  $this->notyf
            ->position('x', 'center')
            ->position('y', 'center');
        if ($verif){
                $notification->error("Le numéro de telephone {$telephone} a déjà été attribué à un qrCode.",[],'Echec!');
            return true;
        }

        $notification->success("Vous pouvez transferer ce code au concerné en cliquant sur 'Partager'",[], "Succès!");
        return false;
    }

    public function notificationTicketInvite($ticket): void
    {
        if ($ticket){
            $this->notyf
                ->position('x', 'center')
                ->position('y', 'center')
                ->info("Vous pouvez attribuer les qrCodes à vos invités, puis les leur transmettre via WhatsApp.", [], 'Félicitations');
        }
    }
}