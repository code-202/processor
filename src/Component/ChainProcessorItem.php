<?php

namespace Code202\Processor\Component;

class ChainProcessorItem
{
    private Processor $processor;

    private string $name;

    private int $priority;

    public function __construct(
        Processor $processor,
        string $name,
        int $priority
    ) {
        $this->processor = $processor;
        $this->name = $name;
        $this->priority = $priority;
    }

    public function getProcessor(): Processor
    {
        return $this->processor;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPriority(): int
    {
        return $this->priority;
    }
}
