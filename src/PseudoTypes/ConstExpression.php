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
use Php_Documentor\Reflection\Types\Mixed_;
use function sprintf;
/** @psalm-immutable */
final class Const_Expression extends Mixed_ implements Pseudo_Type
{
    private \Php_Documentor\Reflection\Type $owner;
    private string $expression;
    public function __construct(Type $owner, string $expression)
    {
        $this->owner = $owner;
        $this->expression = $expression;
    }
    public function get_owner(): Type
    {
        return $this->owner;
    }
    public function get_expression(): string
    {
        return $this->expression;
    }
    public function underlying_type(): Type
    {
        return new Mixed_();
    }
    public function __toString(): string
    {
        return sprintf('%s::%s', (string) $this->owner, $this->expression);
    }
}