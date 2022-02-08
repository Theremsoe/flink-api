#!/bin/bash

if [ ! "$1" ]; then
    [[ $TRESHOLD =~ ^[0-9]+$ ]] || \
     { echo "A docker container name is required!"; exit $ERRCODE; }
fi;

if [ "$(docker ps -q --filter name=$1)" ]; then
    docker stop "$1";
    echo "Stopped $1 container";
fi;

if [ "$(docker ps -aq --filter name=$1)" ]; then
    docker rm -fv "$1";
    echo "Removed $1 container";
fi;
