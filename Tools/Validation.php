<?php

namespace App\Component\Tools;

class Validation
{
    /**
     * @var array
     */
    private $values;

    /**
     * Validation constructor.
     * @param array $values
     */
    public function __construct(array $values = [])
    {
        $this->values = $values;
    }

    /**
     * @return array
     */
    public function getValues()
    {
        return $this->values;
    }

    /**
     * Get key value, if not present return default value
     *
     * @param string $key
     * @param mixed|null $default
     * @return mixed|null
     */
    public function get($key, $default = null)
    {
        return $this->__get($key) ?? $default;
    }

    /**
     * Check key exists
     *
     * @param string $key
     * @return bool
     */
    public function has($key)
    {
        return array_key_exists($key, $this->values);
    }

    /**
     * Set a value for name
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $this->values[$key] = $value;
    }

    /**
     * Get value for given key, if not present return null
     *
     * @param string $key
     * @return mixed|null
     */
    public function __get($key)
    {
        if (!$this->has($key)) {
            return null;
        }
        return $this->values[$key];
    }
}