<?php

namespace Tempo;

use GuzzleHttp\Exception\ClientException;
use League\OAuth2\Client\Provider\GenericProvider;
use League\OAuth2\Client\Token\AccessToken;
use Tempo\Configuration\ConfigurationInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Tempo\Configuration\DotEnvConfiguration;

/**
 * Interact jira server with REST API.
 */
class TempoClient
{
    /**
     * Json Mapper.
     *
     * @var \JsonMapper
     */
    public $jsonMapper;

    /**
     * HTTP response code.
     *
     * @var string
     */
    protected $http_response;

    /**
     * JIRA REST API URI.
     *
     * @var string
     */
    private $api_uri = '/rest/api/2';

    /**
     * CURL instance.
     *
     * @var resource
     */
    protected $curl;

    /**
     * Monolog instance.
     *
     * @var \Monolog\Logger
     */
    protected $log;

    /**
     * Jira Rest API Configuration.
     *
     * @var ConfigurationInterface
     */
    protected $configuration;

    /**
     * Base TEMPO API url
     *
     * @var string
     */
    protected $tempoApiUrl = 'https://api.tempo.io/core/3/';

    /**
     * Constructor.
     *
     * @param ConfigurationInterface $configuration
     * @param LoggerInterface $logger
     * @param string $path
     *
     * @throws \Exception
     */
    public function __construct(
        ConfigurationInterface $configuration = null,
        LoggerInterface $logger = null,
        string $path = './'
    ) {
        $this->loadConfiguration($configuration, $path);
        $this->createJsonMapper();
        $this->initLogger($logger);

        $this->http_response = 200;
        $this->curl = curl_init();
    }

    /**
     * Find and load configuration
     *
     * @param ConfigurationInterface|null $configuration
     * @param string $path
     */
    protected function loadConfiguration(ConfigurationInterface $configuration = null, string $path = './')
    {
        if ($configuration === null) {
            if (!file_exists($path . '.env')) {
                // If calling the getcwd() on laravel it will returning the 'public' directory.
                $path = '../';
            }
            $this->configuration = new DotEnvConfiguration($path);
        } else {
            $this->configuration = $configuration;
        }
    }

    /**
     * Create JSON Mapper and handle exceptions
     */
    protected function createJsonMapper()
    {
        $this->jsonMapper = new \JsonMapper();

        // Fix "\JiraRestApi\JsonMapperHelper::class" syntax error, unexpected 'class' (T_CLASS), expecting identifier (T_STRING) or variable (T_VARIABLE) or '{' or '$'
        $this->jsonMapper->undefinedPropertyHandler = [new JsonMapperHelper(), 'setUndefinedProperty'];

        // Properties that are annotated with `@var \DateTimeInterface` should result in \DateTime objects being created.
        $this->jsonMapper->classMap['\\' . \DateTimeInterface::class] = \DateTime::class;
    }

    /**
     * @param LoggerInterface|null $logger
     * @throws \Exception
     */
    protected function initLogger(LoggerInterface $logger = null)
    {
        // create logger
        if ($this->configuration->getTempoLogEnabled()) {
            if ($logger) {
                $this->log = $logger;
            } else {
                $this->log = new Logger('JiraClient');
                $this->log->pushHandler(new StreamHandler(
                    $this->configuration->getTempoLogFile(),
                    $this->convertLogLevel($this->configuration->getTempoLogLevel())
                ));
            }
        } else {
            $this->log = new Logger('TempoApiClient');
            $this->log->pushHandler(new NoOperationMonologHandler());
        }
    }

    /**
     * Convert log level.
     *
     * @param $log_level
     *
     * @return int
     */
    private function convertLogLevel($log_level)
    {
        $log_level = strtoupper($log_level);
        $levels = Logger::getLevels();

        return $levels[$log_level] ?? Logger::WARNING;
    }

