<?php

namespace App\Core;

class Validator
{
    private array $errors = [];

    public function required(string $field, $value, string $message): self
    {
        if ($value === null || $value === '' || (is_array($value) && empty($value))) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function email(string $field, ?string $value, string $message): self
    {
        if ($value !== null && $value !== '' && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function max(string $field, ?string $value, int $length, string $message): self
    {
        if ($value !== null && mb_strlen($value) > $length) {
            $this->errors[$field][] = $message;
        }
        return $this;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }

    public function errors(): array
    {
        return $this->errors;
    }
}
