<?php

namespace App\Enums;

enum ArtistStatusType: string
{
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case PENDING_INVITE = 'pending_invite';
    case EXITED = 'exited';
}
