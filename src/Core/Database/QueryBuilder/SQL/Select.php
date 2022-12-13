<?php

namespace Core\Database\QueryBuilder\SQL;

use Core\Database\QueryBuilder\Interface\ISelect;

class Select implements ISelect
{
    /** @var array<Reflector>  */
    private array $reflectors;

    public function setReflectors(array $reflectors): self
    {
        $this->reflectors = $reflectors;
    }
}
