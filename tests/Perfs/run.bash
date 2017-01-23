#!/usr/bin/env bash

BASEDIR=$(dirname $0)
source ${BASEDIR}/../../scripts/functions
PROJECTDIR="${BASEDIR}/../../"
cd $PROJECTDIR

JSONMODE=$1
TRAVIS_MODE=$2

if [ "$TRAVIS_MODE" == "travis" ]; then
    PHP="php"
    PHPVERSIONS=($PHP)
else
    PHP56="php56"
    PHP7="php7"
    PHPVERSIONS=($PHP56 $PHP7)
fi

ITERATIONS=(10 100 250 500 750 1000 2000 5000 10000 20000 30000 40000 50000 100000 500000 1000000 5000000 10000000)
METHODS=(map filter each combine)


for VERSION in ${PHPVERSIONS[*]}
do
    FILE="${BASEDIR}/results${VERSION}.data"
    echo -n "" > $FILE
    if [ "$JSONMODE" != "1" ]; then
        echoTitle "${VERSION}"
    fi

    if [ "$TRAVIS_MODE" != "travis" ]; then
        PHP="./${VERSION}"
    fi

    for METHOD in ${METHODS[*]}
    do
        for ITERATION in ${ITERATIONS[*]}
        do
            echoAction "Doing $METHOD with $ITERATION iterations."
            $PHP tests/Perfs/test.php $METHOD 0 $ITERATION $JSONMODE >> $FILE
            $PHP tests/Perfs/test.php $METHOD 1 $ITERATION $JSONMODE >> $FILE
        done
    done

    if [ "$JSONMODE" != "1" ]; then
        cat $FILE
    else
        # use the php from php here to get GD
        php tests/Perfs/graph.php "${VERSION}" 0 > ${VERSION}_10-50k.png
        php tests/Perfs/graph.php "${VERSION}" 1 > ${VERSION}_30k-10M.png
    fi
done

exit 0
