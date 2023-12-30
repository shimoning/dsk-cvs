<?php

namespace Shimoning\DskCvs\Utilities;

/**
 * 電算システムのCSV用のユーティリティ
 */
class Csv
{
    /**
     * SJISの文字列としてのCSVをUTF-8の配列に変換する
     *
     * @param string $body
     * @return array
     */
    static public function toArray(string $csv): array
    {
        $utf8Csv = \mb_convert_encoding($csv, 'UTF-8', 'SJIS');
        $arrayedCsv = \explode("\r\n", $utf8Csv);
        return \array_map('str_getcsv', $arrayedCsv);
    }

    /**
     * エラーが含まれているかどうか
     *
     * 収納データPOST方式取得インターフェース仕様書(第5版)
     * Table 1.2-2 エラー情報構造
     *
     * 1行目: “error:” の文字列
     * 2行目: “システム連携収納データダウンロード(CVS055_4203)にエラーが発生しました。” の文字列
     * 3行目: “日時:YYYY/MM/DD HH:MM:SS” の文字列。
     * 4行目: “エラー状況:” の文字列とエラーメッセージ
     *
     * @param array $csv
     * @return boolean
     */
    static public function hasError(array $csv): bool
    {
        return \strpos($csv[0][0], 'error') !== false;
    }
}
