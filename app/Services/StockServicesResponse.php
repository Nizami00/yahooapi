<?php

namespace App\Services;

use App\Models\Stock;

class StockServicesResponse
{
    private Stock $stock;

    public function __construct(Stock $stock)
    {
        $this->stock = $stock;
    }

    public function stock(): Stock
    {
        return $this->stock;
    }
}
