<?php
/**
 * Novactive Collection.
 *
 * @author    Luke Visinoni <l.visinoni@novactive.us, luke.visinoni@gmail.com>
 * @author    Sébastien Morel <s.morel@novactive.us, morel.seb@gmail.com>
 * @copyright 2017 Novactive
 * @license   MIT
 */
use Novactive\Collection\Collection;

include __DIR__.'/bootstrap.php';

$reflector = new ReflectionClass('Novactive\Collection\Collection');
$methods   = $reflector->getMethods(ReflectionMethod::IS_PUBLIC);
$docBlock  = \phpDocumentor\Reflection\DocBlockFactory::createInstance();

foreach ($methods as $method) {
    $doc = $docBlock->create($method->getDocComment());
    if ($doc->getSummary() == '{@inheritDoc}' || $doc->getSummary() == '') {
        continue;
    }
    $paramsColl = new Collection($method->getParameters());
    $params     = $paramsColl->map(
        function (ReflectionParameter $parameter) {
            return $parameter->getType().' $'.$parameter->getName();
        }
    )->implode(', ');

    $signature = $method->getName().'('.trim($params).')';

    $returnType = 'void';
    /* @var phpDocumentor\Reflection\DocBlock\Tags\Return_ $returnType */
    if (isset($doc->getTagsByName('return')[0])) {
        $type           = $doc->getTagsByName('return')[0]->getType();
        $classNameParts = explode('\\', get_class($type));
        $returnType     = trim(strtolower(array_pop($classNameParts)), '_');
    }
    $inPlace = $returnType == 'this';
    echo '|'.$signature.'|'.$doc->getSummary().'|'.($inPlace ? ':white_check_mark:' : ':negative_squared_cross_mark:').
         "|\n";
}
