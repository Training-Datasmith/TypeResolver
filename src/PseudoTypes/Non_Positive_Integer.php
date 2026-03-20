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
 * Value Object representing the type 'non-positive-int'.
 *
 * @psalm-immutable
 */
final class Non_Positive_Integer extends Integer implements Pseudo_Type
{
    public function underlying_type(): Type
    {
        return new Integer();
    }
    public function __toString(): string
    {
        return 'non-positive-int';
    }
}