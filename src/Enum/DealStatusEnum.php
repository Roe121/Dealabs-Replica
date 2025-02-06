<?php
namespace App\Enum;

enum DealStatus: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case DELETED = 'deleted';
}
