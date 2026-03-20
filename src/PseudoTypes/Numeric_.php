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
use Php_Documentor\Reflection\Types\Aggregated_Type;
use Php_Documentor\Reflection\Types\Compound;
use Php_Documentor\Reflection\Types\Float_;
use Php_Documentor\Reflection\Types\Integer;
/**
 * Value Object representing the 'numeric' pseudo-type, which is either a numeric-string, integer or float.
 *
 * @psalm-immutable
 */
final class Numeric_ extends Aggregated_Type implements Pseudo_Type
{
    public function __construct()
    {
        Aggregated_Type::__construct([new Numeric_String(), new Integer(), new Float_()], '|');
    }
    public function underlying_type(): Type
    {
        return new Compound([new Numeric_String(), new Integer(), new Float_()]);
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return 'numeric';
    }
}