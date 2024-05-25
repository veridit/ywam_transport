#!/bin/bash

# Load environment variables from .env
export $(grep -v '^#' .env | xargs)

# Replace placeholders in transport.load.template and create transport.load
sed -e "s/{{MYSQL_USER}}/$MYSQL_USER/g" \
    -e "s/{{MYSQL_PASSWORD}}/$MYSQL_PASSWORD/g" \
    -e "s/{{MYSQL_DATABASE}}/$MYSQL_DATABASE/g" \
    -e "s/{{MYSQL_HOST}}/$MYSQL_HOST/g" \
    -e "s/{{MYSQL_PORT}}/$MYSQL_PORT/g" \
    -e "s/{{POSTGRES_USER}}/$POSTGRES_USER/g" \
    -e "s/{{POSTGRES_PASSWORD}}/$POSTGRES_PASSWORD/g" \
    -e "s/{{POSTGRES_DB}}/$POSTGRES_DB/g" \
    -e "s/{{POSTGRES_HOST}}/$POSTGRES_HOST/g" \
    -e "s/{{POSTGRES_PORT}}/$POSTGRES_PORT/g" \
    transport.load.template > transport.load
