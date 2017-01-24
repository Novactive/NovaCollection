#!/usr/bin/env bash

BASEDIR=$(dirname $0)
source ${BASEDIR}/../../scripts/functions
PROJECTDIR="${BASEDIR}/../../"
cd $PROJECTDIR

JSONMODE=$1
LOCAL_MODE=$2

if [ "$LOCAL_MODE" == "1" ]; then
    PHP="php"
    PHPVERSIONS=($PHP)
else
    PHP56="php56"
    PHP7="php7"
    PHPVERSIONS=($PHP56 $PHP7)
fi

source ${BASEDIR}/config.conf

for VERSION in ${PHPVERSIONS[*]}
do
    FILE="${BASEDIR}/results${VERSION}.data"
    echo -n "" > $FILE
    if [ "$JSONMODE" != "1" ]; then
        echoTitle "${VERSION}"
    fi

    if [ "$LOCAL_MODE" != "1" ]; then
        PHP="./${VERSION}"
    fi

    for METHOD in ${METHODS[*]}
    do
        for ITERATION in ${ITERATIONS[*]}
        do
            echoAction "Doing $METHOD with $ITERATION iterations."
            $PHP tests/Perfs/test.php $METHOD 0 $ITERATION $JSONMODE >> $FILE
            $PHP tests/Perfs/test.php $METHOD 1 $ITERATION $JSONMODE >> $FILE
            $PHP tests/Perfs/test.php $METHOD 2 $ITERATION $JSONMODE >> $FILE
        done
    done

    if [ "$JSONMODE" != "1" ]; then
        cat $FILE
    else
        # use the php from php here to get GD
        php tests/Perfs/graph.php "${VERSION}" > ${VERSION}_graph.png
    fi
done

exit 0
