<?php

/**
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @link      http://phpdoc.org
 */
declare (strict_types=1);
namespace Php_Documentor\Reflection\Types;

use Php_Documentor\Reflection\Type;
use function trim;
/**
 * Value Object representing a Callable parameters.
 *
 * @psalm-immutable
 */
final class Callable_Parameter
{
    private \Php_Documentor\Reflection\Type $type;
    private bool $is_reference;
    private bool $is_variadic;
    private bool $is_optional;
    private ?string $name;
    public function __construct(Type $type, ?string $name = null, bool $is_reference = false, bool $is_variadic = false, bool $is_optional = false)
    {
        $this->type = $type;
        $this->is_reference = $is_reference;
        $this->is_variadic = $is_variadic;
        $this->is_optional = $is_optional;
        $this->name = $name;
    }
    public function get_name(): ?string
    {
        return $this->name;
    }
    public function get_type(): Type
    {
        return $this->type;
    }
    public function is_reference(): bool
    {
        return $this->is_reference;
    }
    public function is_variadic(): bool
    {
        return $this->is_variadic;
    }
    public function is_optional(): bool
    {
        return $this->is_optional;
    }
    public function __toString(): string
    {
        $reference = $this->is_reference ? '&' : '';
        $variadic = $this->is_variadic ? '...' : '';
        $optional = $this->is_optional ? '=' : '';
        $name = $this->name !== null ? '$' . $this->name : '';
        return trim($this->type . ' ' . $reference . $variadic . $name . $optional);
    }
}