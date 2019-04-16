<?php

namespace TempoRestApi\WorkLog;

use TempoRestApi\TempoClient;
use TempoRestApi\TempoException;

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
     * @param WorkLogParameters $parameters
     *
     * @return object
     * @throws TempoException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function getList(WorkLogParameters $parameters)
    {
        $url = $this->tempoApiUrl . "worklogs?" . $parameters->getHttpQuery();

        return $this->request($url);
    }

    /**
     * @param object $json
     * @return WorkLog
     * @throws \JsonMapper_Exception
     */
    protected function getWorkLogFromJson(object $json): WorkLog
    {
        return $this->jsonMapper->map(
            $json, new WorkLog()
        );
    }
}