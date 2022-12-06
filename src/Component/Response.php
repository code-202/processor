<?php

namespace Code202\Processor\Component;

class Response
{
    private mixed $request;

    private mixed $output;

    private ResponseStatusCode $statusCode;

    private string $reasonPhrase;

    private array $extras;

    public function __construct(
        mixed $request,
        mixed $output,
        ResponseStatusCode $statusCode = ResponseStatusCode::OK,
        string $reasonPhrase = ''
    ) {
        $this->request = $request;

        $this->output = $output;

        $this->statusCode = $statusCode;

        $this->reasonPhrase = $reasonPhrase;

        $this->extras = [];
    }

    public function getRequest(): mixed
    {
        return $this->request;
    }

    public function getOutput(): mixed
    {
        return $this->output;
    }

    public function getStatusCode(): ResponseStatusCode
    {
        return $this->statusCode;
    }

    public function getReasonPhrase(): string
    {
        return $this->reasonPhrase;
    }

    public function getExtra($key): mixed
    {
        return isset($this->extras[$key]) ? $this->extras[$key] : null;
    }

    public function setExtra(string $key, mixed $extra)
    {
        $this->extras[$key] = $extra;

        return $this;
    }

    public function isSuccessed(): bool
    {
        return ResponseStatusCode::OK === $this->getStatusCode();
    }
}
