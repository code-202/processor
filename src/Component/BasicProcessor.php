<?php

namespace Code202\Processor\Component;

class BasicProcessor implements Processor
{
    protected mixed $supportedInput;

    protected mixed $output;

    public function __construct(
        mixed $supportedInput,
        mixed $output
    ) {
        $this->supportedInput = $supportedInput;
        $this->output = $output;
    }

    public function supports($request): bool
    {
        if ($request != $this->supportedInput) {
            return false;
        }

        return true;
    }

    public function process($request): Response
    {
        if ($this->supports($request)) {
            return new Response($request, $this->output);
        }

        return new Response($request, null, ResponseStatusCode::NOT_SUPPORTED);
    }
}
