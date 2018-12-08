<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
declare(strict_types=1);

use Novactive\Collection\Collection;
use phpDocumentor\Reflection\DocBlockFactory;

include __DIR__.'/bootstrap.php';

$reflector = new ReflectionClass(Collection::class);
$methods   = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);
$docBlock  = DocBlockFactory::createInstance();

foreach ($methods as $method) {
    $comment = $method->getDocComment();
    if (false === $comment) {
        continue;
    }
    $doc        = $docBlock->create($method->getDocComment());
    $paramsColl = new Collection($method->getParameters());
    $params     = $paramsColl->map(
        function (ReflectionParameter $parameter) {
            return trim($parameter->getType().' $'.$parameter->name);
        }
    )->implode(', ');
    $signature  = $method->name.'('.trim($params).')';
    echo '| '.$signature.' | '.str_replace(["\r", "\n"], [' ', ' '], $doc->getSummary()).' | '.PHP_EOL;
}
