<?php

namespace perf\Http\Url;

/**
 *
 */
class CurrentUrlRetrieverTest extends \PHPUnit_Framework_TestCase
{

    /**
     *
     */
    protected function setUp()
    {
        $this->retriever = new CurrentUrlRetriever();
    }

    /**
     *
     */
    public function testCreate()
    {
        $result = CurrentUrlRetriever::create();

        $this->assertInstanceOf('\\' . __NAMESPACE__ . '\\CurrentUrlRetriever', $result);
    }

    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unable to retrieve host.
     */
    public function testRetrieveWithUnresolvableHostWillThrowException()
    {
        $serverValues = array(
            'REQUEST_URI' => '/baz.qux?abc=def',
        );

        $this->retriever->retrieve($serverValues);
    }

    /**
     *
     * @expectedException \RuntimeException
     * @expectedExceptionMessage Unable to retrieve URI.
     */
    public function testRetrieveWithUnresolvableUrlWillThrowException()
    {
        $serverValues = array(
            'HTTP_HOST' => 'foo.bar',
        );

        $this->retriever->retrieve($serverValues);
    }

    /**
     *
     */
    public function testRetrieve()
    {
        $serverValues = array(
            'HTTP_HOST'   => 'foo.bar',
            'REQUEST_URI' => '/baz.qux?abc=def',
        );

        $result = $this->retriever->retrieve($serverValues);

        $this->assertSame('http://foo.bar/baz.qux?abc=def', $result);
    }

    /**
     *
     */
    public function testRetrieveWithHttpsProtocol()
    {
        $serverValues = array(
            'HTTP_HOST'   => 'foo.bar',
            'REQUEST_URI' => '/baz.qux?abc=def',
            'HTTPS'       => '1',
        );

        $result = $this->retriever->retrieve($serverValues);

        $this->assertSame('https://foo.bar/baz.qux?abc=def', $result);
    }
}
