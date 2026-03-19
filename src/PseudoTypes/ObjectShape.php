<?php

declare(strict_types=1);

namespace phpDocumentor\Reflection\PseudoTypes;

use function implode;

use phpDocumentor\Reflection\PseudoType;
use phpDocumentor\Reflection\Type;

use phpDocumentor\Reflection\Types\Object_;

/** @psalm-immutable */
final class ObjectShape extends Object_ implements PseudoType
{
    /** @var ObjectShapeItem[] */
    private array $items;

    public function __construct(ObjectShapeItem ...$items)
    {
        $this->items = $items;
    }

    /**
     * @return ObjectShapeItem[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function underlyingType(): Type
    {
        return new Object_();
    }

    public function __toString(): string
    {
        return 'object{' . implode(', ', $this->items) . '}';
    }
}
