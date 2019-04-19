<?php

namespace TempoRestApi\Configuration;

/**
 * Class ArrayConfiguration.
 */
class ArrayConfiguration extends AbstractConfiguration
{
    /**
     * @param array $configuration
     */
    public function __construct(array $configuration)
    {
        $this->tempoAuthType = 'token';
        $this->tempoToken = '';
        $this->tempoLogEnabled = true;
        $this->tempoLogFile = 'tempo-rest-client.log';
        $this->tempoLogLevel = 'WARNING';
        $this->curlOptSslVerifyHost = false;
        $this->curlOptSslVerifyPeer = false;
        $this->curlOptVerbose = false;
        $this->curlOptUserAgent = $this->getDefaultUserAgentString();

        foreach ($configuration as $key => $value) {
            if (property_exists($this, $key)) {
                $this->{$key} = $value;
            }
        }
    }
}
