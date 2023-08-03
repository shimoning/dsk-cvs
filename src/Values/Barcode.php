<?php

namespace Shimoning\DskCvs\Values;

use Shimoning\DskCvs\Utilities\CheckDigit;

class Barcode
{
    /** 識別子 */
    const IDENTIFIER = '91';
    /** メーカーコード */
    const MAKER_CODE = '929023';

    private string $_raw;
    private string $_barcode;
    private string $_identifier;
    private string $_makerCode;
    private string $_companyCode;
    private string $_freeArea;
    private string $_userData1;
    private string $_userData2;
    private int $_retryCount;
    private string $_paymentDueDate;
    private bool $_revenueStamp;
    private int $_amount;
    private int $_checkDigit;

    /**
     * バーコード
     *
     * @param string $barcode
     */
    public function __construct(string $barcode)
    {
        $this->_raw = $barcode;
        if (\strpos($barcode, 'EAN') !== false) {
            $barcode = \ltrim($barcode, 'EAN');
        }
        $this->_barcode = $barcode;

        $this->_identifier = \substr($barcode, 0, 2); // 固定: 91
        $this->_makerCode = \substr($barcode, 2, 6); // 固定: 929023
        $this->_companyCode = \substr($barcode, 8, 5);
        $this->_freeArea = \substr($barcode, 13, 16);
        $this->_userData1 = \substr($barcode, 13, 6);
        $this->_userData2 = \substr($barcode, 19, 10);
        $this->_retryCount = (int)\substr($barcode, 29, 1);
        $this->_paymentDueDate = \substr($barcode, 30, 6);
        $this->_revenueStamp = (bool)\substr($barcode, 36, 1);
        $this->_amount = (int)\substr($barcode, 37, 6);
        $this->_checkDigit = (int)\substr($barcode, 43, 1);
    }

    /**
     * 入力されたチェックディジットと計算した結果を照合する
     * @return bool
     */
    public function validate(): bool
    {
        $setCheckDigit = -1;
        $calculatedCheckDigit = CheckDigit::barcode($this->_barcode, $setCheckDigit);

        return $setCheckDigit === $calculatedCheckDigit;
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
     * EAN を除外したバーコード全部 を取得
     * @return string
     */
    public function getBarcode(): string
    {
        return $this->_barcode;
    }

    /**
     * 識別子 (固定: 91)
     * @return string
     */
    public function getIdentifier(): string
    {
        return $this->_identifier;
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
     * 企業コード
     * @return string
     */
    public function getCompanyCode(): string
    {
        return $this->_companyCode;
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
     * 支払期日 (YYMMDD)
     * 無期限の場合は 999999
     * @return string
     */
    public function getPaymentDueDate(): string
    {
        return $this->_paymentDueDate;
    }

    /**
     * 印紙フラグ
     * @return boolean
     */
    public function hasRevenueStamp(): bool
    {
        return $this->_revenueStamp;
    }

    /**
     * 支払い金額 (円)
     * @return int
     */
    public function getAmount(): int
    {
        return $this->_amount;
    }

    /**
     * チェックディジット
     * @return int
     */
    public function getCheckDigit(): int
    {
        return $this->_checkDigit;
    }

    /**
     * 基本情報からバーコードの数字列を生成する
     *
     * @param string $companyCode
     * @param string $freeArea
     * @param integer $retryCount
     * @param string $paymentDueDate
     * @param integer $revenueStamp
     * @param integer $amount
     * @return self
     */
    static public function build(
        string $companyCode,
        string $freeArea,
        int $retryCount,
        string $paymentDueDate,
        int $revenueStamp,
        int $amount,
    ): self {
        $base = self::IDENTIFIER
                . self::MAKER_CODE
                . $companyCode
                . $freeArea
                . $retryCount
                . $paymentDueDate
                . $revenueStamp
                . \sprintf('%06d', $amount);

        $checkDigit = CheckDigit::barcode($base);

        return new self($base . $checkDigit);
    }
}
