#!/usr/bin/env bash

BASEDIR=$(dirname $0)
PHP="php"
PROJECTDIR="${BASEDIR}/../"
cd ${PROJECTDIR}

CLASSES_TO_TESTS=("Novactive\Collection\Collection" "Novactive\Collection\Debug\Collection" "Novactive\Tests\Perfs\ArrayMethodCollection" "Novactive\Tests\Perfs\ForeachMethodCollection")
#CLASSES_TO_TESTS=("Novactive\Collection\Debug\Collection")
for CLASS in ${CLASSES_TO_TESTS[*]}
do
    echo " == $CLASS == "
    DEBUG_COLLECTION_CLASS=$CLASS $PHP vendor/bin/phpunit
    if [ $? != 0 ]; then
        echo "$CLASS failed."
        exit 1;
    fi
done

exit 0;
