<?php

namespace Shimoning\DskCvs\Variables;

use DateTimeInterface;
use Exception;

/**
 * DSK 収納データで扱われる日付型 (YYYYMMDD)
 */
class Date
{
    private string $_date;

    /**
     * @param string|DateTimeInterface $date
     */
    public function __construct(mixed $date)
    {
        if (\is_string($date)) {
            // TODO: validate
            $this->_date = date('Ymd', \strtotime($date));
        } else if ($date instanceof DateTimeInterface) {
            $this->_date = $date->format('Ymd');
        } else {
            // TODO: replace original exception for handling by user
            throw new Exception('日付は "文字列" もしくは "DateTimeInterface を継承したオブジェクト" を入れてください。');
        }
    }

    /**
     * 日時を取得
     * @return string
     */
    public function get(): string
    {
        return $this->_date;
    }
}
