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
use Php_Documentor\Reflection\Types\Integer;
use Php_Documentor\Reflection\Types\String_;
/**
 * Value Object representing the type `array-key`.
 *
 * @psalm-immutable
 */
class Array_Key extends Aggregated_Type implements Pseudo_Type
{
    public function __construct()
    {
        parent::__construct([new String_(), new Integer()], '|');
    }
    public function underlying_type(): Type
    {
        return new Compound([new String_(), new Integer()]);
    }
    public function __toString(): string
    {
        return 'array-key';
    }
}