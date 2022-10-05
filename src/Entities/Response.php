<?php

namespace Shimoning\DskCvs\Entities;

use Psr\Http\Message\ResponseInterface;

class Response
{
    private array $_rawHeader = [];
    private string $_rawBody = '';
    private array $_parsedBody;
    private int $_status;

    /**
     * @param \Psr\Http\Message\ResponseInterface $response
     */
    public function __construct(ResponseInterface $response)
    {
        $this->_rawHeader = $response->getHeaders();

        $this->_status = $response->getStatusCode();
        if ($this->_status === 200) {
            $body = $response->getBody()->getContents();
            $this->_rawBody = $body;
            $this->_parsedBody = $this->parse($body);
        }
    }

    /**
     * ボディをパースする
     * @param string $body
     * @return array
     */
    private function parse(string $body): array
    {
        // TODO: implement
        $utf8Body = \mb_convert_encoding($body, 'UTF-8', 'SJIS');
        $arrayedBody = explode("\r\n", $utf8Body);
        $parsedBody = array_map('str_getcsv', $arrayedBody);
        return $parsedBody;
    }

    /**
     * 生レスポンスヘッダ
     * @return array
     */
    public function getRawHeader(): array
    {
        return $this->_rawHeader;
    }

    /**
     * 生レスポンスボディ
     * @return string
     */
    public function getRawBody(): string
    {
        return $this->_rawBody;
    }

    /**
     * パース後のボディ
     * @return string[][]
     */
    public function getParsedBody(): array
    {
        return $this->_parsedBody;
    }

    /**
     * リクエストの成否を取得
     * @return bool
     */
    public function isSuccess(): bool
    {
        return 200 <= $this->_status && $this->_status < 300;
    }
}
