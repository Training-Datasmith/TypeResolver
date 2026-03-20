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

use ArrayIterator;
use function define;
use function defined;
use function file_exists;
use function file_get_contents;
use function get_class;
use function in_array;
use InvalidArgumentException;
use function is_string;
use ReflectionClass;
use Reflection_Class_Constant;
use ReflectionMethod;
use ReflectionParameter;
use ReflectionProperty;
use Reflector;
use RuntimeException;
use function strrpos;
use function substr;
use const T_AS;
use const T_CLASS;
use const T_CURLY_OPEN;
use const T_DOLLAR_OPEN_CURLY_BRACES;
use const T_NAME_FULLY_QUALIFIED;
use const T_NAME_QUALIFIED;
use const T_NAMESPACE;
use const T_NS_SEPARATOR;
use const T_STRING;
use const T_TRAIT;
use const T_USE;
use function token_get_all;
use function trim;
use UnexpectedValueException;
if (!defined('T_NAME_QUALIFIED')) {
    define('T_NAME_QUALIFIED', 10001);
}
if (!defined('T_NAME_FULLY_QUALIFIED')) {
    define('T_NAME_FULLY_QUALIFIED', 10002);
}
/**
 * Convenience class to create a Context for DocBlocks when not using the Reflection Component of phpDocumentor.
 *
 * For a DocBlock to be able to resolve types that use partial namespace names or rely on namespace imports we need to
 * provide a bit of context so that the DocBlock can read that and based on it decide how to resolve the types to
 * Fully Qualified names.
 *
 * @see Context for more information.
 */
