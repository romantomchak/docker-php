<?php

declare(strict_types=1);

namespace Docker\Endpoint;

use Docker\API\Client;
use Docker\API\Endpoint\ContainerLogs as BaseEndpoint;
use Docker\API\Runtime\Client\Exception\InvalidFetchModeException;
use Docker\Stream\DockerRawStream;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;
use function sprintf;

class ContainerLogs extends BaseEndpoint
{

    public function parseResponse(ResponseInterface $response, SerializerInterface $serializer, string $fetchMode = Client::FETCH_OBJECT)
    {
        if (Client::FETCH_OBJECT === $fetchMode) {
            if (200 === $response->getStatusCode()) {
                return new DockerRawStream($response->getBody());
            }

            return $this->transformResponseBody(
                (string) $response->getBody(),
                $response->getStatusCode(),
                $serializer,
                $response->getHeaderLine('Content-Type')
            );
        }

        if (Client::FETCH_RESPONSE === $fetchMode) {
            return $response;
        }

        throw new InvalidFetchModeException(sprintf('Fetch mode %s is not supported', $fetchMode));
    }
}
