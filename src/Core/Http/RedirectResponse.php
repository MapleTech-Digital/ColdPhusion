<?php

namespace Core\Http;

class RedirectResponse extends Response
{
    public $code = 301;
    public string $target = '';


}
