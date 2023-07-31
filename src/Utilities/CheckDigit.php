<?php

namespace Shimoning\DskCvs\Utilities;

/**
 * ディジット計算ツール
 */
class CheckDigit
{
    /**
     * バーコードのチェックディジットを計算
     * バーコード43桁 + チェックディジット1桁
     * (モジュラス10 ウェイト1,3)
     *
     * @param integer|string $number
     * @param $setCheckDigit バーコードにチェックディジットが含まれていた場合 0 以上の値が入る
     * @return integer|false
     */
    public static function barcode(int|string $numeric, &$setCheckDigit = -1): int|bool
    {
        $barcode = (string)$numeric;
        $length = \strlen($barcode);
        if ($length !== 43 && $length !== 44) {
            return false;
        }

        $setCheckDigit = -1;
        if ($length === 44) {
            // チェックディジットがついているので削除する
            $setCheckDigit = \intval(\substr($barcode, -1));
            $barcode = \substr($barcode, 0, -1);
        }

        $arrayedBarcode = \array_reverse(\str_split($barcode));

        $sum = 0;
        foreach ($arrayedBarcode as $i => $digit) {
            $sum += \intval($digit) * ($i % 2 === 0 ? 3 : 1);
        }
        $last1 = $sum % 10;

        return $last1 === 0 ? 0 : 10 - $last1;
    }
}
