<?php

namespace Code202\Processor\Component;

trait ChainProcessorTrait
{
    protected array $processors = [];

    public function add(
        Processor $processor,
        string $name = '',
        int $priority = 10
    ) {
        $this->processors[] = new ChainProcessorItem($processor, $name, $priority);

        usort($this->processors, function ($a, $b) {
            return $a->getPriority() > $b->getPriority();
        });

        return $this;
    }

    public function supports($request): bool
    {
        foreach ($this->processors as $item) {
            if ($item->getProcessor()->supports($request)) {
                return true;
            }
        }

        return false;
    }

    protected function doProcess($request): ?Response
    {
        $item = $this->findProcessor($request);

        if ($item) {
            $response = $item->getProcessor()->process($request);
            $response->setExtra('name', $item->getName());

            return $response;
        }
    }

    protected function findProcessor($request): ?ChainProcessorItem
    {
        foreach ($this->processors as $item) {
            $processor = $item->getProcessor();
            if ($processor->supports($request)) {
                return $item;
            }
        }
    }
}
