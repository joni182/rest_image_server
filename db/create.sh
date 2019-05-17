#!/bin/sh

if [ "$1" = "travis" ]; then
    psql -U postgres -c "CREATE DATABASE restimageserver_test;"
    psql -U postgres -c "CREATE USER restimageserver PASSWORD 'restimageserver' SUPERUSER;"
else
    sudo -u postgres dropdb --if-exists restimageserver
    sudo -u postgres dropdb --if-exists restimageserver_test
    sudo -u postgres dropuser --if-exists restimageserver
    sudo -u postgres psql -c "CREATE USER restimageserver PASSWORD 'restimageserver' SUPERUSER;"
    sudo -u postgres createdb -O restimageserver restimageserver
    sudo -u postgres psql -d restimageserver -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    sudo -u postgres createdb -O restimageserver restimageserver_test
    sudo -u postgres psql -d restimageserver_test -c "CREATE EXTENSION pgcrypto;" 2>/dev/null
    LINE="localhost:5432:*:restimageserver:restimageserver"
    FILE=~/.pgpass
    if [ ! -f $FILE ]; then
        touch $FILE
        chmod 600 $FILE
    fi
    if ! grep -qsF "$LINE" $FILE; then
        echo "$LINE" >> $FILE
    fi
fi
