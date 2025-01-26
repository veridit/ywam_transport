#!/bin/bash

# Exit on error
set -e

# Run migrations
python manage.py migrate --noinput

# Start server
exec "$@"
