<?php

namespace Code202\Processor\Component\Test;

use Code202\Processor\Component\BasicProcessor;
use Code202\Processor\Component\ChainProcessor;
use Code202\Processor\Component\ResponseStatusCode;
use PHPUnit\Framework\TestCase;

class ChainProcessorTest extends TestCase
{
    private $chainProcessor;

    public function setup(): void
    {
        $this->chainProcessor = new ChainProcessor();

        $this->chainProcessor
            ->add(
                new BasicProcessor('foo', 'bar'),
                'p1',
                5
            )
            ->add(
                new BasicProcessor('foo', 'bar'),
                'p2',
                3
            )
            ->add(
                new BasicProcessor('bar', 'foo'),
                'p3',
                1
            )
        ;
    }

    public function testNotImplement()
    {
        $request = 'foobar';

        $this->assertFalse($this->chainProcessor->supports($request));

        $this->assertEquals(ResponseStatusCode::NOT_SUPPORTED, $this->chainProcessor->process($request)->getStatusCode());
    }

    public function testChain()
    {
        $request = 'foo';

        $this->assertTrue($this->chainProcessor->supports($request));
        $response = $this->chainProcessor->process($request);
        $this->assertEquals('bar', $response->getOutput());
        $this->assertEquals('p2', $response->getExtra('name'));
    }
}
