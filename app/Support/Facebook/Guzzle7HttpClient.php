<?php

namespace App\Support\Facebook;

use Facebook\HttpClients\FacebookHttpClientInterface;
use GuzzleHttp\Client;
use Psr\Http\Message\ResponseInterface;

class Guzzle7HttpClient implements FacebookHttpClientInterface
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function send($url, $method, $body, array $headers, $timeOut)
    {
        $options = [
            'headers'         => $headers,
            'body'            => $body ?? '',
            'timeout'         => $timeOut ?: 60,
            'connect_timeout' => 10,
            'http_errors'     => false,
        ];

        /** @var ResponseInterface $psrResponse */
        $psrResponse = $this->client->request($method, $url, $options);

        return new FacebookResponseAdapter($psrResponse);
    }
}
