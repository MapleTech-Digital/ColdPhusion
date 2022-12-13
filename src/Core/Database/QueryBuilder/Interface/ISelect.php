<?php

namespace Core\Database\QueryBuilder\Interface;

interface ISelect
{
    public function setReflectors(array $reflectors): self;
}
