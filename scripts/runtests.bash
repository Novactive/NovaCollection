#!/usr/bin/env bash

BASEDIR=$(dirname $0)
PHP="env php "
PHP7="./php7 "
source ${BASEDIR}/functions
PROJECTDIR="${BASEDIR}/../"

cd ${PROJECTDIR}

echoTitle "******** Run tests ********"

echoAction "PHP MAC 5.6 - Collection"
$PHP vendor/bin/phpunit
echoAction "PHP MAC 5.6 - ArrayMethodCollection"
DEBUG_COLLECTION_CLASS="Novactive\Tests\Perfs\ArrayMethodCollection" $PHP vendor/bin/phpunit
echoAction "PHP MAC 5.6 - ForeachMethodCollection"
DEBUG_COLLECTION_CLASS="Novactive\Tests\Perfs\ForeachMethodCollection" $PHP vendor/bin/phpunit

echoAction "PHP 7 DOCKER - Collection"
$PHP7 vendor/bin/phpunit

echoSuccess "Done."
exit 0;
