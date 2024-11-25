<?php

namespace App\Actions\Bank;

use App\Models\Bank;
use Illuminate\Support\Facades\Http;
use App\Interfaces\Bank\BanksSyncInterface;
use App\Models\Currency;

class BanksSyncService implements BanksSyncInterface
{
    protected $apiUrl;

   


    public function syncBankData(): void
    {
        $apiUrl = config('api.nu_bank_api.base_url') . "/bank_codes.json";
        try {
            // Fetch bank data from API
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $bankData = $response->json();

                foreach ($bankData as $bank) {
                    // Check if the bank record already exists by code or name
                    Bank::updateOrCreate(
                        ['bank_code' => $bank['code']], // Attributes to search for
                        [   // Values to update or use for creation
                            'name'          => $bank['bank_name'],
                            'store_id'      => store_id(),
                            'transfer_type' => 1,
                            'bank_category' => 'b',
                            'currency_id'   => Currency::whereDefaultCurrency(1)->value('id_currency'),
                        ]
                    );
                    
                }
            } else {
                // Handle non-successful responses
                throw new \Exception('Failed to fetch bank data from the API');
            }
        } catch (\Exception $e) {
            // Handle exceptions (e.g., logging)
            logger()->error('Bank Sync Error: ' . $e->getMessage());
        }
    }
}
