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
use Php_Documentor\Reflection\Types\Integer;
/**
 * Value Object representing the `int-mask-of` type.
 *
 * @psalm-immutable
 */
final class Int_Mask_Of extends Integer implements Pseudo_Type
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
        return new Integer();
    }
    public function __toString(): string
    {
        return 'int-mask-of<' . $this->type . '>';
    }
}