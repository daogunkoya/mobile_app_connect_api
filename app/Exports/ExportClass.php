<?php
namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Illuminate\Contracts\Support\Arrayable;

class ExportClass implements FromCollection, WithHeadings, WithMapping
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Created At',
            'Transaction ID',
            'Transaction Code',
            'User ID',
            'Sender ID',
            'Sender Address',
            'Receiver Address',
            'Currency ID',
            'Sender First Name',
            'Sender Last Name',
            'Receiver First Name',
            'Receiver Last Name',
            'Receiver Phone',
            'Receiver Bank',
            'Receiver Account Number',
            'Receiver Transfer Type',
            'Amount Sent',
            'Local Amount',
            'Total Amount',
            'Total Commission',
            'Exchange Rate'
        ];
    }

    public function map($row): array
    {
        // If $row is an instance of Arrayable, convert it to array
        $row = $row instanceof Arrayable ? $row->toArray() : (array) $row;

        return [
            $row['createdAt'],
            $row['transactionId'],
            $row['transactionCode'],
            $row['userId'],
            $row['senderId'],
            $row['senderAddress'],
            $row['receiverAddress'],
            $row['currencyId']??'',
            $row['senderFname'],
            $row['senderLname'],
            $row['receiverFname'],
            $row['receiverLname'],
            $row['receiverPhone'],
            $row['receiverBank']['name'], // Assuming BankDto has a name attribute
            $row['receiverAccountNumber'],
            $row['receiverTransferType'],
            $row['amountSent'],
            $row['localAmount'],
            $row['totalAmount'],
            $row['totalCommission'],
            $row['exchangeRate']
        ];
    }
}
