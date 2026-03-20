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
use Php_Documentor\Reflection\Types\Boolean;
use Php_Documentor\Reflection\Types\Compound;
use Php_Documentor\Reflection\Types\Float_;
use Php_Documentor\Reflection\Types\Integer;
use Php_Documentor\Reflection\Types\String_;
/**
 * Value Object representing the 'scalar' pseudo-type, which is either a string, integer, float or boolean.
 *
 * @psalm-immutable
 */
final class Scalar implements Pseudo_Type
{
    public function underlying_type(): Type
    {
        return new Compound([new String_(), new Integer(), new Float_(), new Boolean()]);
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return 'scalar';
    }
}