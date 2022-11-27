<?php

namespace Core\Database\QueryBuilder\SQL;

class Reflector
{
    public string $reflector = '*';
    public ?string $alias;

    /**
     * @param string $reflector
     * @param string|null $alias
     */
    public function __construct(string $reflector = '*', ?string $alias = null)
    {
        $this->reflector = $reflector;
        $this->alias = $alias;
    }


}
