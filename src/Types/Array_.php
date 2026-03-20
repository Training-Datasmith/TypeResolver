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

use function preg_match;
use function substr;
/**
 * Represents an array type as described in the PSR-5, the PHPDoc Standard.
 *
 * An array can be represented in two forms:
 *
 * 1. Untyped (`array`), where the key and value type is unknown and hence classified as 'Mixed_'.
 * 2. Types (`string[]`), where the value type is provided by preceding an opening and closing square bracket with a
 *    type name.
 *
 * @psalm-immutable
 */
class Array_ extends Abstract_List
{
    public function __toString(): string
    {
        if ($this->value_type === null) {
            return 'array';
        }
        $value_type_string = (string) $this->value_type;
        if ($this->key_type) {
            return 'array<' . $this->key_type . ', ' . $value_type_string . '>';
        }
        if (!preg_match('/[^\w\\\\]/', $value_type_string) || substr($value_type_string, -2, 2) === '[]') {
            return $value_type_string . '[]';
        }
        return 'array<' . $value_type_string . '>';
    }
}