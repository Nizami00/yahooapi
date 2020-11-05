<?php

namespace App\Models;

class Stock
{
    private int $id;
    private string $shortName;
    private string $longName;
    private float $previousClose;
    private int $open;
    private float $volume;
    private float $avgVolume;
    private string $updatedAt;


    public function __construct(
        int $id,
        string $shortName,
        string $longName,
        float $previousClose,
        int $open,
        float $volume,
        float $avgVolume,
        string $updatedAt
    )
    {
        $this->id = $id;
        $this->shortName = $shortName;
        $this->longName = $longName;
        $this->previousClose = $previousClose;
        $this->open = $open;
        $this->volume = $volume;
        $this->avgVolume = $avgVolume;
        $this->updatedAt = $updatedAt;
    }

    public function getShortName(): string
    {
        return $this->shortName;
    }

    public function getLongName(): string
    {
        return $this->longName;
    }

    public function getPreviousClose(): float
    {
        return $this->previousClose;
    }

    public function getOpen(): int
    {
        return $this->open;
    }

    public function getVolume(): float
    {
        return $this->volume;
    }

    public function getAvgVolume(): float
    {
        return $this->avgVolume;
    }

    public function getTime(): string
    {
        return $this->updatedAt;
    }

    public static function create(array $stockQuery): Stock
    {
        return new self(
            (int)$stockQuery['id'],
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