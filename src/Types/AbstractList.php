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

use Php_Documentor\Reflection\Type;
/**
 * Represents a list of values. This is an abstract class for Array_ and List_.
 *
 * @psalm-immutable
 */
abstract class Abstract_List implements Type
{
    protected ?\Php_Documentor\Reflection\Type $value_type;
    protected ?\Php_Documentor\Reflection\Type $key_type;
    protected \Php_Documentor\Reflection\Types\Compound $default_key_type;
    protected \Php_Documentor\Reflection\Types\Mixed_ $default_value_type;
    /**
     * Initializes this representation of an array with the given Type.
     */
    public function __construct(?Type $value_type = null, ?Type $key_type = null)
    {
        $this->default_value_type = new Mixed_();
        $this->value_type = $value_type;
        $this->default_key_type = new Compound([new String_(), new Integer()]);
        $this->key_type = $key_type;
    }
    public function get_original_key_type(): ?Type
    {
        return $this->key_type;
    }
    public function get_original_value_type(): ?Type
    {
        return $this->value_type;
    }
    /**
     * Returns the type for the keys of this array.
     */
    public function get_key_type(): Type
    {
        return $this->key_type ?? $this->default_key_type;
    }
    /**
     * Returns the type for the values of this array.
     */
    public function get_value_type(): Type
    {
        return $this->value_type ?? $this->default_value_type;
    }
}