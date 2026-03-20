<?php

/*
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @link      http://phpdoc.org
 *
 */
declare (strict_types=1);
namespace Php_Documentor\Reflection\Pseudo_Types;

use function implode;
use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Array_;
use Php_Documentor\Reflection\Types\Mixed_;
/** @psalm-immutable */
class Array_Shape extends Array_ implements Pseudo_Type
{
    /** @var ArrayShapeItem[] */
    private array $items;
    public function __construct(Array_Shape_Item ...$items)
    {
        parent::__construct(new Mixed_(), new Array_Key());
        $this->items = $items;
    }
    /**
     * @return ArrayShapeItem[]
     */
    public function get_items(): array
    {
        return $this->items;
    }
    public function underlying_type(): Type
    {
        return new Array_(new Mixed_(), new Array_Key());
    }
    public function __toString(): string
    {
        return 'array{' . implode(', ', $this->items) . '}';
    }
}