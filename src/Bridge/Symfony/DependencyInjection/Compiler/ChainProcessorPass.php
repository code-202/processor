<?php

namespace Code202\Processor\Bridge\Symfony\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ChainProcessorPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container)
    {
        $chainTaggedServices = $container->findTaggedServiceIds('code202.processor.chain');

        $resolver = $this->createOptionResolver();

        foreach ($chainTaggedServices as $id => $tags) {
            $definition = $container->getDefinition($id);

            $taggedServices = $container->findTaggedServiceIds($id);
            foreach ($taggedServices as $itemId => $itemTags) {
                foreach ($itemTags as $tagOptions) {
                    $options = $resolver->resolve($tagOptions);

                    $definition->addMethodCall(
                        'add',
                        [
                            new Reference($itemId),
                            $itemId,
                            $options['priority'],
                        ]
                    );
                }
            }
        }
    }

    protected function createOptionResolver()
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'priority' => 10,
        ]);

        $resolver->setAllowedTypes('priority', ['int']);

        return $resolver;
    }
}
