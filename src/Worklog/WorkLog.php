<?php

namespace TempoRestApi\WorkLog;

use TempoRestApi\DataModel;

class WorkLog implements \JsonSerializable, DataModel
{
    /**
     * @var string
     */
    public $self;

    /** @var int */
    public $tempoWorklogId;

    /** @var int */
    public $jiraWorklogId;

    /** @var \TempoRestApi\Jira\Issue */
    public $issue;

    /** @var int */
    public $timeSpentSeconds;

    /** @var \DateTime */
    public $startDate;

    /** @var string */
    public $startTime;

    /** @var string */
    public $description;

    /** @var \DateTime */
    public $createdAt;

    /** @var \DateTime */
    public $updatedAt;

    /** @var \TempoRestApi\Jira\Author */
    public $author;

    /** @var array */
    public $attributes;

    public function jsonSerialize()
    {
        return array_filter(get_object_vars($this));
    }
}
