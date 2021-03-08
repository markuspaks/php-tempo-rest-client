<?php

namespace TempoRestApi\Configuration;

/**
 * Interface ConfigurationInterface.
 */
interface ConfigurationInterface
{
    /**
     * Authorization type [token or oauth2]
     *
     * @return string
     */
    public function getTempoAuthType(): string;


    /**
     * Tempo API client token
     *
     * @return string
     */
    public function getTempoToken(): string;

    /**
     * Tempo API Client ID.
     *
     * @return string
     */
    public function getTempoClientId(): string;

    /**
     * Tempo API Client Secret.
     *
     * @return string
     */
    public function getTempoClientSecret(): string;

    /**
     * OAuth2 Redirect URI
     *
     * @return string
     */
    public function getRedirectUri(): string;

    /**
     * OAuth2 URL Authorize
     *
     * @return string
     */
    public function getUrlAuthorize(): string;

    /**
     * OAuth2 URL Access Token
     *
     * @return string
     */
    public function getUrlAccessToken(): string;

    /**
     * Enabled write to log.
     *
     * @return bool
     */
    public function getUrlResourceOwnerDetails(): string;


    /**
     * Is Tempo log enabled
     * @return bool
     */
    public function getTempoLogEnabled(): bool;

    /**
     * Path to log file.
     *
     * @return string
     */
    public function getTempoLogFile(): string;

    /**
     * Log level (DEBUG, INFO, ERROR, WARNING).
     *
     * @return string
     */
    public function getTempoLogLevel(): string;

    /**
     * Curl options CURLOPT_SSL_VERIFYHOST.
     *
     * @return bool
     */
    public function isCurlOptSslVerifyHost(): bool;

    /**
     * Curl options CURLOPT_SSL_VERIFYPEER.
     *
     * @return bool
     */
    public function isCurlOptSslVerifyPeer(): bool;

    /**
     * Curl options CURLOPT_VERBOSE.
     *
     * @return bool
     */
    public function isCurlOptVerbose(): bool;

    /**
     * Get curl option CURLOPT_USERAGENT.
     *
     * @return string
     */
    public function getCurlOptUserAgent(): string;
}
