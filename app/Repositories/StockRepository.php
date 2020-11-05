<?php

namespace App\Repositories;

use App\Models\Stock;
use Carbon\Carbon;

class StockRepository
{
    public function storeStock(array $stockData): Stock
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

        return $this->stockCreator();

    }

    public function updateStock(array $stockData): Stock
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

        $this->stockCreator();
    }

    private function stockCreator()
    {
        $stockQuery = query()
            ->select('*')
            ->from('stocks')
            ->where('shortName = :name')
            ->setParameter('name', $_POST['search'])
            ->execute()
            ->fetchAssociative();

        return new Stock(
            (int) $stockQuery['id'],
            $stockQuery['shortName'],
            $stockQuery['longName'],
            $stockQuery['previousClose'],
            $stockQuery['open'],
            $stockQuery['volume'],
            $stockQuery['avgVolume'],
            $stockQuery['updated_at'],
        );
    }
}