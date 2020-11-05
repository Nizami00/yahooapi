<?php

namespace App\Controllers;


use App\Models\Stock;
use Carbon\Carbon;
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

        $stockRequestData = json_decode(json_encode($stockRequest),true);


        $stockQuery = query()
            ->select('*')
            ->from('stocks')
            ->where('shortName = :name')
            ->setParameter('name', $_POST['search'])
            ->execute()
            ->fetchAssociative();

        if(!empty($stockQuery)){
            $stock = new Stock(
                (int) $stockQuery['id'],
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
            var_dump($stockTimeSeconds, $currentTimeSeconds);

            if($currentTimeSeconds - $stockTimeSeconds> 600){
                $this->update($stockRequestData);
            }
        }else{
            $this->store($stockRequestData);
        }


        return require_once __DIR__  . '/../Views/MainPageView.php';
    }

    private function update(array $stockData)
    {
        query()
            ->update('stocks')
            ->set('previousClose', ':previousClose')
            ->set('open', ':open')
            ->set('volume', ':volume')
            ->set('avgVolume', ':avgVolume')
            ->set('updated_at', ':time')
            ->setParameter('previousClose', $stockData['regularMarketPreviousClose'])
            ->setParameter('open', $stockData['regularMarketOpen'])
            ->setParameter('volume', $stockData['regularMarketVolume'])
            ->setParameter('avgVolume', $stockData['averageDailyVolume3Month'])
            ->setParameter('time', Carbon::now()->format('h:i:s'))
            ->where('shortName = :shortName')
            ->setParameter('shortName', $stockData['symbol'])
            ->execute();

    }

    private function store(array $stockData)
    {
        query()
            ->insert('stocks')
            ->values([
                'shortName' => ':shortName',
                'longName' => ':longName',
                'previousClose' => ':previousClose',
                'open' => ':open',
                'volume' => ':volume',
                'avgVolume' => ':avgVolume',
                'updated_at' => ':updated_at'
            ])
            ->setParameter('shortName', $stockData['symbol'])
            ->setParameter('longName', $stockData['shortName'])
            ->setParameter('previousClose', $stockData['regularMarketPreviousClose'])
            ->setParameter('open', $stockData['regularMarketOpen'])
            ->setParameter('volume', $stockData['regularMarketVolume'])
            ->setParameter('avgVolume', $stockData['averageDailyVolume3Month'])
            ->setParameter('updated_at', Carbon::now()->format('h:i:s'))
            ->execute();
    }


}