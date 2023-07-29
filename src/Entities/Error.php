<?php

namespace Shimoning\DskCvs\Entities;

use DateTimeImmutable;
use Shimoning\DskCvs\Contracts\Entities\Output;

class Error implements Output
{
    private array $_raw;
    private string $_message;
    private DateTimeImmutable $_dateTime;
    private string $_description;
    private array $_parameters;
    private ?int $_code;

    /**
     * エラー内容
     *
     * @param
     * @param string[][] $error
     */
    public function __construct(array $error)
    {
        $this->_raw = $error;
        $this->_message = $error[1][0];
        $this->_dateTime = new DateTimeImmutable(\ltrim($error[2][0], '日時：'));
        $this->_description = \preg_replace('/エラー状況：/u', '', $error[3][0]);
        \preg_match_all('/「(.+?)」/u', $error[3][0], $parameters);
        $this->_parameters = $parameters[1] ?? [];
        \preg_match('/（コード：(.+?)）/u', $error[3][0], $code);
        $this->_code = $code[1] ?? null;
    }

    /**
     * エラーメッセージを取得
     * @return array
     */
    public function getRaw(): array
    {
        return $this->_raw;
    }

    /**
     * エラーメッセージを取得
     * @return string
     */
    public function getMessage(): string
    {
        return $this->_message;
    }

    /**
     * 日時を取得
     * @return DateTimeImmutable
     */
    public function getDateTime(): DateTimeImmutable
    {
        return $this->_dateTime;
    }

    /**
     * エラー状況を取得
     * @return string
     */
    public function getDescription(): string
    {
        return $this->_description;
    }

    /**
     * エラーになったパラメータを取得
     * @return string[]
     */
    public function getParameters(): array
    {
        return $this->_parameters;
    }

    /**
     * エラーコードを取得
     * @return int|null
     */
    public function getCode(): ?int
    {
        return $this->_code;
    }

    public function hasError(): bool
    {
        return true;
    }
}
