<?php

namespace Shimoning\DskCvs\Tests\Unit\Entities;

use PHPUnit\Framework\TestCase;
use Shimoning\DskCvs\Entities\Error;
use Shimoning\DskCvs\Utilities\Csv;

class ErrorTest extends TestCase
{
    /**
     * Entity 作成
     * @return void
     */
    public function test_normally()
    {
        $string = \file_get_contents(\dirname(\dirname(__DIR__)) . '/data/csv/error.csv');
        $result = Csv::toArray($string);
        $entity = new Error($result);

        $this->assertEquals(
            'システム連携収納データダウンロード(CVS055_4203)にエラーが発生しました。',
            $entity->getMessage(),
            'message',
        );
        $this->assertEquals(
            '2023-12-15 05:00:00',
            $entity->getDateTime()?->format('Y-m-d H:i:s'),
            'datetime',
        );
        $this->assertMatchesRegularExpression(
            '/認証に失敗しました/',
            $entity->getDescription(),
            'description',
        );
        $this->assertEquals(
            [],
            $entity->getParameters(),
            'parameters',
        );
        $this->assertEmpty(
            $entity->getCode(),
            'code',
        );
    }
}
