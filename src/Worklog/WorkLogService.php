<?php

namespace TempoRestApi\Worklog;

use TempoRestApi\TempoClient;
use TempoRestApi\TempoException;

/**
 * Class WorkLogService
 * @package TempoRestApi\Worklog
 */
class WorkLogService extends TempoClient
{
    /**
     * @param int $workLogId
     * @return WorkLog|null
     * @throws TempoException
     * @throws \JsonMapper_Exception
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function get(int $workLogId)
    {
        $url = $this->configuration->getTempoApiUrl() . "worklogs/{$workLogId}";

        try {
            $data = $this->request($url);

            return $this->getWorkLogFromJson($data);
        } catch (TempoException $te) {
            if ($te->getCode() == 404) {
                return null;
            }

            throw $te;
        }
    }

    /**
     * @param WorkLogListParameters $parameters
     * @return WorkLogResultSet
     * @throws TempoException
     * @throws \JsonMapper_Exception
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     * @throws \TempoRestApi\InvalidInstanceException
     */
    public function getList(WorkLogListParameters $parameters): WorkLogResultSet
    {
        $workLogs = new WorkLogResultSet($this);

        $url = $this->configuration->getTempoApiUrl() . "worklogs?" . $parameters->getHttpQuery();

        return $workLogs->request($url);
    }

    /**
     * @param WorkLogCreateParameters $parameters
     * @return WorkLog
     * @throws TempoException
     * @throws \JsonMapper_Exception
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function create(WorkLogCreateParameters $parameters): WorkLog
    {
        $url = $this->configuration->getTempoApiUrl() . "worklogs";

        $result = $this->request($url, $parameters->getPostParams(), 'POST');

        return $this->getWorkLogFromJson($result);
    }

    /**
     * @param object $json
     * @return WorkLog|object
     * @throws \JsonMapper_Exception
     */
    protected function getWorkLogFromJson(object $json): WorkLog
    {
        return $this->jsonMapper->map(
            $json, new WorkLog()
        );
    }
}
