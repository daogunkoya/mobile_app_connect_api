<?php
namespace App\Details\Bank;

class BankAccountDetail
{
    public function __construct(
        public string $accountName, 
        public string $receiverFirstName, 
        public string $receiverLastName,
        public string $receiverMiddleName,
        public string $accountNumber,
        public string $bankName,
        public string $bankCode,

        )
    {
        
    }
}