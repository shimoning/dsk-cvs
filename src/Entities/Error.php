<?php

namespace Shimoning\DskCvs\Entities;

use DateTimeImmutable;
use Shimoning\DskCvs\Contracts\Entities\Output;

/**
 * 収納データPOST方式取得インターフェース仕様書(第5版)
 * Table 1.2-2 エラー情報構造
 */
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

        // 2行目: “システム連携収納データダウンロード(CVS055_4203)にエラーが発生しました。” の文字列
        $this->_message = $error[1][0];

        // 3行目: “日時:YYYY/MM/DD HH:MM:SS” の文字列。
        $this->_dateTime = new DateTimeImmutable(\ltrim($error[2][0], '日時：'));

        // 4行目: “エラー状況:” の文字列とエラーメッセージ
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
