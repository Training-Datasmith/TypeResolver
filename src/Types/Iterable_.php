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

/**
 * Value Object representing iterable type
 *
 * @psalm-immutable
 */
final class Iterable_ extends Abstract_List
{
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        if ($this->value_type === null) {
            return 'iterable';
        }
        if ($this->key_type) {
            return 'iterable<' . $this->key_type . ', ' . $this->value_type . '>';
        }
        return 'iterable<' . $this->value_type . '>';
    }
}