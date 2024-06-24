<?php

namespace TempoRestApi\Worklog;

use TempoRestApi\ParametersInterface;
use TempoRestApi\TempoException;

class WorkLogCreateParameters implements ParametersInterface
{
    /**
     * @var string
     */
    protected $issueKey = [];

    /**
     * @var integer
     */
    protected $timeSpentSeconds = 0;

    /**
     * @var int
     */
    protected $remainingEstimateSeconds = 0;

    /**
     * @var \DateTime
     */
    protected $startDateTime;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $authorAccountId;

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @return string
     */
    public function getIssueKey(): string
    {
        return $this->issueKey;
    }

    /**
     * @param string $issueKey
     * @return WorkLogCreateParameters
     */
    public function setIssueKey(string $issueKey): self
    {
        $this->issueKey = $issueKey;

        return $this;
    }

    /**
     * @return int
     */
    public function getTimeSpentSeconds(): int
    {
        return $this->timeSpentSeconds;
    }

    /**
     * @param int $timeSpentSeconds
     * @return WorkLogCreateParameters
     */
    public function setTimeSpentSeconds(int $timeSpentSeconds): self
    {
        $this->timeSpentSeconds = $timeSpentSeconds;

        return $this;
    }

    /**
     * @return int
     */
    public function getRemainingEstimateSeconds(): int
    {
        return $this->remainingEstimateSeconds;
    }

    /**
     * @param int $remainingEstimateSeconds
     * @return WorkLogCreateParameters
     */
    public function setRemainingEstimateSeconds(int $remainingEstimateSeconds): self
    {
        $this->remainingEstimateSeconds = $remainingEstimateSeconds;

        return $this;
    }

    /**
     * @return \DateTime
     */
    public function getStartDateTime(): \DateTime
    {
        return $this->startDateTime;
    }

    /**
     * @param \DateTime $startDateTime
     * @return WorkLogCreateParameters
     */
    public function setStartDateTime(\DateTime $startDateTime): self
    {
        $this->startDateTime = $startDateTime;

        return $this;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $description
     * @return WorkLogCreateParameters
     */
    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return string
     */
    public function getAuthorAccountId(): string
    {
        return $this->authorAccountId;
    }

    /**
     * @param string $authorAccountId
     * @return WorkLogCreateParameters
     */
    public function setAuthorAccountId(string $authorAccountId): self
    {
        $this->authorAccountId = $authorAccountId;

        return $this;
    }

    /**
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * @param array $attributes
     * @return WorkLogCreateParameters
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }


    /**
     * @return string
     * @throws TempoException
     */
    public function getHttpQuery(): string
    {
        $params = $this->getPostParams();

        return http_build_query($params);
    }

    /**
     * @return array
     * @throws TempoException
     */
    public function getPostParams(): array
    {
        $params = [];

        if (!$this->issueKey) {
            throw new TempoException('Issue key value is required');
        }

        if (!$this->startDateTime) {
            throw new TempoException('Start Date/Time value is required');
        }

        if ($this->timeSpentSeconds <= 0) {
            throw new TempoException('Time spent value is required');
        }

        if ($this->authorAccountId <= 0) {
            throw new TempoException('AuthorAccountId value is required');
        }

        $params['issueKey'] = $this->issueKey;
        $params['timeSpentSeconds'] = $this->timeSpentSeconds;
        $params['remainingEstimateSeconds'] = $this->remainingEstimateSeconds;
        $params['startDate'] = $this->startDateTime->format('Y-m-d');
        $params['startTime'] = $this->startDateTime->format('h:i:s');
        $params['description'] = $this->description;
        $params['authorAccountId'] = $this->authorAccountId;

        return $params;
    }
}