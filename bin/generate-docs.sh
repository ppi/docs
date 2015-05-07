#!/bin/bash
EXECPATH=`dirname $0`
cd $EXECPATH
cd ..

rm build -Rf
sphinx-build en build
