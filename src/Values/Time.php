<?php

namespace Shimoning\DskCvs\Values;

use DateTimeInterface;
use Shimoning\DskCvs\Exceptions\ParameterException;

/**
 * DSK 収納データで扱われる時刻型 (HHMM)
 */
class Time
{
    private string $_time;

    /**
     * @param string|DateTimeInterface $time
     */
    public function __construct(mixed $time)
    {
        if (\is_string($time)) {
            if (\strlen($time) !== 4) {
                throw new ParameterException('時刻は "文字列" で 4桁(HHMM) を入れてください。');
            }
            $this->_time = $time;
        } else if ($time instanceof DateTimeInterface) {
            $this->_time = $time->format('hi');
        } else {
            throw new ParameterException('時刻は "文字列" もしくは "DateTimeInterface を継承したオブジェクト" を入れてください。');
        }
    }

    /**
     * 時刻を取得
     * @return string
     */
    public function get(): string
    {
        return $this->_time;
    }
}
