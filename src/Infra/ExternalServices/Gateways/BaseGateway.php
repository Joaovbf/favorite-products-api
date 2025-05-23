<?php

namespace Infra\ExternalServices\Gateways;

use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;

abstract class BaseGateway
{
    public function __construct(private readonly Client $client)
    {
    }

    /**
     * @param string $uri
     * @param string $method
     * @param array $options
     * @return array
     * @throws GuzzleException
     */
    public function request(string $method, string $uri, array $options = []): array
    {
        $response = $this->client->request($method, $uri, $options);

        return json_decode($response->getBody()->getContents(), true);
    }

    public function concurrentRequest(int $concurrency, $payloads, \Closure $requestIterator): array
    {
        $results = [];
        $client = new Client();

        $pool = new Pool($client, $requestIterator($payloads), [
            'concurrency' => $concurrency,
            'fulfilled' => function ($response, $index) use (&$results) {
                $results[$index] = json_decode($response->getBody()->getContents(), true);
            },
            'rejected' => function (Exception $reason, $index) use (&$results) {
                $results[$index] = "Request #$index code: " . $reason->getCode() . " failed: " . $reason->getMessage();
            },
        ]);

        $promise = $pool->promise();
        $promise->wait();

        return $results;
    }
}
