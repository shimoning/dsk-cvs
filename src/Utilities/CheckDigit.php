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

    /**
     * OCR用のチェックディジット計算
     * 先頭2桁がチェックディジット + 37桁 or 42桁 (合計 39 or 44)
     *
     * @param integer|string $numeric
     * @param integer $setCheckDigit1 値にチェックディジットが含まれていた場合 0 以上の値が入る
     * @param integer $setCheckDigit2 値にチェックディジットが含まれていた場合 0 以上の値が入る
     * @return bool|array<int, int>
     */
    public static function ocr(
        int|string $numeric,
        &$setCheckDigit1 = -1,
        &$setCheckDigit2 = -1,
    ): array|bool {
        $digits = (string)$numeric;
        $length = \strlen($digits);
        if (
            $length !== 37 && $length !== 39 &&
            $length !== 42 && $length !== 44
        ) {
            return false;
        }

        $setCheckDigit1 = -1;
        $setCheckDigit2 = -1;
        if ($length === 39 || $length === 44) {
            // チェックディジットがついているので削除する
            $setCheckDigit1 = \intval(\substr($digits, 0, 1));
            $setCheckDigit2 = \intval(\substr($digits, 1, 1));
            $digits = \substr($digits, 2);
        }

        $arrayedDigit = \str_split($digits);

        $sumForCD1 = 0;
        $sumForCD2 = 0;
        $weightForCD2 = 9;
        $weightForCD1 = 3;
        foreach ($arrayedDigit as $i => $digit) {
            if ($weightForCD2 < 2) {
                $weightForCD2 = 9;
            }
            if ($weightForCD1 > 9) {
                $weightForCD1 = 2;
            }

            $isOdd = $i % 2 === 1;
            $sumForCD1 += $digit * ($isOdd ? $weightForCD1++ : 1);
            $sumForCD2 += $digit * ($isOdd ? 1 : $weightForCD2-- );
        }
        $calculatedCheckDigit2 = $sumForCD2 % 10;
        $calculatedCheckDigit1 = ($sumForCD1 + $calculatedCheckDigit2 * 2) % 11;

        return [
            $calculatedCheckDigit1,
            $calculatedCheckDigit2,
        ];
    }
}
