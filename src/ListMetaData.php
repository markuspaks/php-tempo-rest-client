<?php

namespace Tempo;

/**
 * MetaData from every listing request
 *
 * Class ListMetaData
 * @package TempoRest\WorkLog
 */
class ListMetaData implements \JsonSerializable, DataModel
{
    /** @var int */
    public $count;

    /** @var int */
    public $offset;

    /** @var int */
    public $limit;

    /** @var string */
    public $next;

    /** @var string */
    public $previous;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
