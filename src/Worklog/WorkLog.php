<?php

namespace TempoRestApi\Worklog;

use TempoRestApi\DataModel;

class WorkLog implements \JsonSerializable, DataModel
{
    /**
     * @var string
     */
    public $self;

    /** @var int */
    public $tempoWorklogId;

    /** @var int|null */
    public $jiraWorklogId;

    /** @var \TempoRestApi\Jira\Issue */
    public $issue;

    /** @var int */
    public $timeSpentSeconds;

    /** @var int */
    public $billableSeconds;

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

    /**
     * @return string
     */
    public function getHttpQuery(): string
    {
        $params = [];

        if (!empty($this->issues)) {
            $params['issue'] = $this->issues;
        }

        if (!empty($this->projects)) {
            $params['project'] = $this->projects;
        }

        if (!empty($this->from)) {
            $params['from'] = $this->from->format('Y-m-d');
        }

        if (!empty($this->to)) {
            $params['to'] = $this->to->format('Y-m-d');
        }

        if (!empty($this->updatedFrom)) {
            $params['updatedFrom'] = $this->updatedFrom->format('Y-m-d');
        }

        $params['offset'] = $this->offset;
        $params['limit'] = $this->limit;

        return http_build_query($params);
    }
}
