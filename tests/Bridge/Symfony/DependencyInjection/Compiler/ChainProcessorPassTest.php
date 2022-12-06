<?php

namespace Code202\Processor\Tests\Bridge\Symfony\DependencyInjection\Compiler;

use Code202\Processor\Bridge\Symfony\DependencyInjection\Compiler\ChainProcessorPass;
use Matthias\SymfonyDependencyInjectionTest\PhpUnit\AbstractCompilerPassTestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class ChainProcessorPassTest extends AbstractCompilerPassTestCase
{
    protected function registerCompilerPass(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new ChainProcessorPass());
    }

    public function testCollectOfService()
    {
        $chain = new Definition();
        $chain->addTag('code202.processor.chain');
        $this->setDefinition('my_chain', $chain);

        $p1 = new Definition();
        $p1->addTag('my_chain');
        $this->setDefinition('p1', $p1);

        $p2 = new Definition();
        $this->setDefinition('p2', $p2);

        $this->compile();

        $this->assertContainerBuilderHasServiceDefinitionWithMethodCall(
            'my_chain',
            'add',
            [
                new Reference('p1'),
                'p1',
                10,
            ]
        );
    }
}
