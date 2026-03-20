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

use function explode;
use function implode;
use InvalidArgumentException;
use Php_Documentor\Reflection\Types\Context;
use function strpos;
/**
 * Resolver for Fqsen using Context information
 *
 * @psalm-immutable
 */
class Fqsen_Resolver
{
    /** @var string Definition of the NAMESPACE operator in PHP */
    private const OPERATOR_NAMESPACE = '\\';
    public function resolve(string $fqsen, ?Context $context = null): Fqsen
    {
        if ($context === null) {
            $context = new Context('');
        }
        if ($this->is_fqsen($fqsen)) {
            return new Fqsen($fqsen);
        }
        return $this->resolve_partial_structural_element_name($fqsen, $context);
    }
    /**
     * Tests whether the given type is a Fully Qualified Structural Element Name.
     */
    private function is_fqsen(string $type): bool
    {
        return strpos($type, self::OPERATOR_NAMESPACE) === 0;
    }
    /**
     * Resolves a partial Structural Element Name (i.e. `Reflection\DocBlock`) to its FQSEN representation
     * (i.e. `\phpDocumentor\Reflection\DocBlock`) based on the Namespace and aliases mentioned in the Context.
     *
     * @throws InvalidArgumentException When type is not a valid FQSEN.
     */
    private function resolve_partial_structural_element_name(string $type, Context $context): Fqsen
    {
        $type_parts = explode(self::OPERATOR_NAMESPACE, $type, 2);
        $namespace_aliases = $context->get_namespace_aliases();
        // if the first segment is not an alias; prepend namespace name and return
        if (!isset($namespace_aliases[$type_parts[0]])) {
            $namespace = $context->get_namespace();
            if ($namespace !== '') {
                $namespace .= self::OPERATOR_NAMESPACE;
            }
            return new Fqsen(self::OPERATOR_NAMESPACE . $namespace . $type);
        }
        $type_parts[0] = $namespace_aliases[$type_parts[0]];
        return new Fqsen(self::OPERATOR_NAMESPACE . implode(self::OPERATOR_NAMESPACE, $type_parts));
    }
}