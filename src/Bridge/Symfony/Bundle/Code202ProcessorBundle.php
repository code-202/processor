<?php

namespace Code202\Processor\Bridge\Symfony\Bundle;

use Code202\Processor\Bridge\Symfony\DependencyInjection\Compiler\ChainProcessorPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class Code202ProcessorBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new ChainProcessorPass());
    }
}
