<?php

namespace App;

enum IncidentStatus: string
{
    case Nieuw = 'Nieuw';
    case InOnderzoek = 'In onderzoek';
    case Opgelost = 'Opgelost';
    case Gesloten = 'Gesloten';
}
