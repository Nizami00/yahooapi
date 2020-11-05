<?php

namespace App\Services;

use App\Repositories\StockRepository;

class StockService
{
    private StockRepository $stockRepository;

    public function __construct()
    {
        $this->stockRepository = new StockRepository();
    }

    public function storeStock(array $stockData): StockServicesResponse
    {

        $stock = $this->stockRepository->storeStock($stockData);

        return new StockServicesResponse($stock);
    }

    public function updateStock(array $stockData): StockServicesResponse
    {
        $stock = $this->stockRepository->updateStock($stockData);

        return new StockServicesResponse($stock);
    }
}