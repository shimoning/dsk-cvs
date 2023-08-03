
<?php

namespace Shimoning\DskCvs\Constants;

enum TransferFeeBurdenType: int
{
    case COMPANY  = 2;

    public function description(): string
    {
        return match ($this) {
            self::COMPANY    => '企業負担',
        };
    }
}
