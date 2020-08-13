<?php

namespace perf\CurrentUrlRetriever;

use perf\CurrentUrlRetriever\Exception\CurrentUrlRetrieverException;

class CurrentUrlRetriever implements CurrentUrlRetrieverInterface
{
    private const KEY_HOST     = 'HTTP_HOST';
    private const KEY_PROTOCOL = 'HTTPS';
    private const KEY_URI      = 'REQUEST_URI';

    /**
     * @var {string:string}
     */
    private array $serverValues = [];

    public static function create(): self
    {
        return new self();
    }

    /**
     * {@inheritDoc}
     */
    public function retrieve(array $serverValues = null): string
    {
        $this->serverValues = $this->getServerValues($serverValues);

        $protocol = $this->getProtocol();
        $host     = $this->getHost();
        $uri      = $this->getUri();

        $this->serverValues = [];

        return "{$protocol}://{$host}{$uri}";
    }

    /**
     * @param null|array $serverValues Values from $_SERVER superglobal variable.
     *
     * @return array
     *
     * @throws CurrentUrlRetrieverException
     *
     * @SuppressWarnings(PHPMD.Superglobals)
     */
    private function getServerValues(?array $serverValues): array
    {
        if (null !== $serverValues) {
            return $serverValues;
        }

        if (!isset($_SERVER)) {
            $message = 'Unable to retrieve visitor IP address: '
                . 'superglobal variable $_SERVER is not set. '
                . 'Calling retriever from command line?';

            throw new CurrentUrlRetrieverException($message);
        }

        return $_SERVER;
    }

    private function getProtocol(): string
    {
        if (empty($this->serverValues[self::KEY_PROTOCOL])) {
            return 'http';
        }

        return 'https';
    }

    /**
     * @return string
     *
     * @throws CurrentUrlRetrieverException
     */
    private function getHost(): string
    {
        if (empty($this->serverValues[self::KEY_HOST])) {
            throw new CurrentUrlRetrieverException('Unable to retrieve host.');
        }

        return $this->serverValues[self::KEY_HOST];
    }

    /**
     * @return string
     *
     * @throws CurrentUrlRetrieverException
     */
    private function getUri(): string
    {
        if (empty($this->serverValues[self::KEY_URI])) {
            throw new CurrentUrlRetrieverException('Unable to retrieve URI.');
        }

        return $this->serverValues[self::KEY_URI];
    }
}
