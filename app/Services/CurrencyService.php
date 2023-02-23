<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class CurrencyService implements CurrencyServiceInterface
{
    public function getAverageBuyRate(string $currency, string $startDate, string $endDate): array
    {
        $response = Http::get(env('API_NBP_URL').$currency.'/'.$startDate.'/'.$endDate)->json();

        return [
            'average_price' => $response['rates'][0]['mid'] ?? ''
        ];
    }
}
