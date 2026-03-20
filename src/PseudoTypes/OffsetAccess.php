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

use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Callable_;
use Php_Documentor\Reflection\Types\Mixed_;
use Php_Documentor\Reflection\Types\Nullable;
/**
 * Value Object representing the offset access type.
 *
 * @psalm-immutable
 */
final class Offset_Access extends Mixed_ implements Pseudo_Type
{
    private \Php_Documentor\Reflection\Type $type;
    private \Php_Documentor\Reflection\Type $offset;
    public function __construct(Type $type, Type $offset)
    {
        $this->type = $type;
        $this->offset = $offset;
    }
    public function get_type(): Type
    {
        return $this->type;
    }
    public function get_offset(): Type
    {
        return $this->offset;
    }
    public function underlying_type(): Type
    {
        return new Mixed_();
    }
    public function __toString(): string
    {
        if ($this->type instanceof Callable_ || $this->type instanceof Const_Expression || $this->type instanceof Nullable) {
            return '(' . $this->type . ')[' . $this->offset . ']';
        }
        return $this->type . '[' . $this->offset . ']';
    }
}