<?php

namespace Aljeerz\PhpSatim\Support\Enums;

enum SatimOrderStatus: string
{
    case Pending = 'pending';
    case Paid = 'paid';
    case PartiallyPaid = 'partially_paid';
    case Refunded = 'refunded';
    case Cancelled = 'cancelled';
    case Failed = 'failed';
    case Declined = 'declined';

    public static function fromOrderStatusCode(string|int $code): self
    {
        $code = (int)$code;
        if ($code == 0) {
            return self::Pending;
        } else if ($code == 1) {
            return self::PartiallyPaid;
        } elseif ($code == 2) {
            return self::Paid;
        } else if ($code == 4) {
            return self::Refunded;
        } else if ($code == 5) {
            // Provided Infos
            return self::Pending;
        } else if ($code == 6) {
            return self::Declined;
        } else if ($code == 7) {
            return self::Cancelled;
        } else {
            return self::Failed;
        }
    }
}
