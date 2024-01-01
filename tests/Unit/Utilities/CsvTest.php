<?php

namespace Shimoning\DskCvs\Tests\Unit\Utilities;

use PHPUnit\Framework\TestCase;
use Shimoning\DskCvs\Utilities\Csv;

class CsvTest extends TestCase
{
    /**
     * 管理画面で提供されるテストデータ
     * @return void
     */
    public function test_test()
    {
        $string = \file_get_contents(\dirname(\dirname(__DIR__)) . '/data/csv/test.csv');
        $result = Csv::toArray($string);
        $this->assertEquals(17, count($result), 'check count');
        $this->assertEquals(false, Csv::hasError($result), 'check hasError');
    }

    /**
     * 許可されないIPからアクセスされた時のエラーJSON
     * @return void
     */
    public function test_error()
    {
        $string = \file_get_contents(\dirname(\dirname(__DIR__)) . '/data/csv/error.csv');
        $result = Csv::toArray($string);
        $this->assertEquals(4, count($result), 'check count');
        $this->assertEquals(true, Csv::hasError($result), 'check hasError');
    }
}
