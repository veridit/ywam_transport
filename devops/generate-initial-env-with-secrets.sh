#!/bin/bash

# Generate .env file if it doesn't exist
if [ ! -f .env ]; then
    cat > .env << EOF
POSTGRES_DB=ywam_transport
POSTGRES_USER=ywam_transport
POSTGRES_PASSWORD=$(openssl rand -base64 20 | tr -dc 'a-zA-Z0-9' | head -c 20)
POSTGRES_HOST=localhost
POSTGRES_PORT=5442

DJANGO_SECRET_KEY=$(openssl rand -base64 32 | tr -dc 'a-zA-Z0-9' | head -c 32)

DJANGO_SUPERUSER_USERNAME=admin
DJANGO_SUPERUSER_EMAIL=transportation@uofnkona.edu
DJANGO_SUPERUSER_PASSWORD=$(openssl rand -base64 12 | tr -dc 'a-zA-Z0-9' | head -c 12)

OLD_MYSQL_DATABASE=transportation
OLD_MYSQL_USER=transport
OLD_MYSQL_PASSWORD=

MYSQL_ROOT_PASSWORD=$(openssl rand -base64 20 | tr -dc 'a-zA-Z0-9' | head -c 20)
MYSQL_DATABASE=ywam_transport
MYSQL_USER=ywam_transport
MYSQL_PASSWORD=$(openssl rand -base64 20 | tr -dc 'a-zA-Z0-9' | head -c 20)
MYSQL_HOST=localhost
MYSQL_PORT=3406
EOF
    echo ".env file created with random secrets"
    echo "Please set OLD_MYSQL_PASSWORD in .env"
else
    echo ".env file already exists"
fi
