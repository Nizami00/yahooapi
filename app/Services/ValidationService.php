<?php
namespace App\Services;

use App\Models\Stock;

class ValidationService
{
    public function compareTime(Stock $stock): bool
    {
        $currentTime = date("h:i:s", time());

        $updatedStockTime = $stock->getTime();
        $updatedStockFormattedTime = date("h:i:s", strtotime($updatedStockTime));

        $currentTime = explode(':', $currentTime);
        $currentTimeSeconds = ($currentTime[0] * 60 * 60) + ($currentTime[1] * 60) + $currentTime[2];

        $updatedStockTime = explode(':', $updatedStockTime);
        $stockTimeSeconds = ($updatedStockTime[0] * 60 * 60) + ($updatedStockTime[1] * 60) + $updatedStockTime[2];

        return $currentTimeSeconds - $stockTimeSeconds > 600;
    }
}