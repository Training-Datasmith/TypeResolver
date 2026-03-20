<?php

declare (strict_types=1);
/**
 * This file is part of phpDocumentor.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @link      http://phpdoc.org
 */
namespace Php_Documentor\Reflection\Types;

use Php_Documentor\Reflection\Type;
/**
 * Represents an expression type as described in the PSR-5, the PHPDoc Standard.
 *
 * @psalm-immutable
 */
final class Expression implements Type
{
    protected \Php_Documentor\Reflection\Type $value_type;
    /**
     * Initializes this representation of an array with the given Type.
     */
    public function __construct(Type $value_type)
    {
        $this->value_type = $value_type;
    }
    /**
     * Returns the value for the keys of this array.
     */
    public function get_value_type(): Type
    {
        return $this->value_type;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return '(' . $this->value_type . ')';
    }
}