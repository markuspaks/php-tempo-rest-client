<?php

namespace Tempo\Jira;

class Author implements \JsonSerializable
{
    /**
     * @var string
     */
    public $self;

    /** @var string */
    public $accountId;

    /** @var string */
    public $displayName;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
