<?php

namespace App\DTO;

class ThirdPartyServiceDto
{
    private bool $success;

    private $data;

    private ?string $error;

    public function __construct(bool $success, $data, ?string $error = null)
    {
        $this->success = $success;
        $this->data = $data;
        $this->error = $error;
    }

    public function isSuccess(): bool
    {
        return $this->success;
    }

    public function getData()
    {
        return $this->data;
    }

    public function getError(): ?string
    {
        return $this->error;
    }
}
