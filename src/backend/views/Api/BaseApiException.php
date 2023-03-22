<?php

namespace Kronas\Api;

class BaseApiException extends \Exception
{
    public function __construct(?string $errorMessage, int $errorCode = 0, protected int $errorStatus = 400)
    {
        parent::__construct($errorMessage, $errorCode);
    }

    public function getStatusCode(): int
    {
        return $this->errorStatus;
    }
}