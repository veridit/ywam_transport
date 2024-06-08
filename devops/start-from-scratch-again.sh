#!/bin/bash
#

set -e # Exit on any failure for any command

if test -n "$DEBUG"; then
  set -x # Print all commands before running them - for easy debugging.
fi

WORKSPACE="$( cd "$( dirname "${BASH_SOURCE[0]}" )" && cd .. && pwd )"
pushd "$WORKSPACE"

# Connect to and download a fresh copy of the database
export $(grep -v '^#' .env | xargs)

# Fetch the data only if older than 1 day
if [[ ! -f "tmp/transport-dump.sql" || $(find "tmp/transport-dump.sql" -mtime +1) ]]; then
  ssh ec2-user@transportation.uofnkona.edu mysqldump -u"$OLD_MYSQL_USER" "$OLD_MYSQL_DATABASE" -p"$OLD_MYSQL_PASSWORD" > tmp/transport-dump.sql
fi

docker compose down --volumes
docker compose up -d db db-old

# Wait for postgresql first while mysql loads the dumped data.
max_retries=10
count=0
until docker-compose exec -t db pg_isready -U "$POSTGRES_USER" -d "$POSTGRES_DB"; do
  count=$((count + 1))
  if [ $count -ge $max_retries ]; then
    echo "pg_isready failed after $max_retries attempts."
    exit 1
  fi
  echo "pg_isready failed. Retrying in 1 second... (Attempt: $count)"
  sleep 1
done

# TODO: wait for a two digit number of tables.
count=0
until docker compose exec -T db-old mysql -h "$MYSQL_HOST" -u "$MYSQL_USER" -p"$MYSQL_PASSWORD" -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$MYSQL_DATABASE';" | grep -qE '^[1-9][0-9]*$'; do
  count=$((count + 1))
  if [ $count -ge $max_retries ]; then
    echo "MariaDB table check failed after $max_retries attempts."
    exit 1
  fi
  echo "MariaDB table check failed. Retrying in 1 second... (Attempt: $count)"
  sleep 1
done
sleep 1

mkdir -p tmp

./devops/generate_load_file.sh
pgloader tmp/transport.load
docker compose run web python manage.py migrate

popd
