
<?php

namespace Shimoning\DskCvs\Constants;

enum ErrorCode: int
{
    case OVER_THRESHOLD     = 1;

    case INVALID_1          = 2;
    case INVALID_2          = 3;
    case INVALID_3          = 4;
    case INVALID_4          = 5;

    case UNDER_THRESHOLD    = 6;
    case MISSING            = 7;

    public function description(): string
    {
        return match ($this) {
            self::OVER_THRESHOLD    => '値が最大桁数を超えている',

            self::INVALID_1         => '値に使用できない文字が含まれている',
            self::INVALID_2         => '値に使用できない文字が含まれている',
            self::INVALID_3         => '値に使用できない文字が含まれている',
            self::INVALID_4         => '値に使用できない文字が含まれている',

            self::UNDER_THRESHOLD   => '値の桁数が足りない',
            self::MISSING           => '値が設定されていない',
        };
    }
}
