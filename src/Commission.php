<?php
namespace Picqer\BolRetailer;

use GuzzleHttp\Exception\ClientException;

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
}
