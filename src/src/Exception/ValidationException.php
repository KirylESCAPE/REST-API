<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class ValidationException extends \Exception
{
    private ConstraintViolationListInterface $violations;

    public function __construct(ConstraintViolationListInterface $violations)
    {
        $this->violations = $violations;
        parent::__construct('Validation failed');
    }

    public function getMessages(): array
    {
        $messages = [];
        foreach ($this->violations as $key => $value) {
                $messages[$key] = $value->getMessage();
        }

        return $messages;
    }
}