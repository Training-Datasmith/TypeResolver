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
use Php_Documentor\Reflection\Types\Integer;
/**
 * Value Object representing the type 'int'.
 *
 * @psalm-immutable
 */
final class Integer_Range extends Integer implements Pseudo_Type
{
    private string $min_value;
    private string $max_value;
    public function __construct(string $min_value, string $max_value)
    {
        $this->min_value = $min_value;
        $this->max_value = $max_value;
    }
    public function underlying_type(): Type
    {
        return new Integer();
    }
    public function get_min_value(): string
    {
        return $this->min_value;
    }
    public function get_max_value(): string
    {
        return $this->max_value;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return 'int<' . $this->min_value . ', ' . $this->max_value . '>';
    }
}