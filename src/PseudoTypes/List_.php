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
/**
 * Value Object representing the type 'list'.
 *
 * @psalm-immutable
 */
final class List_ extends Array_ implements Pseudo_Type
{
    public function underlying_type(): Type
    {
        return new Array_();
    }
    public function __construct(?Type $value_type = null)
    {
        parent::__construct($value_type, new Integer());
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        if ($this->value_type === null) {
            return 'list';
        }
        return 'list<' . $this->value_type . '>';
    }
}