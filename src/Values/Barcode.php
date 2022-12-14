<?php

namespace Shimoning\DskCvs\Values;

class Barcode
{
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
     * @param string $pw
     * @param Date|null $fromDate
     * @param Date|null $toDate
     */
    public function __construct(string $barcode)
    {
        $this->_raw = $barcode;
        if (\strpos($barcode, 'EAN') !== false) {
            $barcode = \ltrim($barcode, 'EAN');
        }
        $this->_barcode = $barcode;

        $this->_identifier = \substr($barcode, 0, 2);
        $this->_makerCode = \substr($barcode, 2, 6);
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
     * モジュラス10 - ウェイト1,3
     * @return bool
     */
    public function validate(): bool
    {
        $arrayedBarcode = \array_reverse(\str_split($this->_barcode));
        $setCheckDigit = \intval(\substr($this->_barcode, -1));

        $sum = 0;
        foreach ($arrayedBarcode as $i => $digit) {
            $sum += \intval($digit) * ($i % 2 === 0 ? 1 : 3);
        }
        $sum -= $setCheckDigit;
        $last1 = $sum % 10;

        $calculatedCheckDigit = $last1 === 0 ? 0 : 10 - $last1;

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
     * 支払い期日 (YYMMDD)
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
}
