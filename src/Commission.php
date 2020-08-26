<?php
namespace Picqer\BolRetailer;

use GuzzleHttp\Exception\ClientException;
use Picqer\BolRetailer\Exception\CommissionNotFoundException;
use Picqer\BolRetailer\Exception\HttpException;
use Picqer\BolRetailer\Exception\OfferNotFoundException;

class Commission extends Model\Commission
{
    /**
     * Get the commission information for a single EAN.
     *
     * @param string $ean The EAN of the product to get.
     *
     * @param string $condition
     * @param float|null $price
     *
     * @return self|null
     */
    public static function get(string $ean, string $condition = 'NEW', ?float $price = null): ?Commission
    {
        $query = [ 'condition' => $condition ];
        if(! is_null($price)) {
            $query['price'] = $price;
        }

        try {
            $response = Client::request('GET', "commission/${ean}", $query);
        } catch (ClientException $e) {
            static::handleException($e);
        }

        return new self(json_decode((string) $response->getBody(), true));
    }

    private static function handleException(ClientException $e): void
    {
        $response = $e->getResponse();

        if ($response && $response->getStatusCode() === 400) {
            throw new CommissionNotFoundException(
                json_decode((string) $response->getBody(), true),
                404,
                $e
            );
        } elseif ($response) {
            throw new HttpException(
                json_decode((string) $response->getBody(), true),
                $response->getStatusCode(),
                $e
            );
        }

        throw $e;
    }
}
