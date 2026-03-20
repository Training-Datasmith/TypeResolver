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
 * Value Object representing the type 'non-negative-int'.
 *
 * @psalm-immutable
 */
final class Non_Negative_Integer extends Integer implements Pseudo_Type
{
    public function underlying_type(): Type
    {
        return new Integer();
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return 'non-negative-int';
    }
}