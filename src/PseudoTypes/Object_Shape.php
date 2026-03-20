<?php

declare (strict_types=1);
namespace Php_Documentor\Reflection\Pseudo_Types;

use function implode;
use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Object_;
/** @psalm-immutable */
final class Object_Shape extends Object_ implements Pseudo_Type
{
    /** @var ObjectShapeItem[] */
    private array $items;
    public function __construct(Object_Shape_Item ...$items)
    {
        $this->items = $items;
    }
    /**
     * @return ObjectShapeItem[]
     */
    public function get_items(): array
    {
        return $this->items;
    }
    public function underlying_type(): Type
    {
        return new Object_();
    }
    public function __toString(): string
    {
        return 'object{' . implode(', ', $this->items) . '}';
    }
}