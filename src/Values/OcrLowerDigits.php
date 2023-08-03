<?php

namespace Shimoning\DskCvs\Values;

use Shimoning\DskCvs\Utilities\CheckDigit;

/**
 * OCR 印字欄 (下段)
 * 合計44桁 (内チェックディジット2桁, 本文42桁)
 */
class OcrLowerDigits
{
    /** メーカーコード */
    const MAKER_CODE = '29023';

    private string $_raw;
    private string $_digits;

    private int $_checkDigit1;
    private int $_checkDigit2;

    private string $_freeArea;
    private string $_userData1;
    private string $_userData2;
    private int $_retryCount;

    private string $_blank1; // 固定: 000000000
    private string $_makerCode; // 固定: 29023
    private int $_controlCode;
    private string $_blank2;    // 固定: 00000000

    /**
     * バーコード
     *
     * @param string $barcode
     */
    public function __construct(string $digits)
    {
        $this->_raw = $digits;
        $this->_digits = $digits;

        $this->_checkDigit1 = (int)\substr($digits, 0, 1);
        $this->_checkDigit2 = (int)\substr($digits, 1, 1);

        $this->_freeArea = \substr($digits, 2, 16);
        $this->_userData1 = \substr($digits, 2, 6);
        $this->_userData2 = \substr($digits, 8, 10);
        $this->_retryCount = (int)\substr($digits, 18, 1);

        $this->_blank1 = \substr($digits, 19, 9); // 固定: 000000000
        $this->_makerCode = \substr($digits, 28, 5);
        $this->_controlCode = \substr($digits, 33, 3);
        $this->_blank2 = \substr($digits, 36, 8); // 固定: 00000000
    }

    /**
     * 入力されたチェックディジットと計算した結果を照合する
     * @return bool
     */
    public function validate(): bool
    {
        $setCheckDigit1 = -1;
        $setCheckDigit2 = -1;
        [$calculatedCheckDigit1, $calculatedCheckDigit2] = CheckDigit::ocr(
            $this->_digits,
            $setCheckDigit1,
            $setCheckDigit2,
        );

        return $calculatedCheckDigit1 === $setCheckDigit1
            && $calculatedCheckDigit2 === $setCheckDigit2;
    }

    /**
     * 生データ取得
     * @return string
     */
    public function getRaw(): string
    {
        return $this->_raw;
    }

    /**
     * 数字列取得
     * @return string
     */
    public function getDigits(): string
    {
        return $this->_digits;
    }

    /**
     * チェックディジット1
     * @return int
     */
    public function getCheckDigit1(): int
    {
        return $this->_checkDigit1;
    }

    /**
     * チェックディジット2
     * @return int
     */
    public function getCheckDigit2(): int
    {
        return $this->_checkDigit2;
    }

    /**
     * 自由使用欄
     * @return string
     */
    public function getFreeArea(): string
    {
        return $this->_freeArea;
    }

    /**
     * ユーザー使用欄1
     * @return string
     */
    public function getUserData1(): string
    {
        return $this->_userData1;
    }

    /**
     * ユーザー使用欄2
     * @return string
     */
    public function getUserData2(): string
    {
        return $this->_userData2;
    }

    /**
     * 再発行区分 (回数)
     * @return int
     */
    public function getRetryCount(): int
    {
        return $this->_retryCount;
    }

    /**
     * 00000 固定
     * @return string
     */
    public function getBlank1(): string
    {
        return $this->_blank1;
    }

    /**
     * メーカーコード (固定: 929023)
     * @return string
     */
    public function getMakerCode(): string
    {
        return $this->_makerCode;
    }

    /**
     * 指定の管理コード
     * @return string
     */
    public function getControlCode(): string
    {
        return $this->_controlCode;
    }

    /**
     * 000000000 固定
     * @return string
     */
    public function getBlank2(): string
    {
        return $this->_blank2;
    }

    /**
     * 基本情報から OCR 印字欄下段の数字列を生成する
     *
     * @param string $freeArea
     * @param integer $retryCount
     * @param string $controlCode
     * @return self
     */
    static public function build(
        string $freeArea,
        int $retryCount,
        string $controlCode,
    ): self {
        $base = $freeArea
                . $retryCount
                . '000000000'
                . self::MAKER_CODE
                . $controlCode
                . '00000000';

        [$checkDigit1, $checkDigit2] = CheckDigit::ocr($base);

        return new self($checkDigit1 . $checkDigit2 . $base);
    }
}
