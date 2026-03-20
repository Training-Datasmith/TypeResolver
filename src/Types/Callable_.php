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

use function implode;
use Php_Documentor\Reflection\Type;
/**
 * Value Object representing a Callable type.
 *
 * @psalm-immutable
 */
final class Callable_ implements Type
{
    private string $identifier;
    private ?\Php_Documentor\Reflection\Type $return_type;
    /** @var CallableParameter[] */
    private array $parameters;
    /**
     * @param CallableParameter[] $parameters
     */
    public function __construct(string $identifier = 'callable', array $parameters = [], ?Type $return_type = null)
    {
        $this->identifier = $identifier;
        $this->parameters = $parameters;
        $this->return_type = $return_type;
    }
    public function get_identifier(): string
    {
        return $this->identifier;
    }
    /** @return CallableParameter[] */
    public function get_parameters(): array
    {
        return $this->parameters;
    }
    public function get_return_type(): ?Type
    {
        return $this->return_type;
    }
    /**
     * Returns a rendered output of the Type as it would be used in a DocBlock.
     */
    public function __toString(): string
    {
        if (!$this->parameters && $this->return_type === null) {
            return $this->identifier;
        }
        if ($this->return_type instanceof self) {
            $return_type = '(' . $this->return_type . ')';
        } else {
            $return_type = (string) $this->return_type;
        }
        return $this->identifier . '(' . implode(', ', $this->parameters) . '): ' . $return_type;
    }
}