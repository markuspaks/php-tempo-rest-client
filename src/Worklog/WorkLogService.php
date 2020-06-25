<?php

namespace Tempo\WorkLog;

use Tempo\TempoClient;
use Tempo\TempoException;

/**
 * Class WorkLogService
 * @package TempoRest\WorkLog
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
        $url = $this->tempoApiUrl . "worklogs/{$workLogId}";

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
     * @throws \Tempo\InvalidInstanceException
     */
    public function getList(WorkLogListParameters $parameters): WorkLogResultSet
    {
        $workLogs = new WorkLogResultSet($this);

        $url = $this->tempoApiUrl . "worklogs?" . $parameters->getHttpQuery();

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
        $url = $this->tempoApiUrl . "worklogs";

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
