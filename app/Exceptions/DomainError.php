<?php

namespace App\Exceptions;

use DomainException;

class DomainError extends DomainException
{
    protected string $type;

    public function __construct(string $type, string $message, int $code = 0)
    {
        $this->type = $type;
        parent::__construct($message, $code);
    }

    public function getType(): string
    {
        return $this->type;
    }
}
