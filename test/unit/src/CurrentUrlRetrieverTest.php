<?php

namespace perf\CurrentUrlRetriever;

use perf\CurrentUrlRetriever\Exception\CurrentUrlRetrieverException;
use PHPUnit\Framework\TestCase;

class CurrentUrlRetrieverTest extends TestCase
{
    private CurrentUrlRetriever $retriever;

    protected function setUp(): void
    {
        $this->retriever = new CurrentUrlRetriever();
    }

    public function testCreate()
    {
        $result = CurrentUrlRetriever::create();

        $this->assertInstanceOf(CurrentUrlRetriever::class, $result);
    }

    public function testRetrieveWithUnresolvableHostWillThrowException()
    {
        $serverValues = [
            'REQUEST_URI' => '/baz.qux?abc=def',
        ];

        $this->expectException(CurrentUrlRetrieverException::class);
        $this->expectExceptionMessage('Unable to retrieve host.');

        $this->retriever->retrieve($serverValues);
    }

    public function testRetrieveWithUnresolvableUrlWillThrowException()
    {
        $serverValues = [
            'HTTP_HOST' => 'foo.bar',
        ];

        $this->expectException(CurrentUrlRetrieverException::class);
        $this->expectExceptionMessage('Unable to retrieve URI.');

        $this->retriever->retrieve($serverValues);
    }

    public function testRetrieve()
    {
        $serverValues = [
            'HTTP_HOST'   => 'foo.bar',
            'REQUEST_URI' => '/baz.qux?abc=def',
        ];

        $result = $this->retriever->retrieve($serverValues);

        $this->assertSame('http://foo.bar/baz.qux?abc=def', $result);
    }

    public function testRetrieveWithHttpsProtocol()
    {
        $serverValues = [
            'HTTP_HOST'   => 'foo.bar',
            'REQUEST_URI' => '/baz.qux?abc=def',
            'HTTPS'       => '1',
        ];

        $result = $this->retriever->retrieve($serverValues);

        $this->assertSame('https://foo.bar/baz.qux?abc=def', $result);
    }
}
