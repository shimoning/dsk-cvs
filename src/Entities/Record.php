<?php

namespace Shimoning\DskCvs\Entities;

use DateTimeImmutable;
use Shimoning\DskCvs\Values\Date;
use Shimoning\DskCvs\Values\Time;
use Shimoning\DskCvs\Values\Barcode;
use Shimoning\DskCvs\Constants\RecordType;
use Shimoning\DskCvs\Constants\CompanyCode;

class Record
{
    private array $_raw;
    private RecordType $_recordType;

    private Date $_receivedDate;
    private ?Time $_receivedTime;
    private DateTimeImmutable $_receivedDateTime;

    private Barcode $_barcode;
    private string $_userData1;
    private string $_userData2;

    private bool $_revenueStamp;
    private int $_amount;

    private CompanyCode|string $_companyCode;
    private string $_companyName;
    private string $_shopCode;

    private ?Date $_paysDate;
    private Date $_createdDate;

    /**
     * 収納データ
     *
     * @param string[] $record
     */
    public function __construct(array $record)
    {
        $this->_raw = $record;
        $this->_recordType = RecordType::tryFrom($record[0]);
        $this->_receivedDate = new Date($record[1]);
        $this->_receivedTime = $record[2] > 0 ? new Time($record[2]) : null;
        $this->_receivedDateTime = new DateTimeImmutable($record[1] . ($record[2] > 0 ? $record[2] : ''));

        $this->_barcode = new Barcode($record[3]);
        $this->_userData1 = $record[4];
        $this->_userData2 = $record[5];

        $this->_revenueStamp = (bool)$record[6];
        $this->_amount = (int)$record[7];

        $this->_companyCode = CompanyCode::tryFrom($record[8]) ?? $record[8];
        $this->_companyName = $record[9];
        $this->_shopCode = $record[10];

        $this->_paysDate = $record[11] > 0 ? new Date($record[11]) : null;
        $this->_createdDate = new Date($record[12]);
    }

    /**
     * 生データ取得
     * @return array
     */
    public function getRaw(): array
    {
        return $this->_raw;
    }

    // TODO: implement toArray() method

    /**
     * 種別
     * @return RecordType
     */
    public function getRecordType(): RecordType
    {
        return $this->_recordType;
    }

    /**
     * 店舗での収納日を取得 (YYYYMMDD)
     * @return Date
     */
    public function getReceivedDate(): Date
    {
        return $this->_receivedDate;
    }

    /**
     * 店舗での収納時刻を取得 (HHMM)
     * 種別が 12, 22 の場合は null
     * @return Time|null
     */
    public function getReceivedTime(): ?Time
    {
        return $this->_receivedTime;
    }

    /**
     * 店舗での収納日時を取得
     * @return DateTimeImmutable
     */
    public function getReceivedDateTime(): DateTimeImmutable
    {
        return $this->_receivedDateTime;
    }

    /**
     * EAN + バーコード情報(44桁)をそのまま設定
     * 種別が 12 の場合は、支払期限の設定の有無にかかわらずバーコード情報の31-36桁(支払期限)が999999としてセットされます。
     * @return Barcode
     */
    public function getBarcode(): Barcode
    {
        return $this->_barcode;
    }

    /**
     * ユーザー使用欄1
     * バーコード情報の14-19桁目
     * @return string
     */
    public function getUserData1(): string
    {
        return $this->_userData1;
    }

    /**
     * ユーザー使用欄2
     * バーコード情報の20-30桁目 (30桁目は再発行区分)
     * @return string
     */
    public function getUserData2(): string
    {
        return $this->_userData2;
    }

    /**
     * 印紙の有無
     * バーコード情報の37桁目
     * @return boolean
     */
    public function hasRevenueStamp(): bool
    {
        return $this->_revenueStamp;
    }

    /**
     * 金額
     * バーコード情報の38-43桁目
     * 種別が22（郵便振替訂正）の場合は、訂正後の金額がセットされます
     * @return boolean
     */
    public function getAmount(): int
    {
        return $this->_amount;
    }

    /**
     * コンビニ本部コード
     * DSKコンビニコード又は郵便局識別
     * @return CompanyCode|string
     */
    public function getCompanyCode(): CompanyCode|string
    {
        return $this->_companyCode;
    }

    /**
     * コンビニ名
     * @return string
     */
    public function getCompanyName(): string
    {
        return $this->_companyName;
    }

    /**
     * コンビニ店舗コード
     * 各コンビニの店舗コード、郵便局コード
     * @return string
     */
    public function getShopCode(): string
    {
        return $this->_shopCode;
    }

    /**
     * 振り込み日
     * データ種別が 02, 12, 22 の場合、資金の振込日 YYYYMMDD
     * 上記以外のデータ種別の場合 0
     * @return Date|null
     */
    public function getPaysDate(): ?Date
    {
        return $this->_paysDate;
    }

    /**
     * データ作成日
     * 01: コンビニでのデータ作成日
     * 02: DSKデータ作成日
     * @return Date
     */
    public function getCreatedDate(): Date
    {
        return $this->_createdDate;
    }
}
