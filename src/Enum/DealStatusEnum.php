<?php
namespace App\Enum;

enum DealStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case DELETED = 'deleted';
}
