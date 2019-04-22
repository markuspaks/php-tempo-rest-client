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
     * @var ListMetaData
     */
    protected $metaData;

    /**
     * @var TempoClient
     */
    protected $tempoClient;

    /**
     * ResultSet constructor.
     * @param TempoClient $tempoClient
     * @param string $strictType
     * @throws InvalidInstanceException
     */
    public function __construct(TempoClient $tempoClient, string $strictType)
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
        $this->tempoClient = $tempoClient;
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

    /**
     * @param object $json
     * @return ResultSet
     * @throws \JsonMapper_Exception
     */
    public function setMetaData(object $json): self
    {
        $this->metaData = $this->tempoClient->jsonMapper->map(
            $json, new ListMetaData()
        );

        return $this;
    }

    /**
     * @return bool
     */
    public function hasMore(): bool
    {
        return $this->metaData && $this->metaData->next;
    }

    /**
     * @param object $json
     * @return DataModel
     * @throws \JsonMapper_Exception
     */
    public function mapJson(object $json): DataModel
    {
        return $this->tempoClient->jsonMapper->map(
            $json, new $this->strictType()
        );
    }

    /**
     * @param bool $clearExisting
     * @return bool
     * @throws TempoException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function fetchNext(bool $clearExisting = true)
    {
        $next = $this->metaData ? $this->metaData->next : false;

        if (!$next) {
            return false;
        }

        if ($clearExisting) {
            $this->items = [];
        }

        $this->request($next);

        return true;
    }

    /**
     * @param string $url
     * @return $this
     * @throws TempoException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function request(string $url): ResultSet
    {
        $result = $this->tempoClient->request($url);

        if ($result->metadata) {
            $this->setMetaData($result->metadata);
        }

        foreach ($result->results as $item) {
            $this[] = $this->mapJson($item);
        }

        return $this;
    }
}