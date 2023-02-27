<?php

namespace App\Http\Controllers;

use App\Exceptions\CurrencyException;
use App\Services\CurrencyServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
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
            'startDate' => ['date_format:Y-m-d', 'before_or_equal:endDate'],
            'endDate' => ['date_format:Y-m-d', 'after_or_equal:startDate', 'before_or_equal:now']
        ]);

        if ($validator->fails()) {
            return $validator->errors();
        }

        try {
            return $this->currencyService->getAverageBuyRate($params['currency'], $params['startDate'], $params['endDate']);
        } catch (CurrencyException $currencyException) {
            Log::error('Something went wrong during getting information about average price currency. '.$currencyException->getMessage(),
                $currencyException->getTrace());

            return $currencyException->getMessage();
        }
    }
}
