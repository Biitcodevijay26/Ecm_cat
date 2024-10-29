<?php

namespace App\Console\Commands;

use App\Models\CurrencyRate;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class UpdateCurrencyRates extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'currency:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch and update currency conversion rates';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $apiUrl   = 'https://open.er-api.com/v6/latest/USD';
        $response = Http::get($apiUrl);
        if ($response->successful()) {
            $data = $response->json();


            if (isset($data['rates']) && is_array($data['rates'])) {

                CurrencyRate::where('base_currency', 'USD')->delete();

                $conversionRates = $data['rates'] ?? [];
                $baseCurrency    = $data['base_code'] ?? 'USD';
                $Record = new CurrencyRate();
                $Record->base_currency = $baseCurrency;
                $Record->rates = json_encode($conversionRates);
                $Record->save();

                \Log::info("Successfully run updated rates command. Run At: " . now());

            } else {
                \Log::info("Rates not found in the API response.");
            }
        } else {
            \Log::info("Failed to fetch data from the API.");
        }
    }
}
