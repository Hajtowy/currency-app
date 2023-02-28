<?php

namespace App\Services;

interface CurrencyServiceInterface
{
    public function getAverageBuyRate(string $currency, string $startDate, string $endDate): array;
}
