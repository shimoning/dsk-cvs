<?php

namespace Shimoning\DskCvs\Entities;

// TODO: support GuzzleHttp\RequestOptions
class Options
{
    private string $_endpoint = 'https://ec-web.densan-s.co.jp/CVS001_055/CVS055_4203.php';
    private bool $_test= false;
    private bool $_reset = false;

    public function __construct(array $data)
    {
        if (!empty($data['endpoint'])) {
            $this->_endpoint = $data['endpoint'];
        }
        if (isset($data['test'])) {
            $this->_test = \filter_var($data['test'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
        if (isset($data['reset'])) {
            $this->_reset = \filter_var($data['reset'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE);
        }
    }

    /**
     * API のエンドポイント設定を取得
     * @return string
     */
    public function getEndpoint(): string
    {
        return $this->_endpoint . ($this->_test ? '?mode=test' : '');
    }

    /**
     * テストモードかどうかを取得
     * @return bool
     */
    public function isTest(): bool
    {
        return $this->_test;
    }

    /**
     * クライアントの初期化が必要かどうかを取得
     * @return bool
     */
    public function shouldReset(): bool
    {
        return $this->_reset;
    }
}
