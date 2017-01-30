#!/usr/bin/env bash

BASEDIR=$(dirname $0)
PHP="php"
PHP7="./php7"
source ${BASEDIR}/functions
PROJECTDIR="${BASEDIR}/../"

cd ${PROJECTDIR}

echoTitle "******** Run tests ********"


PHP_VERSIONS=($PHP $PHP7)
CLASSES_TO_TESTS=("Novactive\Collection\Collection" "Novactive\Collection\Debug\Collection" "Novactive\Tests\Perfs\ArrayMethodCollection" "Novactive\Tests\Perfs\ForeachMethodCollection")


for PHPVERSION in ${PHP_VERSIONS[*]}
do
    for CLASS in ${CLASSES_TO_TESTS[*]}
    do
        echoAction "Version $PHPVERSION - $CLASS"
        DEBUG_COLLECTION_CLASS=$CLASS $PHPVERSION vendor/bin/phpunit


        if [ $? != 0 ]; then
            echoFail "Version $PHPVERSION - $CLASS"
            exit
        fi
    done
done

echoSuccess "Done."
exit 0;
