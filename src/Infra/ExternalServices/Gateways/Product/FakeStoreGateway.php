<?php

namespace Infra\ExternalServices\Gateways\Product;

use Application\DTOs\ProductDTO;
use Application\DTOs\RatingDTO;
use Domain\Product\Interfaces\ProductGatewayInterface;
use Domain\Product\ValueObjects\Money;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Pool;
use GuzzleHttp\Psr7\Request;
use Infra\ExternalServices\Gateways\BaseGateway;
use Infra\ExternalServices\Gateways\Exceptions\FailedRequestException;

class FakeStoreGateway extends BaseGateway implements ProductGatewayInterface
{
    public function __construct(Client $client)
    {
        parent::__construct($client);
    }

    public function getProduct(int $id): ?ProductDTO
    {
        $url = 'https://fakestoreapi.com/products/' . $id;
        try {
            $response = $this->request('GET', $url);
        } catch (GuzzleException $e) {
            throw new FailedRequestException("Failed request to " . $url, $e->getCode(), $e);
        }

        if (empty($response)) {
            return null;
        }

        return ProductDTO::fromApiResponse($response);
    }

    public function getProductsById(array $ids): array
    {
        $results = $this->concurrentRequest(20, $ids, function ($products) {
            foreach ($products as $product) {
                yield $product => new Request('GET', 'https://fakestoreapi.com/products/' . $product);
            }
        });

        return array_map(
            function ($result, $index) {
                if (is_string($result)) {
                    return $result;
                }

                if (empty($result)) {
                    return "Product #$index not found";
                }

                return ProductDTO::fromApiResponse($result);
            },
            $results,
            array_keys($results)
        );
    }
}
