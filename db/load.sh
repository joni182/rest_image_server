#!/bin/sh

BASE_DIR=$(dirname "$(readlink -f "$0")")
if [ "$1" != "test" ]; then
    psql -h localhost -U restimageserver -d restimageserver < $BASE_DIR/restimageserver.sql
fi
psql -h localhost -U restimageserver -d restimageserver_test < $BASE_DIR/restimageserver.sql
