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
namespace Php_Documentor\Reflection\Pseudo_Types;

use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Array_;
/**
 * Value Object representing the type 'non-empty-array'.
 *
 * @psalm-immutable
 */
final class Non_Empty_Array extends Array_ implements Pseudo_Type
{
    public function underlying_type(): Type
    {
        return new Array_($this->value_type, $this->key_type);
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        if ($this->value_type === null) {
            return 'non-empty-array';
        }
        if ($this->key_type) {
            return 'non-empty-array<' . $this->key_type . ', ' . $this->value_type . '>';
        }
        return 'non-empty-array<' . $this->value_type . '>';
    }
}