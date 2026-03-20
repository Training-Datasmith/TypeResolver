<?php

declare (strict_types=1);
namespace Php_Documentor\Reflection\Pseudo_Types;

use function implode;
/** @psalm-immutable */
final class List_Shape extends Array_Shape
{
    public function __toString(): string
    {
        return 'list{' . implode(', ', $this->get_items()) . '}';
    }
}