    /**
     * Serialize only not null field.
     *
     * @param array $haystack
     *
     * @return array
     */
    protected function filterNullVariable($haystack)
    {
        foreach ($haystack as $key => $value) {
            if (is_array($value)) {
                $haystack[$key] = $this->filterNullVariable($haystack[$key]);
            } elseif (is_object($value)) {
                $haystack[$key] = $this->filterNullVariable(get_class_vars(get_class($value)));
            }

            if (is_null($haystack[$key]) || empty($haystack[$key])) {
                unset($haystack[$key]);
            }
        }

        return $haystack;
    }

    private function getProvider()
    {
        $config = $this->configuration;

        $provider = new GenericProvider([
            'clientId' => $config->getTempoClientId(),
            'clientSecret' => $config->getTempoClientSecret(),
            'redirectUri' => $config->getRedirectUri(),
            'urlAuthorize' => $config->getUrlAuthorize(),
            'urlAccessToken' => $config->getUrlAccessToken(),
            'urlResourceOwnerDetails' => $config->getUrlResourceOwnerDetails(),
        ]);

        return $provider;
    }

    /**
     * @param GenericProvider $provider
     * @return AccessToken|\League\OAuth2\Client\Token\AccessTokenInterface|null
     * @throws TempoException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    private function getToken(GenericProvider $provider)
    {
        $token = $_SESSION['token'] ?? null;

        if ($token) {
            $token = new AccessToken($token);
        }

        if ($token) {
            if (!$token->hasExpired()) {
                return $token;
            }

            $newAccessToken = $provider->getAccessToken('refresh_token', [
                'refresh_token' => $token->getRefreshToken()
            ]);

            $_SESSION['token'] = $newAccessToken->jsonSerialize();

            return $newAccessToken;
        }

        return $this->authorize();
    }

    /**
     * @return \League\OAuth2\Client\Token\AccessTokenInterface
     * @throws TempoException
     */
    public function authorize()
    {
        $provider = $this->getProvider();

        if (!isset($_GET['code'])) {

            // Fetch the authorization URL from the provider; this returns the
            // urlAuthorize option and generates and applies any necessary parameters
            // (e.g. state).
            $authorizationUrl = $provider->getAuthorizationUrl() . '&access_type=tenant_user';

            // Get the state generated for you and store it to the session.
            $_SESSION['oauth2state'] = $provider->getState();

            // Redirect the user to the authorization URL.
            header("Location: " . $authorizationUrl);
            exit();

            // Check given state against previously stored one to mitigate CSRF attack
        } elseif (empty($_GET['state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);

            throw new TempoException('Invalid OAuth2 state');
        } else {
            try {
                // Try to get an access token using the authorization code grant.
                $accessToken = $provider->getAccessToken('authorization_code', [
                    'code' => $_GET['code']
                ]);

                $_SESSION['token'] = $accessToken->jsonSerialize();

                return $accessToken;
            } catch (\League\OAuth2\Client\Provider\Exception\IdentityProviderException $e) {
                // Failed to get the access token or user details.
                throw new TempoException($e->getMessage());
            }

        }
    }

    /**
     * @param string $url
     * @param array $params
     * @param string $method
     * @return object
     * @throws TempoException
     * @throws \League\OAuth2\Client\Provider\Exception\IdentityProviderException
     */
    public function request(string $url, array $params = [], string $method = 'GET')
    {
        $provider = $this->getProvider();

        if ($this->configuration->getTempoAuthType() === 'oauth2') {
            $accessToken = $this->getToken($provider);
        } else {
            $accessToken = $this->configuration->getTempoToken();
        }

        try {
            $request = $provider->getAuthenticatedRequest(
                $method,
                $url,
                $accessToken
            );

            if ($params) {
                $request->getBody()->write(json_encode($params));
            }

            $response = $provider->getResponse($request)->getBody()->getContents();

            return json_decode($response);
        } catch (ClientException $ce) {
            $content = json_decode($ce->getResponse()->getBody()->getContents(), true);
            $errors = $content['errors'] ?? [];
            $error = sizeof($errors) ? reset($errors) : [];

            throw new TempoException($error['message'] ?? $ce->getMessage(), $ce->getResponse()->getStatusCode());
        } catch (\Exception $e) {
            throw new TempoException($e->getMessage());
        }
    }
}
