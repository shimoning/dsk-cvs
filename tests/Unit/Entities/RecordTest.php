<?php

namespace Shimoning\DskCvs\Tests\Unit\Entities;

use PHPUnit\Framework\TestCase;
use Shimoning\DskCvs\Constants\CompanyCode;
use Shimoning\DskCvs\Constants\RecordType;
use Shimoning\DskCvs\Entities\Record;
use Shimoning\DskCvs\Utilities\Csv;

class RecordTest extends TestCase
{
    /**
     * Entity 作成
     * @return void
     */
    public function test_test()
    {
        $string = \file_get_contents(\dirname(\dirname(__DIR__)) . '/data/csv/test.csv');
        $result = Csv::toArray($string);
        $length = count($result);

        /** @var Record[] */
        $records = [];
        for ($i = 1; $i < $length; $i++) {
            $records[] = new Record($result[$i]);
        }

        // 01,20240112,1815,EAN91929023252070000010123499900199999900106050,000001,01234999001,0,10605,010,"�Z�u���|�C���u��",110001,0,20240117
        $this->assertEquals(
            RecordType::PRELIMINARY,
            $records[0]->getRecordType(),
            'record_type',
        );

        $this->assertEquals(
            '20240112',
            $records[0]->getReceivedDate()?->get(),
            'received_date',
        );
        $this->assertEquals(
            '1815',
            $records[0]->getReceivedTime()?->get(),
            'received_time',
        );
        $this->assertEquals(
            '2024-01-12 18:15:00',
            $records[0]->getReceivedDateTime()?->format('Y-m-d H:i:s'),
            'received_datetime',
        );

        $this->assertEquals(
            '91929023252070000010123499900199999900106050',
            $records[0]->getBarcode()?->getBarcode(),
            'barcode.barcode',
        );

        $this->assertEquals(
            '000001',
            $records[0]->getUserData1(),
            'user_data1',
        );
        $this->assertEquals(
            '01234999001',
            $records[0]->getUserData2(),
            'user_data1',
        );

        $this->assertFalse(
            $records[0]->hasRevenueStamp(),
            'revenue_stamp',
        );
        $this->assertSame(
            10605,
            $records[0]->getAmount(),
            'user_data1',
        );

        $this->assertEquals(
            CompanyCode::SEVEN_ELEVEN,
            $records[0]->getCompanyCode(),
            'company_code',
        );
        $this->assertEquals(
            'セブン−イレブン',
            $records[0]->getCompanyName(),
            'company_name',
        );

        $this->assertEquals(
            '110001',
            $records[0]->getShopCode(),
            'shop_code',
        );

        $this->assertNull(
            $records[0]->getPaysDate()?->get(),
            'pays_date',
        );
        $this->assertEquals(
            '20240117',
            $records[0]->getCreatedDate()?->get(),
            'created_date',
        );
        $this->assertEquals(
            '20240117000000',
            $records[0]->getCreatedDate()?->getDatetime()?->format('YmdHis'),
            'created_datetime',
        );

        // 支払い予定日が null じゃないケース
        $this->assertEquals(
            '20240124',
            $records[11]->getPaysDate()?->get(),
            'pays_date',
        );
        $this->assertEquals(
            '20240124000000',
            $records[11]->getPaysDate()?->getDatetime()?->format('YmdHis'),
            'created_datetime',
        );
    }

    /**
     * 本番に近いデータ
     *
     * @return void
     */
    public function test_virtual()
    {
        $string = \file_get_contents(\dirname(\dirname(__DIR__)) . '/data/csv/syuno.csv');
        $result = Csv::toArray($string);
        $length = count($result);

        /** @var Record[] */
        $records = [];
        for ($i = 1; $i < $length; $i++) {
            $records[] = new Record($result[$i]);
        }

        $this->assertEquals(
            RecordType::PRELIMINARY,
            $records[0]->getRecordType(),
            'record_type',
        );
    }
}
