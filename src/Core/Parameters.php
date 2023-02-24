<?php

namespace Core;

class Parameters
{
    private $data = [];

    public function __construct($read = []) {
        $this->data = json_decode(json_encode($read), true);
    }

    public function setData($data)
    {
        $this->data = $data;
    }



    public function set($key, $value): self {
        $this->data[$key] = $value;
        return $this;
    }

    public function isEmpty(): bool
    {
        return count($this->data) <= 0;
    }

    public function getAll(): array {
        return $this->data;
    }

    public function get($element, $filter = null, $default = null) {
        if(!isset($this->data[$element]) || !array_key_exists($element, $this->data) || !$this->data[$element]) {
            return $default;
        }

        if(is_scalar($this->data[$element])) {
            if(!$filter) {
                return $this->data[$element];
            }

            return filter_var($this->data[$element], $filter);
        }

        // if already Parameters instance, just return at this point
        if(get_class((object)$this->data[$element]) === self::class) {
            return $this->data[$element];
        }

        // otherwise autoload into Parameters and hope for the best
        return new Parameters($this->data[$element]);
    }

    public function getRaw($element) {
        return $this->data[$element];
    }

    public function getString($element, $default = '') {
        return (string)$this->get($element, FILTER_SANITIZE_FULL_SPECIAL_CHARS, $default);
    }

    public function getInt($element, $default = 0)
    {
        return (int)$this->get($element, FILTER_SANITIZE_NUMBER_INT, $default);
    }

    public function getFloat($element, $default = 0.0)
    {
        return (float)filter_var($this->getString($element, (string)$default), FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

    public function getNested($element)
    {
        return (array)$this->get($element, null, new Parameters());
    }

    public function getBool($element)
    {
        return $this->get($element, FILTER_VALIDATE_BOOLEAN);
    }

    public function getEmail($element, $default = '')
    {
        return (string)$this->get($element, FILTER_SANITIZE_EMAIL, $default);
    }
}
