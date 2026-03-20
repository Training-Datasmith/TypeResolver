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
use Php_Documentor\Reflection\Types\Array_;
use Php_Documentor\Reflection\Types\Mixed_;
use Php_Documentor\Reflection\Types\String_;
/**
 * Value Object representing the `properties-of` type.
 *
 * @psalm-immutable
 */
class Properties_Of extends Array_ implements Pseudo_Type
{
    protected \Php_Documentor\Reflection\Type $type;
    public function __construct(Type $type)
    {
        parent::__construct(new Mixed_(), new String_());
        $this->type = $type;
    }
    public function get_type(): Type
    {
        return $this->type;
    }
    public function underlying_type(): Type
    {
        return new Array_(new Mixed_(), new String_());
    }
    public function __toString(): string
    {
        return 'properties-of<' . $this->type . '>';
    }
}