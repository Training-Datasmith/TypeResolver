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

use Php_Documentor\Reflection\Pseudo_Type;
use Php_Documentor\Reflection\Type;
/**
 * Value Object representing the `key-of` type.
 *
 * @psalm-immutable
 */
final class Key_Of extends Array_Key implements Pseudo_Type
{
    private \Php_Documentor\Reflection\Type $type;
    public function __construct(Type $type)
    {
        $this->type = $type;
    }
    public function get_type(): Type
    {
        return $this->type;
    }
    public function underlying_type(): Type
    {
        return new Array_Key();
    }
    public function __toString(): string
    {
        return 'key-of<' . $this->type . '>';
    }
}