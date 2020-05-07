<?php
namespace Picqer\BolRetailer\Model;

/**
 * @property string $ean
 * @property string $condition
 * @property float $price
 * @property float $fixedAmount
 * @property float $percentage
 * @property float $totalCost
 * @property float $totalCostWithoutReduction
 */
class Commission extends AbstractModel
{
    protected function getReductions(): ?array
    {
        /** @var array<array-key, mixed> */
        $items = $this->data['reductions'] ?? [];

        return array_map(function (array $data) {
            return new Reduction($this, $data);
        }, $items);
    }
}
