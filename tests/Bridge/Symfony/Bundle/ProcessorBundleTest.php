<?php

namespace Code202\Processor\Tests\Bridge\Symfony\Bundle;

use Code202\Processor\Bridge\Symfony\Bundle\Code202ProcessorBundle;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractContainerBuilderTestCase;

class ProcessorBundleTest extends AbstractContainerBuilderTestCase
{
    public function testBuild()
    {
        $bundle = new Code202ProcessorBundle();
        $bundle->build($this->container);

        $this->assertTrue(true);
    }
}
