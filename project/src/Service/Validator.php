<?php

namespace App\Service;

class Validator {
    private $rules = [];
    private $messages = [];

    public function setRule (string $name, callable $check, $message = null) {
        if (!isset($this->rules[$name])) {
            $this->rules[$name] = [];
        }

        $this->rules[$name][] = [
            'check' => $check,
            'message' => $message
        ];

        return $this;
    }

    public function check (array $data) {
        $this->messages = [];

        foreach ($this->rules as $name => $validators) {
            $value = $data[$name] ?? null;
            foreach ($validators as $validator) {
                if (!$validator['check']($value, $data)) {
                    $this->messages[$name] = $validator['message'];
                    break;
                }
            }
        }

        return count($this->messages) == 0;
    }

    public function getMessages()
    {
        return $this->messages;
    }
}