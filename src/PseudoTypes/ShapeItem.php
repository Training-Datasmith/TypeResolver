<?php

declare (strict_types=1);
namespace Php_Documentor\Reflection\Pseudo_Types;

use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Mixed_;
use function sprintf;
abstract class Shape_Item
{
    private ?string $key;
    private \Php_Documentor\Reflection\Type $value;
    private bool $optional;
    public function __construct(?string $key, ?Type $value, bool $optional)
    {
        $this->key = $key;
        $this->value = $value ?? new Mixed_();
        $this->optional = $optional;
    }
    public function get_key(): ?string
    {
        return $this->key;
    }
    public function get_value(): Type
    {
        return $this->value;
    }
    public function is_optional(): bool
    {
        return $this->optional;
    }
    public function __toString(): string
    {
        if ($this->key !== null && $this->key !== '') {
            return sprintf('%s%s: %s', $this->key, $this->optional ? '?' : '', (string) $this->value);
        }
        return (string) $this->value;
    }
}