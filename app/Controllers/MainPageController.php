<?php

namespace App\Controllers;


use App\Models\Stock;
use App\Services\StockService;
use App\Services\ValidationService;
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

        //uses _POST['search'] request
        $stockRequest = $this->makeYahooApiRequest();

        //check if request is valid
        if(!$stockRequest){
            header('Location: /');
        }

        $stockRequestData = json_decode(json_encode($stockRequest), true);


        $stockQuery = query()
            ->select('*')
            ->from('stocks')
            ->where('shortName = :name')
            ->setParameter('name', $_POST['search'])
            ->execute()
            ->fetchAssociative();


        if (!empty($stockQuery)) {
            $stock = Stock::create($stockQuery);

            $needsToUpdate = (new ValidationService())->compareTime($stock);

            if ($needsToUpdate) {

                $response = (new StockService())->updateStock($stockRequestData);
                $stock = $response->stock();
            }
        }else {

                $response = (new StockService())->storeStock($stockRequestData);
                $stock = $response->stock();
            }


            return require_once __DIR__ . '/../Views/MainPageView.php';
        }

        private function makeYahooApiRequest(): \Scheb\YahooFinanceApi\Results\Quote
        {
            $client = ApiClientFactory::createApiClient();

            $guzzleClient = new Client($options = []);
            $client =  ApiClientFactory::createApiClient($guzzleClient);

            return $client->getQuote($_POST['search']);
        }


}