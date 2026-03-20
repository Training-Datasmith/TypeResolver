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
use Php_Documentor\Reflection\Types\Integer;
use Php_Documentor\Reflection\Types\Mixed_;
/**
 * Value Object representing the type 'callable-array'.
 *
 * @psalm-immutable
 */
final class Callable_Array extends Array_ implements Pseudo_Type
{
    public function __construct()
    {
        parent::__construct(new Mixed_(), new Integer());
    }
    public function underlying_type(): Type
    {
        return new Array_(new Mixed_(), new Integer());
    }
    public function __toString(): string
    {
        return 'callable-array';
    }
}