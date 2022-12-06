<?php

namespace Code202\Processor\Component;

use PHPUnit\Framework\TestCase;

class AbstractProcessorTest extends TestCase
{
    public function testSupportedBeforeProcess()
    {
        $processor = $this->createMock(AbstractProcessor::class);
        $processor->expects($this->once())
            ->method('supports')
            ->willReturn(false)
        ;

        $response = $processor->process('test');

        $this->assertEquals(ResponseStatusCode::NOT_SUPPORTED, $response->getStatusCode());
    }

    public function testReturnBadResponseIfNull()
    {
        $processor = $this->createMock(AbstractProcessor::class);
        $processor->expects($this->once())
            ->method('supports')
            ->willReturn(true)
        ;

        $processor->expects($this->once())
            ->method('doProcess')
            ->willReturn(null)
        ;

        $response = $processor->process('test');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ResponseStatusCode::INTERNAL_ERROR, $response->getStatusCode());
    }

    public function testReturnBadResponseIfThrowException()
    {
        $processor = $this->createMock(AbstractProcessor::class);
        $processor->expects($this->once())
            ->method('supports')
            ->willReturn(true)
        ;

        $processor->expects($this->once())
            ->method('doProcess')
            ->will($this->throwException(new \Exception('Fail')))
        ;

        $response = $processor->process('test');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ResponseStatusCode::INTERNAL_ERROR, $response->getStatusCode());
        $this->assertInstanceOf(\Exception::class, $response->getOutput());
        $this->assertEquals('Fail', $response->getReasonPhrase());
    }

    public function testReturnGoodResponseIfNotNullAndNotResponse()
    {
        $processor = $this->createMock(AbstractProcessor::class);
        $processor->expects($this->once())
            ->method('supports')
            ->willReturn(true)
        ;

        $processor->expects($this->once())
            ->method('doProcess')
            ->willReturn(new Response('test', 'output'))
        ;

        $response = $processor->process('test');

        $this->assertInstanceOf(Response::class, $response);
        $this->assertEquals(ResponseStatusCode::OK, $response->getStatusCode());
        $this->assertEquals('output', $response->getOutput());
    }
}
