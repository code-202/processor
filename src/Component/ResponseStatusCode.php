<?php

namespace Code202\Processor\Component;

enum ResponseStatusCode: int
{
    case OK = 200;
    case INTERNAL_ERROR = 500;
    case NOT_SUPPORTED = 505;
}
