<?php

namespace App\Http\Controllers;

use App\Services\CurrencyServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function __construct(private readonly CurrencyServiceInterface $currencyService)
    {
    }

    public function averagePrice(Request $request)
    {
        $params = $request->route()->parameters();

        $validator = Validator::make($params, [
            'currency' => [Rule::in(['USD', 'EUR', 'CHF', 'GBP'])],
            'startDate' => ['date_format:Y-m-d'],
            'endDate' => ['date_format:Y-m-d']
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        return $this->currencyService->getAverageBuyRate($params['currency'], $params['startDate'], $params['endDate']);
    }
}
