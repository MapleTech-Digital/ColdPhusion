<?php

namespace Core\Database\QueryBuilder\Dialect;

use Core\Database\QueryBuilder\Interface\IDialect;

class MySQL implements IDialect
{
    public function build(): string
    {
        return 'SELECT 1 = 1';
    }
}
