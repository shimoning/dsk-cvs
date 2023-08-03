<?php

namespace Shimoning\DskCvs\Values;

use Shimoning\DskCvs\Utilities\CheckDigit;

/**
 * OCR 印字欄 (上段)
 * 合計39桁 (内チェックディジット2桁, 本文37桁)
 */
class OcrUpperDigits
{
    /** 郵便振替代行センター */
    const ACCOUNT_NUMBER_FOR_POSTAL = '00150900584';
    /** DSK電算システム収納代行センター */
    const ACCOUNT_NUMBER_FOR_DSK    = '00110901221';

    private string $_raw;
    private string $_digits;

    private int $_checkDigit1;
    private int $_checkDigit2;

    private string $_accountNumber;
    private string $_blank1;
    private int $_amount;
    private int $_transferFeeType;
    private string $_blank2;
    private string $_companyCode;

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

        $this->_accountNumber = \substr($digits, 2, 11);
        $this->_blank1 = \substr($digits, 13, 5); // 固定: 00000
        $this->_amount = (int)\substr($digits, 18, 6);
        $this->_transferFeeType = (int)\substr($digits, 24, 1); // 固定: 2
        $this->_blank2 = \substr($digits, 25, 9); // 固定: 000000000
        $this->_companyCode = \substr($digits, 34, 5);
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
     * 電算システム口座番号
     * @return string
     */
    public function getAccountNumber(): string
    {
        return $this->_accountNumber;
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
     * 支払金額
     * @return int
     */
    public function getAmount(): int
    {
        return $this->_amount;
    }

    /**
     * 振込料金負担区分 (2 固定)
     * @return int
     */
    public function getTransferFeeType(): int
    {
        return $this->_transferFeeType;
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
     * 企業コード
     * @return string
     */
    public function getCompanyCode(): string
    {
        return $this->_companyCode;
    }

    /**
     * 基本情報から OCR 印字欄上段の数字列を生成する
     *
     * @param string $accountNumber
     * @param integer $amount
     * @param string $companyCode
     * @param integer|null $transferFeeType (仕様上は 2 で固定)
     * @return self
     */
    static public function build(
        string $accountNumber,
        int $amount,
        string $companyCode,
        ?int $transferFeeType = 2,
    ): self {
        $base = $accountNumber
                . '00000'
                . \sprintf('%06d', $amount)
                . $transferFeeType
                . '000000000'
                . $companyCode;

        [$checkDigit1, $checkDigit2] = CheckDigit::ocr($base);

        return new self($checkDigit1 . $checkDigit2 . $base);
    }
}
