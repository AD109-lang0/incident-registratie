<?php

namespace App;

enum UserRole: string
{
    case User = 'user';
    case Melder = 'melder';
    case Coordinator = 'coordinator';
    case Beheerder = 'beheerder';
}
