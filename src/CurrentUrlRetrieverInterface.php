<?php

namespace perf\CurrentUrlRetriever;

use perf\CurrentUrlRetriever\Exception\CurrentUrlRetrieverException;

interface CurrentUrlRetrieverInterface
{
    /**
     * Attempts to retrieve current URL.
     *
     * @param null|array $serverValues Values from $_SERVER superglobal variable (optional).
     *
     * @return string
     *
     * @throws CurrentUrlRetrieverException
     */
    public function retrieve(array $serverValues = null): string;
}
