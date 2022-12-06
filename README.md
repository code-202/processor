# Processor
Mechanism to process a work from a request

## Installation

You can install Processor with Composer
```bash
$ composer require code-202/processor
```

## How to use it ?
The main interest of this library is the `ChainProcessor`. It contains multiple processors
who can procced a particular work from a request and generate a response.

The `ChainProcessor` ask to each processors contains on it if it can proceed the job for a particular request.
The first processor who awser that it can do it will be use.
The response contains several things like the request itself, the status code, name of processor who do the job and obviously the output of the job.

### Build a __Request__
Before use the processor you have to build your request. You request can be what ever you want : a simple string, an array, a object...
This object will be use to determine if processor can do the job and to do it.

#### Example
```php
<?php

$object; // The object is the reason of your request.

$request = ['action' => 'validate', 'item' => $object];
```

### Proceed the job
You just have to use a `Processor` to try to do the job. The best way is to use a `ChainProcessor` who contains several processors.
```php
<?php

$chainProcessor; // We considere that this processors is already init with several processors

$request; // We considere that you already build the request

if ($chainProcessor->supports($request) { // Test if the processor can do the job
    $response = $chainProcessor->proccess($request); //Do the job
}
```

In fact you can directly try to do the job with a `ChainProcessor`.
If the job can't be done the processor return a response with the special status code `Code202\Processor\Component\ResponseStatusCode::NOT_IMPLEMENTED`.

### Use the __Response__
The fisrt thing that you have to do with a `Response` is to consult the status code to know how the job is done.
```php
<?php

use Code202\Processor\Component\ResponseStatusCode;

if ($response->getStatusCode() == ResponseStatusCode::OK) {
    // the job had been correctly done.
} else {
    $reasonPhrase = $response->getReasonPhrase();
    // The message who explain the cause of the status code
}
```

You can also know which `Processor` had done the job (when you use a `ChainProcessor`) with the next method :
```php
<?php

$response->getExtra('name');
```

Obviously you can get the initial request (i.e. to get the input) with the method `$response->getRequest()`.

And the more important is to get the result of the job.
```php
<?php

$response->getOutput();
```

## Build your own __Processor__
This library is not ready to play because there is no `Processor` define in it.
You have to create them to do the job that you want.

To do it you just have to create a class who implements `Code202\Processor\Component\Processor` interface.
```php
<?php

use Code202\Processor\Component\Processor;
use Code202\Processor\Component\Response;
use Code202\Processor\Component\ResponseStatusCode;

class CustomProcessor implements Processor
{
    public function supports($request)
    {
        // With the $request you have to decide if this processor can do the job

        // i.e.
        return $request instanceof MyClass;
    }

    public function process($request)
    {
        // Advice : check the method 'supports'
        if (!$this->supports($request)) {
            return new Response($request, null, ResponseStatusCode::NOT_SUPPORTED);
        }

        // do the job

        // and return the Response

        return Response($request, $output); // $output had been create during the job was doing
    }
}
```

Now, you just have to add it on a `ChainProcessor`.
```php
<?php

$chainProcessor->add(new CustomProcessor());
```

### Easiers ways
* use `Code202\Processor\Component\ProcessorTrait` and implements `Code202\Processor\Component\Processor`
* extends `Code202\Processor\Component\AbstractProcessor` (who use `Code202\Processor\Component\ProcessTrait`)

Why ? Because the process method :
* already test if request is supported by processor before proceed
* create and return a `Response` with `ResponseStatusCode::INTERNAL_ERROR` status code if your processor return `null`
* create and return a `Response` with `ResponseStatusCode::INTERNAL_ERROR` status code if your processor throw an exception.
* create and return a `Response` with `ResponseStatusCode::OK`  status code if your processor return a not null value who is not an instance of `Response`

The difference with previous method is that 'process' method have to be call 'doProcess' and be `protected`.

#### Example with `AbstractProcessor`
```php
<?php

use Code202\Processor\Component\AbstractProcessor;

class CustomProcessor extends AbstractProcessor
{
    public function supports($request)
    {
        // With the $request you have to decide if this processor can do the job

        // i.e.
        return $request instanceof MyClass;
    }

    protected function doPocess($request)
    {
        // No need to check if $request is supported

        // do the job

        // and return the Response
        // or nothing for bad response
        // or throw exception for bad response
        // or directly the ouput for bood response
    }
}
```

#### Example with `ProcessorTrait`

```php
<?php

use Code202\Processor\Component\Processor;
use Code202\Processor\Component\ProcessorTrait;

class CustomProcessor implements Processor
{
    use ProcessorTrait;

    public function supports($request)
    {
        // With the $request you have to decide if this processor can do the job

        // i.e.
        return $request instanceof MyClass;
    }

    protected function doPocess($request)
    {
        return $this->otherMethod($request);
    }
}
```

## Integration with other libraries

### Symfony
`code-202/processor` contains a `CompilerPass` to link automatically `Processor` to a `ChainProcessor`.

#### Configuration
You can use this `CompilerPass` with class `Code202\Processor\Bridge\Symfony\DependencyInjection\Compiler\ProcessorPass` or just add the bundle in your Symfony kernel like this :
```php
// app/AppKernel.php

public function registerBundles()
{
    $bundles = [
        // ...
        new Code202\Processor\Bridge\Symfony\Bundle\Code202ProcessorBundle(),
    ];

    // ...

    return $bundles;
}
```

To use it you have to require `symfony/dependency-injection` and `symfony/options-resolver` packages (if you don't use Symfony fullstack).

### Uses
Now you can use the tag `code202.processor.chain` on a service (which is instance of `ChainProcessor`).
Every services tagged with the name of this service will be add in it.

You can add `priority` parameter on the tag to set the priority of the processor (default priority is 10).

```yaml
services:
    my_chain:
        class: Code202\Processor\Component\ChainProcessor
        tags:
            - { name: 'code202.processor.chain' }

    p1:
        class: Foo\Processor
        tags:
            - { name: 'my_chain', priority: 5 }
    p2:
        class: Bar\Processor
        tags:
            - { name: 'my_chain' }
```

In this example, the `ChainProcessor` __my\_chain__ will receive the two `Processor` __p1__ and __p2__.

## What now ?
The input of the `Processor` if no constraint by a type hinting, so you can use really what you want to create your request.
The `ChainProcessor` can do what you want... if it contains `Processor` who can do it.
