<?php

declare(strict_types=1);

namespace Docker\Endpoint;

use Docker\API\Client;
use Docker\API\Endpoint\ContainerAttachWebsocket as BaseEndpoint;
use Docker\API\Runtime\Client\Exception\InvalidFetchModeException;
use Docker\Stream\AttachWebsocketStream;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Serializer\SerializerInterface;
use function array_merge;
use function base64_encode;
use function sprintf;
use function uniqid;

class ContainerAttachWebsocket extends BaseEndpoint
{

    public function getExtraHeaders(): array
    {
        return array_merge(parent::getExtraHeaders(), [
            'Host' => 'localhost',
            'Origin' => 'php://docker-php',
            'Upgrade' => 'websocket',
            'Connection' => 'Upgrade',
            'Sec-WebSocket-Version' => '13',
            'Sec-WebSocket-Key' => base64_encode(uniqid()),
        ]);
    }

    public function parseResponse(ResponseInterface $response, SerializerInterface $serializer, string $fetchMode = Client::FETCH_OBJECT)
    {
        if (Client::FETCH_OBJECT === $fetchMode) {
            if (101 === $response->getStatusCode()) {
                return new AttachWebsocketStream($response->getBody());
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
