#!/usr/bin/env bash

BASEDIR=$(dirname $0)
PROJECTDIR="${BASEDIR}/../../"
cd ${PROJECTDIR}

JSONMODE=$1
LOCAL_MODE=$2
PHP="php"
PHPVERSIONS=($PHP)

source ${BASEDIR}/config.conf

# Download jpgraph if not present
JPGRAPH_LIB=${PROJECTDIR}/vendor/jpgraph
if [ ! -d ${JPGRAPH_LIB} ]; then
    echo "JPGraph is not present, downloading it..."
    mkdir ${JPGRAPH_LIB}
    cd ${JPGRAPH_LIB}
    wget -O jpgraph.tar.gz "http://jpgraph.net/download/download.php?p=11"
    tar xvzf jpgraph.tar.gz
    rm jpgraph.tar.gz
    mv jpgraph-4.0.2 jpgraph
    echo "JPGraph installed!"
    cd -
fi

for VERSION in ${PHPVERSIONS[*]}
do
    if [ "$LOCAL_MODE" != "1" ]; then
        PHP="${VERSION}"
    fi

    # trick
    echo "<?php print PHP_VERSION; ?>" > PHP_VERSION.php
    REALPHPVERSION=`$PHP PHP_VERSION.php`
    rm PHP_VERSION.php

    FILE="${BASEDIR}/results${REALPHPVERSION}.data"
    echo -n "" > $FILE
    if [ "$JSONMODE" != "1" ]; then
        echo "${REALPHPVERSION}"
    fi

    for METHOD in ${METHODS[*]}
    do
        for ITERATION in ${ITERATIONS[*]}
        do
            echo "Doing $METHOD with $ITERATION iterations."
            $PHP tests/Perfs/test.php $METHOD 0 $ITERATION $JSONMODE >> $FILE
            $PHP tests/Perfs/test.php $METHOD 1 $ITERATION $JSONMODE >> $FILE
            $PHP tests/Perfs/test.php $METHOD 2 $ITERATION $JSONMODE >> $FILE
        done
    done

    if [ "$JSONMODE" != "1" ]; then
        cat $FILE
    else
        # use the php from php here to get GD
        php tests/Perfs/graph.php "${REALPHPVERSION}" > ${REALPHPVERSION}_graph.png
        php tests/Perfs/upload.php "${REALPHPVERSION}"
        rm ${REALPHPVERSION}_graph.png
    fi
done

exit 0
