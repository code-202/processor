<?php

namespace Code202\Processor\Component\Test;

use Code202\Processor\Component\ChainProcessorItem;
use Code202\Processor\Component\Processor;
use PHPUnit\Framework\TestCase;

class ChainProcessorItemTest extends TestCase
{
    public function testConstructValuesAndGetters()
    {
        $processor = $this->createMock(Processor::class);

        $item = new ChainProcessorItem($processor, 'foo', 5);

        $this->assertEquals($processor, $item->getProcessor());
        $this->assertEquals('foo', $item->getName());
        $this->assertEquals(5, $item->getPriority());
    }
}
