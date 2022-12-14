<?php

namespace Shimoning\DskCvs\Constants;

enum RecordType: string
{
    case PRELIMINARY            = '01';
    case CONFIRMED              = '02';
    case PRELIMINARY_CANCELED   = '03';

    case POSTAL_TRANSFER        = '12';
    case POSTAL_TRANSFER_FIXED  = '22';

    case REAL                   = '81';

    public function description(): string
    {
        return match ($this) {
            self::PRELIMINARY           => '速報',
            self::CONFIRMED             => '確定',
            self::PRELIMINARY_CANCELED  => '速報取消',
            self::POSTAL_TRANSFER       => '郵便振替',
            self::POSTAL_TRANSFER_FIXED => '郵便振替訂正',
            self::REAL                  => 'リアルデータ',
        };
    }
}
