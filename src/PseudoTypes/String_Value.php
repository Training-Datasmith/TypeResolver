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
use Php_Documentor\Reflection\Types\String_;
use function sprintf;
/** @psalm-immutable */
class String_Value extends String_ implements Pseudo_Type
{
    private string $value;
    public function __construct(string $value)
    {
        $this->value = $value;
    }
    public function get_value(): string
    {
        return $this->value;
    }
    public function underlying_type(): Type
    {
        return new String_();
    }
    public function __toString(): string
    {
        return sprintf('"%s"', $this->value);
    }
}