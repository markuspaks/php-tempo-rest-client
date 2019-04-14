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
     * @param string|null $from
     * @param string|null $to
     * @param string|null $updatedFrom
     * @param int $offset
     * @param int $limit
     * @return object
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     * @throws \TempoRestApi\TempoException
     */
    public function getList(
        string $from = null,
        string $to = null,
        string $updatedFrom = null,
        int $offset = 0,
        int $limit = 50
    ) {
        $url = $this->tempoApiUrl . "worklogs?updatedFrom={$updatedFrom}";

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