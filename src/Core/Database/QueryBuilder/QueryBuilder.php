<?php

namespace Core\Database\QueryBuilder;

use Core\Database\QueryBuilder\Dialect\MySQL;
use Core\Database\QueryBuilder\Interface\IDialect;
use Core\Database\QueryBuilder\Interface\ISelect;
use Core\Database\QueryBuilder\SQL\Select;

class QueryBuilder
{
    private IDialect $m_Dialect;
    private ISelect $m_Select;

    /**
     * @param ?IDialect $dialect
     */
    public function __construct(IDialect $dialect = null)
    {
        $this->m_Dialect = $dialect ?: new MySQL();
    }


    public function select(array $reflectors = []): self
    {
        if(empty($reflectors)) {
            $reflectors[] = '*';
        }

        $this->m_Select = new Select();

        $this->m_Select->reflectors = $reflectors;

        return $this;
    }

    public function from($table, $alias): self
    {

        return $this;
    }

    public function where(string $condition): self
    {

        return $this;
    }

    public function andWhere(string $condition): self
    {

        return $this;
    }

    public function orderBy(string $reflector, string $sort): self
    {

        return $this;
    }


    public function build(): string
    {
        return $this->m_Dialect->build();
    }

    public function __toString(): string
    {
        return $this->build();
    }

    public static function Create(): self
    {
        $qb = new self();

        return $qb;
    }
}
