<?php

namespace App\Enums;

enum ArtistRoleType: string
{
    case OWNER = 'owner';
    case ADMIN = 'admin';
    case ARTIST = 'artist';
}
