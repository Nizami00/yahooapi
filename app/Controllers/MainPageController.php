<?php

namespace App\Controllers;


use App\Models\Stock;
use App\Services\StockService;
use Scheb\YahooFinanceApi\ApiClient;
use Scheb\YahooFinanceApi\ApiClientFactory;
use GuzzleHttp\Client;
use Doctrine\DBAL\DriverManager;


class MainPageController
{

    public function index()
    {
        return require_once __DIR__ . '/../Views/MainPageView.php';
    }

    public function search()
    {

        $client = ApiClientFactory::createApiClient();

        $guzzleClient = new Client($options = []);
        $client = ApiClientFactory::createApiClient($guzzleClient);

        $stockRequest = $client->getQuote($_POST['search']);

        $stockRequestData = json_decode(json_encode($stockRequest), true);


        $stockQuery = query()
            ->select('*')
            ->from('stocks')
            ->where('shortName = :name')
            ->setParameter('name', $_POST['search'])
            ->execute()
            ->fetchAssociative();

        if (!empty($stockQuery)) {
            $stock = new Stock(
                (int)$stockQuery['id'],
                $stockQuery['shortName'],
                $stockQuery['longName'],
                $stockQuery['previousClose'],
                $stockQuery['open'],
                $stockQuery['volume'],
                $stockQuery['avgVolume'],
                $stockQuery['updated_at'],
            );


            $currentTime = date("h:i:s", time());

            $updatedStockTime = $stock->getTime();
            $updatedStockFormattedTime = date("h:i:s", strtotime($updatedStockTime));

            $currentTime = explode(':', $currentTime);
            $currentTimeSeconds = ($currentTime[0] * 60 * 60) + ($currentTime[1] * 60) + $currentTime[2];

            $updatedStockTime = explode(':', $updatedStockTime);
            $stockTimeSeconds = ($updatedStockTime[0] * 60 * 60) + ($updatedStockTime[1] * 60) + $updatedStockTime[2];

            if ($currentTimeSeconds - $stockTimeSeconds > 600) {

                $response = (new StockService())->updateStock($stockRequestData);
                $stock = $response->stock();
            }
        }else {

                $response = (new StockService())->storeStock($stockRequestData);
                $stock = $response->stock();
            }


            return require_once __DIR__ . '/../Views/MainPageView.php';
        }


}