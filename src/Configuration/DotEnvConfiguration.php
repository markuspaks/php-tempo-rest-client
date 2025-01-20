<?php

namespace TempoRestApi\Configuration;

use Dotenv\Dotenv;

/**
 * Class DotEnvConfiguration.
 */
class DotEnvConfiguration extends AbstractConfiguration
{
    /**
     * DotEnvConfiguration constructor.
     * @param string $path
     */
    public function __construct(string $path = '.')
    {
        $this->loadDotEnv($path);

        $this->tempoApiUrl = $this->env('TEMPO_API_URL');

        $this->tempoAuthType = $this->env('TEMPO_AUTH_TYPE');

        $this->tempoToken = $this->env('TEMPO_TOKEN');

        $this->tempoClientId = $this->env('TEMPO_CLIENT_ID');
        $this->tempoClientSecret = $this->env('TEMPO_CLIENT_SECRET');
        $this->redirectUri = $this->env('TEMPO_REDIRECT_URI');
        $this->urlAuthorize = $this->env('TEMPO_URL_AUTHORIZE');
        $this->urlAccessToken = $this->env('TEMPO_URL_ACCESS_TOKEN');
        $this->urlResourceOwnerDetails = $this->env('TEMPO_URL_RESOURCE_OWNER_DETAILS');

        $this->tempoLogEnabled = $this->env('TEMPO_LOG_ENABLED', true);
        $this->tempoLogFile = $this->env('TEMPO_LOG_FILE', 'jira-rest-client.log');
        $this->tempoLogLevel = $this->env('TEMPO_LOG_LEVEL', 'WARNING');

        $this->curlOptSslVerifyHost = $this->env('CURLOPT_SSL_VERIFYHOST', false);
        $this->curlOptSslVerifyPeer = $this->env('CURLOPT_SSL_VERIFYPEER', false);
        $this->curlOptUserAgent = $this->env('CURLOPT_USERAGENT', $this->getDefaultUserAgentString());
        $this->curlOptVerbose = $this->env('CURLOPT_VERBOSE', false);
    }

    /**
     * Gets the value of an environment variable. Supports boolean, empty and null.
     *
     * @param string $key
     * @param mixed $default
     *
     * @return mixed
     */
    private function env(string $key, $default = null)
    {
        $value = getenv($key);

        if ($value === false) {
            return $default;
        }

        switch (strtolower($value)) {
            case 'true':
            case '(true)':
                return true;

            case 'false':
            case '(false)':
                return false;

            case 'empty':
            case '(empty)':
                return '';

            case 'null':
            case '(null)':
                return null;
        }

        if ($this->startsWith($value, '"') && $this->endsWith($value, '"')) {
            return substr($value, 1, -1);
        }

        return $value;
    }

    /**
     * Determine if a given string starts with a given substring.
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public function startsWith($haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ($needle != '' && strpos($haystack, $needle) === 0) {
                return true;
            }
        }

        return false;
    }

    /**
     * Determine if a given string ends with a given substring.
     *
     * @param string $haystack
     * @param string|array $needles
     *
     * @return bool
     */
    public function endsWith($haystack, $needles): bool
    {
        foreach ((array)$needles as $needle) {
            if ((string)$needle === substr($haystack, -strlen($needle))) {
                return true;
            }
        }

        return false;
    }

    /**
     * load dotenv.
     * @param string $path
     */
    private function loadDotEnv(string $path)
    {
        $dotEnv = Dotenv::create($path);
        $dotEnv->load();
        $dotEnv->required('TEMPO_AUTH_TYPE');

        if (getenv('TEMPO_AUTH_TYPE') === 'token') {
            $dotEnv->required([
                'TEMPO_TOKEN'
            ]);
        } else {
            $dotEnv->required([
                'TEMPO_CLIENT_ID',
                'TEMPO_CLIENT_SECRET',
                'TEMPO_REDIRECT_URI',
                'TEMPO_URL_AUTHORIZE',
            ]);
        }
    }
}
