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
            self::PRELIMINARY    => '速報',

            self::INVALID_1         => '値に使用できない文字が含まれている',
            self::INVALID_2         => '値に使用できない文字が含まれている',
            self::INVALID_3         => '値に使用できない文字が含まれている',
            self::INVALID_4         => '値に使用できない文字が含まれている',

            self::UNDER_THRESHOLD   => '値の桁数が足りない',
            self::MISSING           => '値が設定されていない',
        };
    }
}
