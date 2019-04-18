<?php

namespace TempoRestApi\WorkLog;

use TempoRestApi\ResultSet;

/**
 * Class WorkLogResultSet
 * @package TempoRestApi\WorkLog
 */
class WorkLogResultSet extends ResultSet
{
    /**
     * WorkLogResultSet constructor.
     */
    public function __construct()
    {
        parent::__construct(WorkLog::class);
    }
}
