<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class CurrencyController extends Controller
{
    public function averagePrice(Request $request)
    {
        $validator = Validator::make($request->route()->parameters(), [
            'currency' => [Rule::in(['USD', 'EUR', 'CHF', 'GBP'])],
            'startDate' => ['date_format:Y-m-d'],
            'endDate' => ['date_format:Y-m-d']
        ]);
    }
}
