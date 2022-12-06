<?php

namespace Code202\Processor\Component;

trait ProcessorTrait
{
    abstract public function supports($request): bool;

    final public function process($request): Response
    {
        if (!$this->supports($request)) {
            return new Response($request, null, ResponseStatusCode::NOT_SUPPORTED);
        }

        try {
            $response = $this->doProcess($request);
        } catch (\Throwable $e) {
            return new Response($request, $e, ResponseStatusCode::INTERNAL_ERROR, $e->getMessage());
        }

        if (null === $response) {
            return new Response($request, null, ResponseStatusCode::INTERNAL_ERROR);
        }

        if (!$response instanceof Response) {
            $response = new Response($request, $response, ResponseStatusCode::OK);
        }

        return $response;
    }

    abstract protected function doProcess($request): ?Response;
}
