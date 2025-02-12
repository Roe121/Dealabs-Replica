<?php
namespace App\Enum;

enum DealStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case INACTIVE = 'inactive';
    case EXPIRED = 'expired';
    case DELETED = 'deleted';
}
