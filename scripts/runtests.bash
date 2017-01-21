#!/usr/bin/env bash

BASEDIR=$(dirname $0)
PHP="env php "
PHP7="./php7 "
source ${BASEDIR}/functions
PROJECTDIR="${BASEDIR}/../"

cd ${PROJECTDIR}

echoTitle "******** Run tests ********"

echoAction "PHP MAC 5.6"
$PHP vendor/bin/phpunit

echoAction "PHP 7 DOCKER"
$PHP7 vendor/bin/phpunit

echoSuccess "Done."
exit 0;
