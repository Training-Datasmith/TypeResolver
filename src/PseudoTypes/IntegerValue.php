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
use Php_Documentor\Reflection\Types\Integer;
/** @psalm-immutable */
final class Integer_Value extends Integer implements Pseudo_Type
{
    private int $value;
    public function __construct(int $value)
    {
        $this->value = $value;
    }
    public function get_value(): int
    {
        return $this->value;
    }
    public function underlying_type(): Type
    {
        return new Integer();
    }
    public function __toString(): string
    {
        return (string) $this->value;
    }
}