<?php

namespace App\Twig\Runtime;

use App\Services\Gestion;
use Twig\Extension\RuntimeExtensionInterface;

class GalaRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private Gestion $_gestion,
    )
    {
        // Inject dependencies if needed
    }

    public function ticketPosition($value)
    {
        return $value ?? 'NON-DEFINI';
    }

    public function ticketFlag($value)
    {
        return $value === 0 ? "NON" : "OUI";
    }

    public function ticketStatut($value)
    {
        return $value === $this->_gestion::STATUT_MEMBRE ? $this->_gestion::STATUT_MEMBRE : $this->_gestion::STATUT_INVITE;
    }

    public function identiteInvite($value)
    {
        $nom = $value->getNom()? $value->getNom() : null;
        $prenom = $value->getPrenom() ? $value->getPrenom() : null;
        return $nom.$prenom ? strtoupper($nom). ' '.strtoupper($prenom) : "Nom à définir";
    }

    public function identiteCss($value)
    {
        $nom = $value->getNom()? $value->getNom() : null;
        $prenom = $value->getPrenom() ? $value->getPrenom() : null;
        return $nom.$prenom ? '' : "anonyme";
    }
}
