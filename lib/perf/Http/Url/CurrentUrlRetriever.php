<?php

namespace perf\Http\Url;

/**
 *
 *
 */
class CurrentUrlRetriever
{

    /**
     *
     * Temporary property.
     *
     * @var {string:string}
     */
    private $serverValues = array();

    /**
     * Static constructor.
     *
     * @return CurrentUrlRetriever
     */
    public static function create()
    {
        return new self();
    }

    /**
     * Attempts to retrieve current URL.
     *
     * @param null|array $serverValues Values from $_SERVER superglobal variable (optional).
     * @return string
     * @throws \RuntimeException
     */
    public function retrieve(array $serverValues = null)
    {
        $this->serverValues = $this->getServerValues($serverValues);

        $protocol = $this->getProtocol();
        $host     = $this->getHost();
        $uri      = $this->getUri();

        $this->serverValues = array();

        return "{$protocol}://{$host}{$uri}";
    }

    /**
     *
     *
     * @param null|array $serverValues Values from $_SERVER superglobal variable.
     * @return array
     * @throws \RuntimeException
     */
    private function getServerValues(array $serverValues = null)
    {
        if (null === $serverValues) {
            if (!isset($_SERVER)) {
                $message = 'Unable to retrieve visitor IP address: '
                         . 'superglobal variable $_SERVER is not set. '
                         . 'Calling retriever from command line?';

                throw new \RuntimeException($message);
            }

            $serverValues = $_SERVER;
        }

        return $serverValues;
    }

    /**
     *
     *
     * @return string
     */
    private function getProtocol()
    {
        static $key = 'HTTPS';

        if (empty($this->serverValues[$key])) {
            return 'http';
        }

        return 'https';
    }

    /**
     *
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getHost()
    {
        static $key = 'HTTP_HOST';

        if (empty($this->serverValues[$key])) {
            throw new \RuntimeException('Unable to retrieve host.');
        }

        return $this->serverValues[$key];
    }

    /**
     *
     *
     * @return string
     * @throws \RuntimeException
     */
    private function getUri()
    {
        static $key = 'REQUEST_URI';

        if (empty($this->serverValues[$key])) {
            throw new \RuntimeException('Unable to retrieve URI.');
        }

        return $this->serverValues[$key];
    }
}
