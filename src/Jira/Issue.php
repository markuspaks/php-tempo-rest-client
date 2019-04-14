<?php

namespace TempoRestApi\Jira;

class Issue implements \JsonSerializable
{
    /**
     * @var string
     */
    public $self;

    /** @var string */
    public $key;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
