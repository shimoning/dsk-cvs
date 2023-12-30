<?php

namespace Shimoning\DskCvs;

use GuzzleHttp\Client as GuzzleClient;
use Shimoning\DskCvs\Contracts\Entities\PostInput;
use Shimoning\DskCvs\Entities\Options;
use Shimoning\DskCvs\Entities\Response;
use Shimoning\DskCvs\Entities\Input;
use Shimoning\DskCvs\Entities\Collection;
use Shimoning\DskCvs\Entities\Record as RecordEntity;
use Shimoning\DskCvs\Entities\Error as ErrorEntity;
use Shimoning\DskCvs\Utilities\Csv;

class Client
{
    /**
     * オプションを整える
     *
     * @param Options|array|null $options
     * @return Options
     */
    public static function options($options = null): Options
    {
        if ($options instanceof Options) {
            return $options;
        }

        if (\is_array($options)) {
            return new Options($options);
        }

        return new Options([]);
    }

    /**
     * クライアントを取得する
     *
     * @param Options $options
     * @return GuzzleClient
     */
    public static function client(Options $options): GuzzleClient
    {
        static $client;
        if (empty($client) || $options->shouldReset()) {
            $client = new GuzzleClient([
                'base_uri' => $options->getEndpoint(),
            ]);
        }
        return $client;
    }

    /**
     * POST リクエストする
     *
     * @param PostInput $input
     * @param Options|array|null $options
     * @return Response
     */
    public static function post(PostInput $input, mixed $options = null): Response
    {
        $options = static::options($options);
        $client = static::client($options);

        // request
        $response = $client->request(
            'POST',
            '',
            [
                'http_errors' => false,
                'timeout' => 0,
                'connect_timeout' => 0,
                'headers' => [
                    'User-Agent' => 'Shimoning DSK CVS Client',
                ],
                'form_params' => $input->getInput(),
            ]
        );
        return new Response($response, $options);
    }

    /**
     * 収納情報取得
     *
     * @param PostInput $input
     * @param Options|array|null $options
     * @param string|null $csv
     * @param array|null $header
     * @return \Shimoning\DskCvs\Entities\Collection|\Shimoning\DskCvs\Entities\Error|null
     */
    public static function requestCvsRecords(
        Input $input,
        mixed $options = null,
        string &$csv = null,
        array &$header = null,
    ): ErrorEntity|Collection|null {
        $response = static::post($input, $options);
        if (! $response->isSuccess()) {
            return null;
        }
        $csv = $response->getRawBody();

        $body = $response->getParsedBody();
        $header = $body[0];
        if (! Csv::hasError($body)) {
            $length = count($body);

            $records = [];
            for ($i = 1; $i < $length; $i++) {
                $records[] = new RecordEntity($body[$i]);
            }
            return new Collection($records);
        }

        return new ErrorEntity($body);
    }
}