final class Context_Factory
{
    /** The literal used at the end of a use statement. */
    private const T_LITERAL_END_OF_USE = ';';
    /** The literal used between sets of use statements */
    private const T_LITERAL_USE_SEPARATOR = ',';
    /**
     * Build a Context given a Class Reflection.
     *
     * @see Context for more information on Contexts.
     */
    public function create_from_reflector(Reflector $reflector): Context
    {
        if ($reflector instanceof ReflectionClass) {
            //phpcs:ignore SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable
            /** @var ReflectionClass<object> $reflector */
            return $this->create_from_reflection_class($reflector);
        }
        if ($reflector instanceof ReflectionParameter) {
            return $this->create_from_reflection_parameter($reflector);
        }
        if ($reflector instanceof ReflectionMethod) {
            return $this->create_from_reflection_method($reflector);
        }
        if ($reflector instanceof ReflectionProperty) {
            return $this->create_from_reflection_property($reflector);
        }
        if ($reflector instanceof Reflection_Class_Constant) {
            return $this->create_from_reflection_class_constant($reflector);
        }
        throw new UnexpectedValueException('Unhandled \Reflector instance given:  ' . get_class($reflector));
    }
    private function create_from_reflection_parameter(ReflectionParameter $parameter): Context
    {
        $class = $parameter->get_declaring_class();
        if (!$class) {
            throw new InvalidArgumentException('Unable to get class of ' . $parameter->get_name());
        }
        return $this->create_from_reflection_class($class);
    }
    private function create_from_reflection_method(ReflectionMethod $method): Context
    {
        $class = $method->get_declaring_class();
        return $this->create_from_reflection_class($class);
    }
    private function create_from_reflection_property(ReflectionProperty $property): Context
    {
        $class = $property->get_declaring_class();
        return $this->create_from_reflection_class($class);
    }
    private function create_from_reflection_class_constant(Reflection_Class_Constant $constant): Context
    {
        //phpcs:ignore SlevomatCodingStandard.Commenting.InlineDocCommentDeclaration.MissingVariable
        /** @phpstan-var ReflectionClass<object> $class */
        $class = $constant->get_declaring_class();
        return $this->create_from_reflection_class($class);
    }
    /**
     * @phpstan-param ReflectionClass<object> $class
     */
    private function create_from_reflection_class(ReflectionClass $class): Context
    {
        $file_name = $class->get_file_name();
        $namespace = $class->get_namespace_name();
        if (is_string($file_name) && file_exists($file_name)) {
            $contents = file_get_contents($file_name);
            if ($contents === false) {
                throw new RuntimeException('Unable to read file "' . $file_name . '"');
            }
            return $this->create_for_namespace($namespace, $contents);
        }
        return new Context($namespace, []);
    }
    /**
     * Build a Context for a namespace in the provided file contents.
     *
     * @see Context for more information on Contexts.
     *
     * @param string $namespace    It does not matter if a `\` precedes the namespace name,
     * this method first normalizes.
     * @param string $fileContents The file's contents to retrieve the aliases from with the given namespace.
     */
    public function create_for_namespace(string $namespace, string $file_contents): Context
    {
        $namespace = trim($namespace, '\\');
        $use_statements = [];
        $current_namespace = '';
        $tokens = new ArrayIterator(token_get_all($file_contents));
        while ($tokens->valid()) {
            $current_token = $tokens->current();
            switch ($current_token[0]) {
                case T_NAMESPACE:
                    $current_namespace = $this->parse_namespace($tokens);
                    break;
                case T_CLASS:
                case T_TRAIT:
                    // Fast-forward the iterator through the class so that any
                    // T_USE tokens found within are skipped - these are not
                    // valid namespace use statements so should be ignored.
                    $brace_level = 0;
                    $first_brace_found = false;
                    while ($tokens->valid() && ($brace_level > 0 || !$first_brace_found)) {
                        $current_token = $tokens->current();
                        if ($current_token === '{' || in_array($current_token[0], [T_CURLY_OPEN, T_DOLLAR_OPEN_CURLY_BRACES], true)) {
                            if (!$first_brace_found) {
                                $first_brace_found = true;
                            }
                            ++$brace_level;
                        }
                        if ($current_token === '}') {
                            --$brace_level;
                        }
                        $tokens->next();
                    }
                    break;
                case T_USE:
                    if ($current_namespace === $namespace) {
                        $use_statements += $this->parse_use_statement($tokens);
                    }
                    break;
            }
            $tokens->next();
        }
        return new Context($namespace, $use_statements);
    }
    /**
     * Deduce the name from tokens when we are at the T_NAMESPACE token.
     *
     * @param ArrayIterator<int, string|array{0:int,1:string,2:int}> $tokens
     */
    private function parse_namespace(ArrayIterator $tokens): string
    {
        // skip to the first string or namespace separator
        $this->skip_to_next_string_or_namespace_separator($tokens);
        $name = '';
        $accepted_tokens = [T_STRING, T_NS_SEPARATOR, T_NAME_QUALIFIED];
        while ($tokens->valid() && in_array($tokens->current()[0], $accepted_tokens, true)) {
            $name .= $tokens->current()[1];
            $tokens->next();
        }
        return $name;
    }
    /**
     * Deduce the names of all imports when we are at the T_USE token.
     *
     * @param ArrayIterator<int, string|array{0:int,1:string,2:int}> $tokens
     *
     * @return string[]
     * @psalm-return array<string, string>
     */
    private function parse_use_statement(ArrayIterator $tokens): array
    {
        $uses = [];
        while ($tokens->valid()) {
            $this->skip_to_next_string_or_namespace_separator($tokens);
            $uses += $this->extract_use_statements($tokens);
            $current_token = $tokens->current();
            if ($current_token[0] === self::T_LITERAL_END_OF_USE) {
                return $uses;
            }
        }
        return $uses;
    }
    /**
     * Fast-forwards the iterator as longs as we don't encounter a T_STRING or T_NS_SEPARATOR token.
     *
     * @param ArrayIterator<int, string|array{0:int,1:string,2:int}> $tokens
     */
    private function skip_to_next_string_or_namespace_separator(ArrayIterator $tokens): void
    {
        while ($tokens->valid()) {
            $current_token = $tokens->current();
            if (in_array($current_token[0], [T_STRING, T_NS_SEPARATOR], true)) {
                break;
            }
            if ($current_token[0] === T_NAME_QUALIFIED) {
                break;
            }
            if (defined('T_NAME_FULLY_QUALIFIED') && $current_token[0] === T_NAME_FULLY_QUALIFIED) {
                break;
            }
            $tokens->next();
        }
    }
    /**
     * Deduce the namespace name and alias of an import when we are at the T_USE token or have not reached the end of
     * a USE statement yet. This will return a key/value array of the alias => namespace.
     *
     * @param ArrayIterator<int, string|array{0:int,1:string,2:int}> $tokens
     *
     * @return string[]
     * @psalm-return array<string, string>
     *
     * @psalm-suppress TypeDoesNotContainType
     */
    private function extract_use_statements(ArrayIterator $tokens): array
    {
        $extracted_use_statements = [];
        $grouped_ns = '';
        $current_ns = '';
        $current_alias = '';
        $state = 'start';
        while ($tokens->valid()) {
            $current_token = $tokens->current();
            $token_id = is_string($current_token) ? $current_token : $current_token[0];
            $token_value = is_string($current_token) ? null : $current_token[1];
            switch ($state) {
                case 'start':
                    switch ($token_id) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            $current_ns .= (string) $token_value;
                            $current_alias = $token_value;
                            break;
                        case T_NAME_QUALIFIED:
                        case T_NAME_FULLY_QUALIFIED:
                            $current_ns .= (string) $token_value;
                            $current_alias = substr((string) $token_value, (int) strrpos((string) $token_value, '\\') + 1);
                            break;
                        case T_CURLY_OPEN:
                        case '{':
                            $state = 'grouped';
                            $grouped_ns = $current_ns;
                            break;
                        case T_AS:
                            $state = 'start-alias';
                            break;
                        case self::T_LITERAL_USE_SEPARATOR:
                        case self::T_LITERAL_END_OF_USE:
                            $state = 'end';
                            break;
                        default:
                            break;
                    }
                    break;
                case 'start-alias':
                    switch ($token_id) {
                        case T_STRING:
                            $current_alias = $token_value;
                            break;
                        case self::T_LITERAL_USE_SEPARATOR:
                        case self::T_LITERAL_END_OF_USE:
                            $state = 'end';
                            break;
                        default:
                            break;
                    }
                    break;
                case 'grouped':
                    switch ($token_id) {
                        case T_STRING:
                        case T_NS_SEPARATOR:
                            $current_ns .= (string) $token_value;
                            $current_alias = $token_value;
                            break;
                        case T_AS:
                            $state = 'grouped-alias';
                            break;
                        case self::T_LITERAL_USE_SEPARATOR:
                            $state = 'grouped';
                            $extracted_use_statements[(string) $current_alias] = $current_ns;
                            $current_ns = $grouped_ns;
                            $current_alias = '';
                            break;
                        case self::T_LITERAL_END_OF_USE:
                            $state = 'end';
                            break;
                        default:
                            break;
                    }
                    break;
                case 'grouped-alias':
                    switch ($token_id) {
                        case T_STRING:
                            $current_alias = $token_value;
                            break;
                        case self::T_LITERAL_USE_SEPARATOR:
                            $state = 'grouped';
                            $extracted_use_statements[(string) $current_alias] = $current_ns;
                            $current_ns = $grouped_ns;
                            $current_alias = '';
                            break;
                        case self::T_LITERAL_END_OF_USE:
                            $state = 'end';
                            break;
                        default:
                            break;
                    }
            }
            if ($state === 'end') {
                break;
            }
            $tokens->next();
        }
        if ($grouped_ns !== $current_ns) {
            $extracted_use_statements[(string) $current_alias] = $current_ns;
        }
        return $extracted_use_statements;
    }
}