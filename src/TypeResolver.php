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
namespace Php_Documentor\Reflection;

use function array_key_exists;
use function array_map;
use function array_reverse;
use function class_exists;
use function class_implements;
use Doctrine\Deprecations\Deprecation;
use function get_class;
use function in_array;
use InvalidArgumentException;
use Php_Documentor\Reflection\Pseudo_Types\Array_Key;
use Php_Documentor\Reflection\Pseudo_Types\Array_Shape;
use Php_Documentor\Reflection\Pseudo_Types\Array_Shape_Item;
use Php_Documentor\Reflection\Pseudo_Types\Callable_Array;
use Php_Documentor\Reflection\Pseudo_Types\Callable_String;
use Php_Documentor\Reflection\Pseudo_Types\Class_String;
use Php_Documentor\Reflection\Pseudo_Types\Closed_Resource;
use Php_Documentor\Reflection\Pseudo_Types\Conditional;
use Php_Documentor\Reflection\Pseudo_Types\Conditional_For_Parameter;
use Php_Documentor\Reflection\Pseudo_Types\Const_Expression;
use Php_Documentor\Reflection\Pseudo_Types\Enum_String;
use Php_Documentor\Reflection\Pseudo_Types\False_;
use Php_Documentor\Reflection\Pseudo_Types\Float_Value;
use Php_Documentor\Reflection\Pseudo_Types\Generic;
use Php_Documentor\Reflection\Pseudo_Types\Html_Escaped_String;
use Php_Documentor\Reflection\Pseudo_Types\Integer_Range;
use Php_Documentor\Reflection\Pseudo_Types\Integer_Value;
use Php_Documentor\Reflection\Pseudo_Types\Interface_String;
use Php_Documentor\Reflection\Pseudo_Types\Int_Mask;
use Php_Documentor\Reflection\Pseudo_Types\Int_Mask_Of;
use Php_Documentor\Reflection\Pseudo_Types\Key_Of;
use Php_Documentor\Reflection\Pseudo_Types\List_;
use Php_Documentor\Reflection\Pseudo_Types\List_Shape;
use Php_Documentor\Reflection\Pseudo_Types\List_Shape_Item;
use Php_Documentor\Reflection\Pseudo_Types\Literal_String;
use Php_Documentor\Reflection\Pseudo_Types\Lowercase_String;
use Php_Documentor\Reflection\Pseudo_Types\Negative_Integer;
use Php_Documentor\Reflection\Pseudo_Types\Never_Return;
use Php_Documentor\Reflection\Pseudo_Types\Never_Returns;
use Php_Documentor\Reflection\Pseudo_Types\Non_Empty_Array;
use Php_Documentor\Reflection\Pseudo_Types\Non_Empty_List;
use Php_Documentor\Reflection\Pseudo_Types\Non_Empty_Lowercase_String;
use Php_Documentor\Reflection\Pseudo_Types\Non_Empty_String;
use Php_Documentor\Reflection\Pseudo_Types\Non_Falsy_String;
use Php_Documentor\Reflection\Pseudo_Types\Non_Negative_Integer;
use Php_Documentor\Reflection\Pseudo_Types\Non_Positive_Integer;
use Php_Documentor\Reflection\Pseudo_Types\Non_Zero_Integer;
use Php_Documentor\Reflection\Pseudo_Types\No_Return;
use Php_Documentor\Reflection\Pseudo_Types\Numeric_;
use Php_Documentor\Reflection\Pseudo_Types\Numeric_String;
use Php_Documentor\Reflection\Pseudo_Types\Object_Shape;
use Php_Documentor\Reflection\Pseudo_Types\Object_Shape_Item;
use Php_Documentor\Reflection\Pseudo_Types\Offset_Access;
use Php_Documentor\Reflection\Pseudo_Types\Open_Resource;
use Php_Documentor\Reflection\Pseudo_Types\Positive_Integer;
use Php_Documentor\Reflection\Pseudo_Types\Private_Properties_Of;
use Php_Documentor\Reflection\Pseudo_Types\Properties_Of;
use Php_Documentor\Reflection\Pseudo_Types\Protected_Properties_Of;
use Php_Documentor\Reflection\Pseudo_Types\Public_Properties_Of;
use Php_Documentor\Reflection\Pseudo_Types\Scalar;
use Php_Documentor\Reflection\Pseudo_Types\String_Value;
use Php_Documentor\Reflection\Pseudo_Types\Trait_String;
use Php_Documentor\Reflection\Pseudo_Types\True_;
use Php_Documentor\Reflection\Pseudo_Types\Truthy_String;
use Php_Documentor\Reflection\Pseudo_Types\Value_Of;
use Php_Documentor\Reflection\Types\Aggregated_Type;
use Php_Documentor\Reflection\Types\Array_;
use Php_Documentor\Reflection\Types\Boolean;
use Php_Documentor\Reflection\Types\Callable_;
use Php_Documentor\Reflection\Types\Callable_Parameter;
use Php_Documentor\Reflection\Types\Compound;
use Php_Documentor\Reflection\Types\Context;
use Php_Documentor\Reflection\Types\Expression;
use Php_Documentor\Reflection\Types\Float_;
use Php_Documentor\Reflection\Types\Integer;
use Php_Documentor\Reflection\Types\Intersection;
use Php_Documentor\Reflection\Types\Iterable_;
use Php_Documentor\Reflection\Types\Mixed_;
use Php_Documentor\Reflection\Types\Never_;
use Php_Documentor\Reflection\Types\Null_;
use Php_Documentor\Reflection\Types\Nullable;
use Php_Documentor\Reflection\Types\Object_;
use Php_Documentor\Reflection\Types\Parent_;
use Php_Documentor\Reflection\Types\Resource_;
use Php_Documentor\Reflection\Types\Self_;
use Php_Documentor\Reflection\Types\Static_;
use Php_Documentor\Reflection\Types\String_;
use Php_Documentor\Reflection\Types\This;
use Php_Documentor\Reflection\Types\Void_;
use Php_Stan\Php_Doc_Parser\Ast\Const_Expr\Const_Expr_Float_Node;
use Php_Stan\Php_Doc_Parser\Ast\Const_Expr\Const_Expr_Integer_Node;
use Php_Stan\Php_Doc_Parser\Ast\Const_Expr\Const_Expr_String_Node;
use Php_Stan\Php_Doc_Parser\Ast\Const_Expr\Const_Fetch_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Array_Shape_Item_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Array_Shape_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Array_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Callable_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Callable_Type_Parameter_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Conditional_Type_For_Parameter_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Conditional_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Const_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Generic_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Identifier_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Intersection_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Nullable_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Object_Shape_Item_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Object_Shape_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Offset_Access_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\This_Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Type_Node;
use Php_Stan\Php_Doc_Parser\Ast\Type\Union_Type_Node;
use Php_Stan\Php_Doc_Parser\Lexer\Lexer;
use Php_Stan\Php_Doc_Parser\Parser\Const_Expr_Parser;
use Php_Stan\Php_Doc_Parser\Parser\Parser_Exception;
use Php_Stan\Php_Doc_Parser\Parser\Token_Iterator;
use Php_Stan\Php_Doc_Parser\Parser\Type_Parser;
use Php_Stan\Php_Doc_Parser\Parser_Config;
use RuntimeException;
use function sprintf;
use function strpos;
use function strtolower;
use function substr;
use function trim;
final class Type_Resolver
{
    /** @var string Definition of the NAMESPACE operator in PHP */
    private const OPERATOR_NAMESPACE = '\\';
    /**
     * @var array<string, string> List of recognized keywords and unto which Value Object they map
     * @psalm-var array<string, class-string<Type>>
     */
    private array $keywords = ['string' => String_::class, 'class-string' => Class_String::class, 'interface-string' => Interface_String::class, 'html-escaped-string' => Html_Escaped_String::class, 'lowercase-string' => Lowercase_String::class, 'non-empty-lowercase-string' => Non_Empty_Lowercase_String::class, 'non-empty-string' => Non_Empty_String::class, 'numeric-string' => Numeric_String::class, 'numeric' => Numeric_::class, 'trait-string' => Trait_String::class, 'enum-string' => Enum_String::class, 'int' => Integer::class, 'integer' => Integer::class, 'positive-int' => Positive_Integer::class, 'negative-int' => Negative_Integer::class, 'bool' => Boolean::class, 'boolean' => Boolean::class, 'real' => Float_::class, 'float' => Float_::class, 'double' => Float_::class, 'object' => Object_::class, 'mixed' => Mixed_::class, 'array' => Array_::class, 'callable-array' => Callable_Array::class, 'array-key' => Array_Key::class, 'non-empty-array' => Non_Empty_Array::class, 'resource' => Resource_::class, 'open-resource' => Open_Resource::class, 'closed-resource' => Closed_Resource::class, 'void' => Void_::class, 'null' => Null_::class, 'scalar' => Scalar::class, 'callback' => Callable_::class, 'callable' => Callable_::class, 'callable-string' => Callable_String::class, 'false' => False_::class, 'true' => True_::class, 'literal-string' => Literal_String::class, 'self' => Self_::class, '$this' => This::class, 'static' => Static_::class, 'parent' => Parent_::class, 'iterable' => Iterable_::class, 'never' => Never_::class, 'never-return' => Never_Return::class, 'never-returns' => Never_Returns::class, 'no-return' => No_Return::class, 'list' => List_::class, 'non-empty-list' => Non_Empty_List::class, 'non-falsy-string' => Non_Falsy_String::class, 'truthy-string' => Truthy_String::class, 'non-positive-int' => Non_Positive_Integer::class, 'non-negative-int' => Non_Negative_Integer::class, 'non-zero-int' => Non_Zero_Integer::class];
    /**
     * @psalm-readonly
     */
    private \Php_Documentor\Reflection\Fqsen_Resolver $fqsen_resolver;
    /**
     * @psalm-readonly
     */
    private \Php_Stan\Php_Doc_Parser\Parser\Type_Parser $type_parser;
    /**
     * @psalm-readonly
     */
    private \Php_Stan\Php_Doc_Parser\Lexer\Lexer $lexer;
    /**
     * Initializes this TypeResolver with the means to create and resolve Fqsen objects.
     */
    public function __construct(?Fqsen_Resolver $fqsen_resolver = null)
    {
        $this->fqsen_resolver = $fqsen_resolver ?: new Fqsen_Resolver();
        $this->type_parser = new Type_Parser(new Parser_Config([]), new Const_Expr_Parser(new Parser_Config([])));
        $this->lexer = new Lexer(new Parser_Config([]));
    }
    /**
     * Analyzes the given type and returns the FQCN variant.
     *
     * When a type is provided this method checks whether it is not a keyword or
     * Fully Qualified Class Name. If so it will use the given namespace and
     * aliases to expand the type to a FQCN representation.
     *
     * This method only works as expected if the namespace and aliases are set;
     * no dynamic reflection is being performed here.
     *
     * @uses Context::getNamespace()        to determine with what to prefix the type name.
     * @uses Context::getNamespaceAliases() to check whether the first part of the relative type name should not be
     * replaced with another namespace.
     *
     * @param string $type The relative or absolute type.
     */
    public function resolve(string $type, ?Context $context = null): Type
    {
        $type = trim($type);
        if (!$type) {
            throw new InvalidArgumentException('Attempted to resolve "' . $type . '" but it appears to be empty');
        }
        if ($context === null) {
            $context = new Context('');
        }
        $tokens = $this->lexer->tokenize($type);
        $token_iterator = new Token_Iterator($tokens);
        $ast = $this->parse($token_iterator);
        $type = $this->create_type($ast, $context);
        if ($token_iterator->is_current_token_type(Lexer::TOKEN_UNION) || $token_iterator->is_current_token_type(Lexer::TOKEN_INTERSECTION)) {
            Deprecation::trigger('phpdocumentor/type-resolver', 'https://github.com/phpDocumentor/TypeResolver/issues/184', 'Legacy nullable type detected, please update your code as
                you are using nullable types in a docblock. support is removed in v2.0.0');
        }
        return $type;
    }
    public function create_type(?Type_Node $type, Context $context): Type
    {
        if ($type === null) {
            return new Mixed_();
        }
        switch (get_class($type)) {
            case Array_Type_Node::class:
                return new Array_($this->create_type($type->type, $context));
            case Array_Shape_Node::class:
                switch ($type->kind) {
                    case Array_Shape_Node::KIND_ARRAY:
                        return new Array_Shape(...array_map(fn(Array_Shape_Item_Node $item): Array_Shape_Item => new Array_Shape_Item($item->key_name !== null ? (string) $item->key_name : null, $this->create_type($item->value_type, $context), $item->optional), $type->items));
                    case Array_Shape_Node::KIND_LIST:
                        return new List_Shape(...array_map(fn(Array_Shape_Item_Node $item): List_Shape_Item => new List_Shape_Item(null, $this->create_type($item->value_type, $context), $item->optional), $type->items));
                    default:
                        throw new RuntimeException('Unsupported array shape kind');
                }
            // no break
            case Object_Shape_Node::class:
                return new Object_Shape(...array_map(fn(Object_Shape_Item_Node $item): Object_Shape_Item => new Object_Shape_Item((string) $item->key_name, $this->create_type($item->value_type, $context), $item->optional), $type->items));
            case Callable_Type_Node::class:
                return $this->create_from_callable($type, $context);
            case Const_Type_Node::class:
                return $this->create_from_const($type, $context);
            case Generic_Type_Node::class:
                return $this->create_from_generic($type, $context);
            case Identifier_Type_Node::class:
                return $this->resolve_single_type($type->name, $context);
            case Intersection_Type_Node::class:
                return new Intersection(array_map(function (Type_Node $nested_type) use ($context): Type {
                    $type = $this->create_type($nested_type, $context);
                    if ($type instanceof Aggregated_Type) {
                        return new Expression($type);
                    }
                    return $type;
                }, $type->types));
            case Nullable_Type_Node::class:
                $nested_type = $this->create_type($type->type, $context);
                return new Nullable($nested_type);
            case Union_Type_Node::class:
                return new Compound(array_map(function (Type_Node $nested_type) use ($context): Type {
                    $type = $this->create_type($nested_type, $context);
                    if ($type instanceof Aggregated_Type) {
                        return new Expression($type);
                    }
                    return $type;
                }, $type->types));
            case This_Type_Node::class:
                return new This();
            case Conditional_Type_Node::class:
                return new Conditional($type->negated, $this->create_type($type->subject_type, $context), $this->create_type($type->target_type, $context), $this->create_type($type->if, $context), $this->create_type($type->else, $context));
            case Conditional_Type_For_Parameter_Node::class:
                return new Conditional_For_Parameter($type->negated, substr($type->parameter_name, 1), $this->create_type($type->target_type, $context), $this->create_type($type->if, $context), $this->create_type($type->else, $context));
            case Offset_Access_Type_Node::class:
                return new Offset_Access($this->create_type($type->type, $context), $this->create_type($type->offset, $context));
            default:
                return new Mixed_();
        }
    }
    private function create_from_generic(Generic_Type_Node $type, Context $context): Type
    {
        switch (strtolower($type->type->name)) {
            case 'array':
                $generic_types = array_reverse($this->create_types_by_type_nodes($type->generic_types, $context));
                return new Array_(...$generic_types);
            case 'non-empty-array':
                $generic_types = array_reverse($this->create_types_by_type_nodes($type->generic_types, $context));
                return new Non_Empty_Array(...$generic_types);
            case 'class-string':
                return new Class_String($this->create_type($type->generic_types[0], $context));
            case 'interface-string':
                return new Interface_String($this->create_type($type->generic_types[0], $context));
            case 'trait-string':
                return new Trait_String($this->create_type($type->generic_types[0], $context));
            case 'enum-string':
                return new Enum_String($this->create_type($type->generic_types[0], $context));
            case 'list':
                return new List_($this->create_type($type->generic_types[0], $context));
            case 'non-empty-list':
                return new Non_Empty_List($this->create_type($type->generic_types[0], $context));
            case 'int':
                if (isset($type->generic_types[1]) === false) {
                    throw new RuntimeException('int<min,max> has not the correct format');
                }
                return new Integer_Range((string) $type->generic_types[0], (string) $type->generic_types[1]);
            case 'iterable':
                return new Iterable_(...array_reverse($this->create_types_by_type_nodes($type->generic_types, $context)));
            case 'key-of':
                return new Key_Of($this->create_type($type->generic_types[0], $context));
            case 'value-of':
                return new Value_Of($this->create_type($type->generic_types[0], $context));
            case 'properties-of':
                return new Properties_Of($this->create_type($type->generic_types[0], $context));
            case 'public-properties-of':
                return new Public_Properties_Of($this->create_type($type->generic_types[0], $context));
            case 'protected-properties-of':
                return new Protected_Properties_Of($this->create_type($type->generic_types[0], $context));
            case 'private-properties-of':
                return new Private_Properties_Of($this->create_type($type->generic_types[0], $context));
            case 'int-mask':
                return new Int_Mask(...$this->create_types_by_type_nodes($type->generic_types, $context));
            case 'int-mask-of':
                return new Int_Mask_Of($this->create_type($type->generic_types[0], $context));
            case 'static':
                return new Static_(...$this->create_types_by_type_nodes($type->generic_types, $context));
            case 'self':
                return new Self_(...$this->create_types_by_type_nodes($type->generic_types, $context));
            default:
                $main_type = $this->create_type($type->type, $context);
                if ($main_type instanceof Object_ === false) {
                    throw new RuntimeException(sprintf('%s is an unsupported generic', (string) $main_type));
                }
                $types = $this->create_types_by_type_nodes($type->generic_types, $context);
                return new Generic($main_type->get_fqsen(), $types);
        }
    }
    private function create_from_callable(Callable_Type_Node $type, Context $context): Callable_
    {
        return new Callable_((string) $type->identifier, array_map(fn(Callable_Type_Parameter_Node $param): Callable_Parameter => new Callable_Parameter($this->create_type($param->type, $context), $param->parameter_name !== '' ? trim($param->parameter_name, '$') : null, $param->is_reference, $param->is_variadic, $param->is_optional), $type->parameters), $this->create_type($type->return_type, $context));
    }
    private function create_from_const(Const_Type_Node $type, Context $context): Type
    {
        switch (true) {
            case $type->const_expr instanceof Const_Expr_Integer_Node:
                return new Integer_Value((int) $type->const_expr->value);
            case $type->const_expr instanceof Const_Expr_Float_Node:
                return new Float_Value((float) $type->const_expr->value);
            case $type->const_expr instanceof Const_Expr_String_Node:
                return new String_Value($type->const_expr->value);
            case $type->const_expr instanceof Const_Fetch_Node:
                return new Const_Expression($this->resolve($type->const_expr->class_name, $context), $type->const_expr->name);
            default:
                throw new RuntimeException(sprintf('Unsupported constant type %s', get_class($type)));
        }
    }
    /**
     * resolve the given type into a type object
     *
     * @param string $type the type string, representing a single type
     *
     * @return Type|Array_|Object_
     *
     * @psalm-mutation-free
     */
    private function resolve_single_type(string $type, Context $context): object
    {
        switch (true) {
            case $this->is_keyword($type):
                return $this->resolve_keyword($type);
            case $this->is_fqsen($type):
                return $this->resolve_typed_object($type);
            case $this->is_partial_structural_element_name($type):
                return $this->resolve_typed_object($type, $context);
            // @codeCoverageIgnoreStart
            default:
                // I haven't got the foggiest how the logic would come here but added this as a defense.
                throw new RuntimeException('Unable to resolve type "' . $type . '", there is no known method to resolve it');
        }
        // @codeCoverageIgnoreEnd
    }
    /**
     * Adds a keyword to the list of Keywords and associates it with a specific Value Object.
     *
     * @psalm-param class-string<Type> $typeClassName
     */
    public function add_keyword(string $keyword, string $type_class_name): void
    {
        if (!class_exists($type_class_name)) {
            throw new InvalidArgumentException('The Value Object that needs to be created with a keyword "' . $keyword . '" must be an existing class' . ' but we could not find the class ' . $type_class_name);
        }
        $interfaces = class_implements($type_class_name);
        if ($interfaces === false) {
            throw new InvalidArgumentException('The Value Object that needs to be created with a keyword "' . $keyword . '" must be an existing class' . ' but we could not find the class ' . $type_class_name);
        }
        if (!in_array(Type::class, $interfaces, true)) {
            throw new InvalidArgumentException('The class "' . $type_class_name . '" must implement the interface "phpDocumentor\Reflection\Type"');
        }
        $this->keywords[$keyword] = $type_class_name;
    }
    /**
     * Detects whether the given type represents a PHPDoc keyword.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @psalm-mutation-free
     */
    private function is_keyword(string $type): bool
    {
        return array_key_exists(strtolower($type), $this->keywords);
    }
    /**
     * Detects whether the given type represents a relative structural element name.
     *
     * @param string $type A relative or absolute type as defined in the phpDocumentor documentation.
     *
     * @psalm-mutation-free
     */
    private function is_partial_structural_element_name(string $type): bool
    {
        return isset($type[0]) && $type[0] !== self::OPERATOR_NAMESPACE && !$this->is_keyword($type);
    }
    /**
     * Tests whether the given type is a Fully Qualified Structural Element Name.
     *
     * @psalm-mutation-free
     */
    private function is_fqsen(string $type): bool
    {
        return strpos($type, self::OPERATOR_NAMESPACE) === 0;
    }
    /**
     * Resolves the given keyword (such as `string`) into a Type object representing that keyword.
     *
     * @psalm-mutation-free
     */
    private function resolve_keyword(string $type): Type
    {
        $class_name = $this->keywords[strtolower($type)];
        return new $class_name();
    }
    /**
     * Resolves the given FQSEN string into an FQSEN object.
     *
     * @psalm-mutation-free
     */
    private function resolve_typed_object(string $type, ?Context $context = null): Object_
    {
        return new Object_($this->fqsen_resolver->resolve($type, $context));
    }
    private function parse(Token_Iterator $token_iterator): Type_Node
    {
        try {
            $ast = $this->type_parser->parse($token_iterator);
        } catch (Parser_Exception $e) {
            throw new RuntimeException($e->get_message(), 0, $e);
        }
        return $ast;
    }
    /**
     * @param TypeNode[] $nodes
     *
     * @return Type[]
     */
    private function create_types_by_type_nodes(array $nodes, Context $context): array
    {
        return array_map(fn(Type_Node $node): Type => $this->create_type($node, $context), $nodes);
    }
}