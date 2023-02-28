<?php

namespace App\Services;

use App\Exceptions\CurrencyException;
use Illuminate\Support\Facades\Http;

class CurrencyService implements CurrencyServiceInterface
{
    public function getAverageBuyRate(string $currency, string $startDate, string $endDate): array
    {
        $response = Http::get(env('API_NBP_URL').$currency.'/'.$startDate.'/'.$endDate);

        if ($response->clientError()) {
            throw new CurrencyException('Something went wrong, try again later.');
        }

        return [
            'average_price' => $this->prepareAverage($response->json())
        ];
    }

    private function prepareAverage(array $json): float
    {
        $sumOfRates = 0;

        foreach ($json['rates'] as $rate) {
            $sumOfRates += $rate['mid'];
        }

        return $sumOfRates / count($json['rates']);
    }
}
