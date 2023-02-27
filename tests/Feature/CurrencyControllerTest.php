<?php

namespace Tests\Feature;

use App\Exceptions\CurrencyException;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\Http;
use Mockery\MockInterface;
use Tests\TestCase;

class CurrencyControllerTest extends TestCase
{
    public function test_input_validation_currency_fail(): void
    {
        $response = $this->get('/api/v1/WRONG-CURRENCY/2020-01-01/2020-01-02');
        $response->assertStatus(200);

        $this->assertArrayHasKey('currency', $response->json());
    }

    public function test_input_validation_start_date_fail(): void
    {
        $date = new \DateTime();
        $date->modify('+1year');

        $response = $this->get('/api/v1/EUR/'.$date->format('Y-m-d').'/2020-01-02');
        $response->assertStatus(200);

        $this->assertArrayHasKey('startDate', $response->json());
    }

    public function test_input_validation_end_date_fail(): void
    {
        $date = new \DateTime();
        $date->modify('-1year');

        $response = $this->get('/api/v1/EUR/2023-01-02/'.$date->format('Y-m-d'));
        $response->assertStatus(200);

        $this->assertArrayHasKey('endDate', $response->json());
    }

    public function test_response_success()
    {
        $floatNumber1 = 3.4321;
        $floatNumber2 = 2.9707;

        $averageOfNumbers = (($floatNumber1 + $floatNumber2) / 2);

        Http::fake([
            '*' => Http::response([
                'table' => 'A',
                'no' => '1/A/B/C',
                'effectiveDate' => '2023-01-01',
                'rates' => [
                    [
                        'no' => '1/A/NBP/2023',
                        'effectiveDate' => '2023-01-01',
                        'mid' => $floatNumber1
                    ],
                    [
                        'no' => '2/A/NBP/2023',
                        'effectiveDate' => '2023-01-02',
                        'mid' => $floatNumber2
                    ]
                ]
            ], 200)
        ]);

        $response = $this->get('/api/v1/EUR/2023-01-01/2023-01-02');

        $this->assertEquals($averageOfNumbers, $response->json()['average_price']);
    }

    public function test_response_throw_exception_and_save_to_log()
    {
        $this->partialMock(CurrencyService::class, function (MockInterface $mock) {
            $mock->shouldReceive('getAverageBuyRate')
                ->once()
                ->andThrow(new CurrencyException('Some exception'));
        });

        $response = $this->get('/api/v1/EUR/2023-01-01/2023-01-02');

        $response->assertContent('Some exception');
    }
}
