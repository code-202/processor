<?php

namespace Code202\Processor\Component\Tests;

use Code202\Processor\Component\Response;
use Code202\Processor\Component\ResponseStatusCode;
use PHPUnit\Framework\TestCase;

class ResponseTest extends TestCase
{
    protected $request;

    public function setup(): void
    {
        $this->request = 'request';
    }

    public function testConstructWithDefaultValuesAndGetters()
    {
        $output = new \stdClass();

        $response = new Response($this->request, $output);

        $this->assertEquals($this->request, $response->getRequest());
        $this->assertEquals($output, $response->getOutput());
        $this->assertEquals(ResponseStatusCode::OK, $response->getStatusCode());
        $this->assertEquals('', $response->getReasonPhrase());
    }

    public function testConstructAndGetters()
    {
        $output = new \stdClass();
        $statusCode = ResponseStatusCode::NOT_SUPPORTED;
        $reasonPhrase = 'reason_phrase';

        $response = new Response($this->request, $output, $statusCode, $reasonPhrase);
        $response->setExtra('foo', 'bar');

        $this->assertEquals($this->request, $response->getRequest());
        $this->assertEquals($output, $response->getOutput());
        $this->assertEquals($statusCode, $response->getStatusCode());
        $this->assertEquals($reasonPhrase, $response->getReasonPhrase());
        $this->assertEquals('bar', $response->getExtra('foo'));
    }
}
