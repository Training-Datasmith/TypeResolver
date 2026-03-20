<?php

/*
 * This file is part of phpDocumentor.
 *
 *  For the full copyright and license information, please view the LICENSE
 *  file that was distributed with this source code.
 *
 *  @link      http://phpdoc.org
 *
 */
declare (strict_types=1);
namespace Php_Documentor\Reflection\Pseudo_Types;

use function implode;
use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
use Php_Documentor\Reflection\Types\Integer;
/**
 * Value Object representing the `int-mask` type.
 *
 * @psalm-immutable
 */
final class Int_Mask extends Integer implements Pseudo_Type
{
    /** @var Type[] */
    private array $types;
    public function __construct(Type ...$types)
    {
        $this->types = $types;
    }
    /**
     * @return Type[]
     */
    public function get_types(): array
    {
        return $this->types;
    }
    public function underlying_type(): Type
    {
        return new Integer();
    }
    public function __toString(): string
    {
        return 'int-mask<' . implode(', ', $this->types) . '>';
    }
}