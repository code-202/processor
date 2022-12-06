<?php

namespace Code202\Processor\Component;

interface Processor
{
    /**
     * Return if the processor can proceed this request.
     */
    public function supports($request): bool;

    /**
     * Try to proceed this request.
     */
    public function process($request): Response;
}
