<?php

namespace App\Exceptions;

use Exception;

class ExcelImportValidationException extends Exception
{
    public function __construct(
        private readonly array $errors
    ) {
        parent::__construct('Excel validation failed.');
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}
