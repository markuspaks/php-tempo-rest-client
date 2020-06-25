<?php

namespace TempoRestApi\Configuration;

/**
 * Class AbstractConfiguration.
 */
abstract class AbstractConfiguration implements ConfigurationInterface
{
    /**
     * Authorization type [token or oauth2]
     *
     * @var string
     */
    protected $tempoAuthType = 'token';

    /**
     * Authorization token
     *
     * @var string
     */
    protected $tempoToken = '';

    /**
     * Tempo ClientId
     *
     * @var string
     */
    protected $tempoClientId = '';

    /**
     * Tempo ClientSecret
     *
     * @var string
     */
    protected $tempoClientSecret = '';

    /**
     * Tempo Redirect URI
     *
     * @var string
     */
    protected $redirectUri = '';

    /**
     * Tempo Url Authorize
     *
     * @var string
     */
    protected $urlAuthorize = '';

    /**
     * Tempo Url AccessToken
     *
     * @var string
     */
    protected $urlAccessToken = '';

    /**
     * Tempo Url ResourceOwnerDetails
     *
     * @var string
     */
    protected $urlResourceOwnerDetails = '';

    /**
     * Path to log file.
     *
     * @var string
     */
    protected $tempoLogEnabled = true;

    /**
     * Path to log file.
     *
     * @var string
     */
    protected $tempoLogFile;

    /**
     * Log level (DEBUG, INFO, ERROR, WARNING).
     *
     * @var string
     */
    protected $tempoLogLevel;

    /**
     * Curl options CURLOPT_SSL_VERIFYHOST.
     *
     * @var bool
     */
    protected $curlOptSslVerifyHost;

    /**
     * Curl options CURLOPT_SSL_VERIFYPEER.
     *
     * @var bool
     */
    protected $curlOptSslVerifyPeer;

    /**
     * Curl option CURLOPT_USERAGENT.
     *
     * @var string
     */
    protected $curlOptUserAgent;

    /**
     * Curl options CURLOPT_VERBOSE.
     *
     * @var bool
     */
    protected $curlOptVerbose;

    /**
     * @return string
     */
    public function getTempoAuthType(): string
    {
        return $this->tempoAuthType;
    }

    /**
     * @return string
     */
    public function getTempoToken(): string
    {
        return $this->tempoToken;
    }

    /**
     * @return string
     */
    public function getTempoClientId(): string
    {
        return $this->tempoClientId;
    }

    /**
     * @return string
     */
    public function getTempoClientSecret(): string
    {
        return $this->tempoClientSecret;
    }

    /**
     * @return string
     */
    public function getRedirectUri(): string
    {
        return $this->redirectUri;
    }

    /**
     * @return string
     */
    public function getUrlAuthorize(): string
    {
        return $this->urlAuthorize;
    }

    /**
     * @return string
     */
    public function getUrlAccessToken(): string
    {
        return $this->urlAccessToken;
    }

    /**
     * @return string
     */
    public function getUrlResourceOwnerDetails(): string
    {
        return $this->urlResourceOwnerDetails;
    }

    /**
     * @return bool
     */
    public function getTempoLogEnabled(): bool
    {
        return $this->tempoLogEnabled;
    }

    /**
     * @return string
     */
    public function getTempoLogFile(): string
    {
        return $this->tempoLogFile;
    }

    /**
     * @return string
     */
    public function getTempoLogLevel(): string
    {
        return $this->tempoLogLevel;
    }

    /**
     * @return bool
     */
    public function isCurlOptSslVerifyHost(): bool
    {
        return $this->curlOptSslVerifyHost;
    }

    /**
     * @return bool
     */
    public function isCurlOptSslVerifyPeer(): bool
    {
        return $this->curlOptSslVerifyPeer;
    }

    /**
     * @return bool
     */
    public function isCurlOptVerbose(): bool
    {
        return $this->curlOptVerbose;
    }

    /**
     * Get curl option CURLOPT_USERAGENT.
     *
     * @return string
     */
    public function getCurlOptUserAgent(): string
    {
        return $this->curlOptUserAgent;
    }

    /**
     * get default User-Agent String.
     *
     * @return string
     */
    public function getDefaultUserAgentString(): string
    {
        $curlVersion = curl_version();

        return sprintf('curl/%s (%s)', $curlVersion['version'], $curlVersion['host']);
    }
}
