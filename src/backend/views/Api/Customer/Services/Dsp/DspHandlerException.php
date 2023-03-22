<?php

namespace Kronas\Api\Customer\Services\Dsp;

use Kronas\Api\BaseApiException;

class DspHandlerException extends BaseApiException
{
    /**
     * Індекс деталі
     *
     * @var int|null
     */
    private ?int $errorDetailIndex = null;

    /**
     * Індекс обробки
     *
     * @var array|null
     */
    private ?array $errorOperationIndex = null;

    /**
     * Назва обробки зі сторони клієнта
     *
     * @var string|null
     */
    private ?string $errorOperationName = null;

    /**
     * Назва обробки зі сторони сервера
     *
     * @var string|null
     */
    private ?string $errorHandler = null;

    /**
     * Ініціалізація
     *
     * @param string|array $message
     */
    public function __construct(string|array $message)
    {
        if (is_array($message)) {
            $message = multilang(...$message);
        }

        parent::__construct($message);
    }

    public function setErrorDetailIndex(int $value): void
    {
        $this->errorDetailIndex = $value;
    }

    public function setErrorOperationIndex(?array $value): void
    {
        $this->errorOperationIndex = $value;
    }

    public function setErrorOperationName(?string $value): void
    {
        $this->errorOperationName = $value;
    }

    public function setErrorHandler(string $value): void
    {
        $this->errorHandler = $value;
    }

    public function getErrorDetailIndex(): ?int
    {
        return $this->errorDetailIndex;
    }

    public function getErrorOperationIndex(): ?array
    {
        return $this->errorOperationIndex;
    }

    public function getErrorOperationName(): ?string
    {
        return $this->errorOperationName;
    }

    public function getErrorHandler(): ?string
    {
        return $this->errorHandler;
    }
}