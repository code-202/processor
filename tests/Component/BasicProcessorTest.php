<?php

namespace Code202\Processor\Component\Test;

use Code202\Processor\Component\BasicProcessor;
use Code202\Processor\Component\ResponseStatusCode;
use PHPUnit\Framework\TestCase;

class BasicProcessorTest extends TestCase
{
    private $processor;

    public function setup(): void
    {
        $this->processor = new BasicProcessor('foo', 'bar');
    }

    public function testUnsupported()
    {
        $request = 'bar';

        $this->assertFalse($this->processor->supports($request));

        $this->assertEquals(ResponseStatusCode::NOT_SUPPORTED, $this->processor->process($request)->getStatusCode());
    }

    public function testProcess()
    {
        $request = 'foo';

        $this->assertTrue($this->processor->supports($request));

        $response = $this->processor->process($request);

        $this->assertEquals($request, $response->getRequest());
        $this->assertEquals(ResponseStatusCode::OK, $response->getStatusCode());
        $this->assertEquals('bar', $response->getOutput());
    }
}
