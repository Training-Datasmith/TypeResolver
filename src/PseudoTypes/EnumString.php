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
use Php_Documentor\Reflection\Types\String_;
/**
 * Value Object representing the type 'enum-string'.
 *
 * @psalm-immutable
 */
final class Enum_String extends String_ implements Pseudo_Type
{
    private ?\Php_Documentor\Reflection\Type $generic_type;
    public function __construct(?Type $generic_type = null)
    {
        $this->generic_type = $generic_type;
    }
    public function underlying_type(): Type
    {
        return new String_();
    }
    public function get_generic_type(): ?Type
    {
        return $this->generic_type;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        if ($this->generic_type === null) {
            return 'enum-string';
        }
        return 'enum-string<' . $this->generic_type . '>';
    }
}