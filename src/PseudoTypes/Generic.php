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

use function implode;
use Php_Documentor\Reflection\Fqsen;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Object_;
/**
 * Value Object representing a type with generics.
 *
 * @psalm-immutable
 */
final class Generic extends Object_
{
    /** @var Type[] */
    private array $types;
    /**
     * @param Type[] $types
     */
    public function __construct(?Fqsen $fqsen, array $types)
    {
        parent::__construct($fqsen);
        $this->types = $types;
    }
    /**
     * @return Type[]
     */
    public function get_types(): array
    {
        return $this->types;
    }
    public function __toString(): string
    {
        $object_type = (string) ($this->fqsen ?? 'object');
        return $object_type . '<' . implode(', ', $this->types) . '>';
    }
}