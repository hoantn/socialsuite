<?php

namespace App\Support\Facebook;

use Psr\Http\Message\ResponseInterface;

class FacebookResponseAdapter
{
    protected ResponseInterface $response;

    public function __construct(ResponseInterface $response)
    {
        $this->response = $response;
    }

    public function getBody()
    {
        return (string) $this->response->getBody();
    }

    public function getHttpResponseCode(): int
    {
        return $this->response->getStatusCode();
    }

    public function getHeaders(): array
    {
        return $this->response->getHeaders();
    }
}
