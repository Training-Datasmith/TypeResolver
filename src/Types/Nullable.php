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
namespace Php_Documentor\Reflection\Types;

use Php_Documentor\Reflection\Type;
/**
 * Value Object representing a nullable type. The real type is wrapped.
 *
 * @psalm-immutable
 */
final class Nullable implements Type
{
    /** @var Type The actual type that is wrapped */
    private \Php_Documentor\Reflection\Type $real_type;
    /**
     * Initialises this nullable type using the real type embedded
     */
    public function __construct(Type $real_type)
    {
        $this->real_type = $real_type;
    }
    /**
     * Provide access to the actual type directly, if needed.
     */
    public function get_actual_type(): Type
    {
        return $this->real_type;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        return '?' . $this->real_type->__toString();
    }
}