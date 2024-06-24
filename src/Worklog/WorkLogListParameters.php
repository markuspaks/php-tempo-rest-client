<?php

namespace TempoRestApi\Worklog;

use TempoRestApi\ParametersInterface;

class WorkLogListParameters implements ParametersInterface
{
    /**
     * @var array
     */
    protected $issues = [];

    /**
     * @var array
     */
    protected $projects = [];

    /**
     * @var \DateTime
     */
    protected $from;

    /**
     * @var \DateTime
     */
    protected $to;

    /**
     * @var \DateTime
     */
    protected $updatedFrom;

    /**
     * @var int
     */
    protected $offset = 0;

    /**
     * @var int
     */
    protected $limit = 50;

    /**
     * @return array
     */
    public function getIssues(): array
    {
        return $this->issues;
    }

    /**
     * Issues may be specified by either issue ids or issue keys.
     *
     * @param array $issues
     * @return WorkLogListParameters
     */
    public function setIssues(array $issues): WorkLogListParameters
    {
        foreach ($issues as $issue) {
            $this->issues[] = (string)$issue;
        }

        return $this;
    }

    /**
     * Issue may be specified by either issue ids or issue keys.
     *
     * @param string $issue
     * @return WorkLogListParameters
     */
    public function setIssue(string $issue): WorkLogListParameters
    {
        $this->issues[] = $issue;
        return $this;
    }

    /**
     * @return array
     */
    public function getProjects(): array
    {
        return $this->projects;
    }

    /**
     * Projects may be specified by either project ids or project keys
     *
     * @param array $projects
     * @return WorkLogListParameters
     */
    public function setProjects(array $projects): WorkLogListParameters
    {
        foreach ($projects as $project) {
            $this->projects[] = (string)$project;
        }

        return $this;
    }

    /**
     * Projects may be specified by either project ids or project keys
     *
     * @param string $project
     * @return WorkLogListParameters
     */
    public function setProject(string $project): WorkLogListParameters
    {
        $this->projects[] = $project;
        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getFrom(): ?\DateTime
    {
        return $this->from;
    }

    /**
     * Retrieve results starting with this date
     *
     * @param \DateTime $from
     * @param \DateTime $to
     * @return WorkLogListParameters
     */
    public function setRange(\DateTime $from, \DateTime $to): WorkLogListParameters
    {
        $this->from = $from;
        $this->to = $to;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getTo(): ?\DateTime
    {
        return $this->to;
    }

    /**
     * @return \DateTime|null
     */
    public function getUpdatedFrom(): ?\DateTime
    {
        return $this->updatedFrom;
    }

    /**
     * @param \DateTime $updatedFrom
     * @return WorkLogListParameters
     */
    public function setUpdatedFrom(\DateTime $updatedFrom): WorkLogListParameters
    {
        $this->updatedFrom = $updatedFrom;
        return $this;
    }

    /**
     * @return int
     */
    public function getOffset(): int
    {
        return $this->offset;
    }

    /**
     * Skip over a number of elements by specifying an offset value for the query
     *
     * @param int $offset
     * @return WorkLogListParameters
     */
    public function setOffset(int $offset): WorkLogListParameters
    {
        $this->offset = $offset;
        return $this;
    }

    /**
     * @return int
     */
    public function getLimit(): int
    {
        return $this->limit;
    }

    /**
     * Limit the number of elements on the response
     * Maximum: 1000, Default: 50
     *
     * @param int $limit
     * @return WorkLogListParameters
     */
    public function setLimit(int $limit): WorkLogListParameters
    {
        $this->limit = ($limit > 1000 || $limit < 0) ? 50 : $limit;

        return $this;
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

    /**
     * @return array
     */
    public function getPostParams(): array
    {
        return [];
    }


}