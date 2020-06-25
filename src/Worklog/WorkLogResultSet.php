<?php

namespace Tempo\WorkLog;

use Tempo\ResultSet;
use Tempo\TempoClient;

/**
 * Class WorkLogResultSet
 * @package TempoRestApi\WorkLog
 *
 * @method WorkLogResultSet request(string $url)
 */
class WorkLogResultSet extends ResultSet
{
    /**
     * WorkLogResultSet constructor.
     * @param TempoClient $tempoClient
     * @throws \Tempo\InvalidInstanceException
     */
    public function __construct(TempoClient $tempoClient)
    {
        parent::__construct($tempoClient, WorkLog::class);
    }
}
