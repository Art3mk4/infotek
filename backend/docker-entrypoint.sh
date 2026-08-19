#!/bin/bash
set -e

echo "Waiting for database to be ready..."
until PGPASSWORD=$DB_PASSWORD psql -h "$DB_HOST" -U "$DB_USER" -d "$DB_NAME" -c '\q' 2>/dev/null; do
  echo "PostgreSQL is unavailable - sleeping"
  sleep 2
done

echo "PostgreSQL is up - applying migrations"
php /app/yii migrate:up --force-yes

echo "Demo user ready (admin / admin123)"

echo "Starting FrankenPHP..."
exec frankenphp run --config /etc/caddy/Caddyfile
