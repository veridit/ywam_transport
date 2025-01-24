#!/bin/bash
# devops/view-schema.sh

# Exit on any failure for any command and on usage of an undefined variable.
set -euo pipefail

if test -n "${DEBUG:-}"; then
  set -x # Print all commands before running them - for easy debugging.
fi

# Load .env secrets
export $(grep -v '^#' .env | xargs)

# Capture the list of tables into an array
tables=$(docker compose exec -T db psql -U "$POSTGRES_USER" -d "$POSTGRES_DB" -c "\dt" \
  | awk -F '[[:space:]]*\\|[[:space:]]*' '$3 == "table" {print $2}')

# Iterate over the array and dump the structure for each table
for table in $tables; do
  echo "-- =================================================="
  docker compose exec -T db psql -U "$POSTGRES_USER" -d "$POSTGRES_DB" -c "\d $table"
done
