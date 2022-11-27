<?php

namespace Core\Database\QueryBuilder\Interface;

interface IDialect
{
    public function build(): string;
}
