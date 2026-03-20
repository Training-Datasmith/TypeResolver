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
use Php_Documentor\Reflection\Types\Float_;
/** @psalm-immutable */
class Float_Value extends Float_ implements Pseudo_Type
{
    private float $value;
    public function __construct(float $value)
    {
        $this->value = $value;
    }
    public function get_value(): float
    {
        return $this->value;
    }
    public function underlying_type(): Type
    {
        return new Float_();
    }
    public function __toString(): string
    {
        return (string) $this->value;
    }
}