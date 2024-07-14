<?php

namespace GeoTool\Service\Request;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

/**
 * Service for handling standard request via POST / GET etc.
 */
class GuzzleService implements GuzzleServiceInterface
{
    // info: adding timeout as sometimes some services seem to be choking the connection, without wanting to let the connection go away
    private array $defaultOptions = [
        'timeout'         => 5, // Response timeout
        'connect_timeout' => 5, // Connection timeout
    ];

    /**
     * @var Client $client
     */
    private Client $client;

    /**
     * @param Client $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * Will perform get request toward provided url
     *
     * @param string $url - url to be called
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function get(string $url, array $options = []): ResponseInterface
    {
        $usedOptions = [
            ...$this->defaultOptions,
            ...$options,
        ];

        return $this->client->get($url, $usedOptions);
    }


    /**
     * Will perform post request toward provided url
     *
     * @param string $url - url to be called
     * @param array  $options
     *
     * @return ResponseInterface
     * @throws GuzzleException
     */
    public function post(string $url, array $options = []): ResponseInterface
    {
        $usedOptions = [
            ...$this->defaultOptions,
            ...$options,
        ];

        return $this->client->post($url, $usedOptions);
    }

}