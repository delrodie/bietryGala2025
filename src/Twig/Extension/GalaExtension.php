<?php

namespace App\Twig\Extension;

use App\Twig\Runtime\GalaRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class GalaExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('position_ticket', [GalaRuntime::class, 'ticketPosition']),
            new TwigFilter('flag_ticket', [GalaRuntime::class, 'ticketFlag']),
            new TwigFilter('statut_ticket', [GalaRuntime::class, 'ticketStatut']),
            new TwigFilter('identite_invite', [GalaRuntime::class, 'identiteInvite']),
            new TwigFilter('identite_css', [GalaRuntime::class, 'identiteCss']),
            new TwigFilter('nombre_membre', [GalaRuntime::class, 'ticketMembre']),
            new TwigFilter('nombre_invite', [GalaRuntime::class, 'ticketInvite']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('position_ticket', [GalaRuntime::class, 'ticketPosition']),
        ];
    }
}
