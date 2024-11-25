<?php

namespace App\Actions\Bank;

use App\Details\Bank\BankAccountDetail;
use Illuminate\Support\Facades\Http;

class VerifyBankAction
{
    /**
     * Handles the verification of a bank account.
     *
     * @param string $bankCode
     * @param string $accountNumber
     * @return BankAccountDetail|null
     */
    public function handle(string $bankCode, string $accountNumber): ?BankAccountDetail
    {
        $apiUrl = $this->buildApiUrl($bankCode, $accountNumber);

        try {
            $response = Http::get($apiUrl);

            if ($response->successful()) {
                $bankData = $response->json();

                if (!empty($bankData[0])) {
                    return $this->mapToBankAccountDetail($bankData[0]);
                }

                return null;
            }

            throw new \Exception('Failed to fetch bank data from the API');
        } catch (\Exception $e) {
            // Log the error and return null for graceful handling
            logger()->error('Bank Verification Error: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Builds the API URL for bank account verification.
     *
     * @param string $bankCode
     * @param string $accountNumber
     * @return string
     */
    private function buildApiUrl(string $bankCode, string $accountNumber): string
    {
        $baseUrl = config('api.nu_bank_api.base_url');
        $apiKey = config('api.nu_bank_api.api_key');
        
        return "{$baseUrl}api/{$apiKey}?acc_no={$accountNumber}&bank_code={$bankCode}";
    }

    /**
     * Maps API response data to a BankAccountDetail object.
     *
     * @param array $userBankData
     * @return BankAccountDetail
     */
    private function mapToBankAccountDetail(array $userBankData): BankAccountDetail
    {
        $accountName = $userBankData['account_name'];
        $accountNameArr = explode(' ', $accountName);

        $firstName = $accountNameArr[0] ?? '';
        $middleName = $accountNameArr[1] ?? '';
        $lastName = $accountNameArr[2] ?? $middleName;

        return new BankAccountDetail(
            $accountName,
            $firstName,
            $lastName,
            $middleName,
            $userBankData['account_number'],
            $userBankData['bank_name'],
            $userBankData['bank_code']
        );
    }
}
