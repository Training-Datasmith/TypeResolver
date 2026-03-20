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
/**
 * Value Object representing the conditional type.
 *
 * @psalm-immutable
 */
final class Conditional extends Mixed_ implements Pseudo_Type
{
    private bool $negated;
    private \Php_Documentor\Reflection\Type $subject_type;
    private \Php_Documentor\Reflection\Type $target_type;
    private \Php_Documentor\Reflection\Type $if;
    private \Php_Documentor\Reflection\Type $else;
    public function __construct(bool $negated, Type $subject_type, Type $target_type, Type $if, Type $else)
    {
        $this->negated = $negated;
        $this->subject_type = $subject_type;
        $this->target_type = $target_type;
        $this->if = $if;
        $this->else = $else;
    }
    public function is_negated(): bool
    {
        return $this->negated;
    }
    public function get_subject_type(): Type
    {
        return $this->subject_type;
    }
    public function get_target_type(): Type
    {
        return $this->target_type;
    }
    public function get_if(): Type
    {
        return $this->if;
    }
    public function get_else(): Type
    {
        return $this->else;
    }
    public function underlying_type(): Type
    {
        return new Mixed_();
    }
    public function __toString(): string
    {
        return sprintf('(%s %s %s ? %s : %s)', (string) $this->subject_type, $this->negated ? 'is not' : 'is', (string) $this->target_type, (string) $this->if, (string) $this->else);
    }
}