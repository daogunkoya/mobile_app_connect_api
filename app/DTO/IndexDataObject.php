<?php

namespace App\DTO;


class IndexDataObject
{
    public $senders;
    public $transactions;
    public $rate;

    public function __construct($senders, $transactions, $rate)
    {
        $this->senders = $senders;
        $this->transactions = $transactions;
        $this->rate = $rate;
    }
}
