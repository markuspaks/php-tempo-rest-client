<?php

namespace TempoRestApi;

class ResultSet implements \ArrayAccess, \Iterator, \Countable
{
    /**
     * @var array
     */
    protected $items = array();

    /**
     * @var string
     */
    protected $strictType;

    /**
     * ResultSet constructor.
     * @param string $strictType
     * @throws InvalidInstanceException
     */
    public function __construct(string $strictType)
    {
        try {
            $class = new \ReflectionClass($strictType);
            if (!$class->implementsInterface(DataModel::class)) {
                throw new InvalidInstanceException(sprintf('Class %s does not implement %s', $strictType,
                    DataModel::class));
            }
        } catch (\ReflectionException $e) {
            throw new InvalidInstanceException(sprintf($e->getMessage()));
        }

        $this->strictType = $strictType;
    }

    /**
     * ArrayAccess implementation
     *
     * @param mixed $offset
     * @param DataModel $value
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (!is_a($value, $this->strictType)) {
            throw new InvalidArgumentException(sprintf('Object must be instance of %s', $this->strictType));
        }

        if ($offset === null) {
            $this->items[] = $value;
        } else {
            $this->items[$offset] = $value;
        }
    }

    /**
     * ArrayAccess implementation
     *
     * @param $offset
     * @return mixed
     */
    public function offsetExists($offset)
    {
        return isset($this->items[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param $offset
     * @return mixed
     */
    public function offsetUnset($offset)
    {
        unset($this->items[$offset]);
    }

    /**
     * ArrayAccess implementation
     *
     * @param $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->items[$offset]) ? $this->items[$offset] : null;
    }

    /**
     * Iterator implementation
     *
     * @return mixed
     */
    public function rewind()
    {
        return reset($this->items);
    }

    /**
     * Iterator implementation
     *
     * @return mixed
     */
    public function current()
    {
        return current($this->items);
    }

    /**
     * Iterator implementation
     *
     * @return mixed
     */
    public function key()
    {
        return key($this->items);
    }

    /**
     * Iterator implementation
     *
     * @return mixed
     */
    public function next()
    {
        return next($this->items);
    }

    /**
     * Iterator implementation
     *
     * @return mixed
     */
    public function valid()
    {
        return $this->current() !== false;
    }

    /**
     * Countable implementation
     *
     * @return mixed
     */
    public function count()
    {
        return count($this->items);
    }
}