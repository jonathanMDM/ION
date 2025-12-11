#!/bin/bash

# Simula el DATABASE_URL de Render
DATABASE_URL="postgresql://ion:password123@dpg-xxxxx.virginia-postgres.render.com:5432/ion_db?client_encoding=utf8"

echo "Original DATABASE_URL:"
echo "$DATABASE_URL"
echo ""

# Remove query parameters
DB_URL_CLEAN=$(echo "$DATABASE_URL" | cut -d'?' -f1)
echo "Cleaned URL:"
echo "$DB_URL_CLEAN"
echo ""

# Extract components
DB_USER=$(echo "$DB_URL_CLEAN" | sed 's|.*://\([^:]*\):.*|\1|')
DB_PASSWORD=$(echo "$DB_URL_CLEAN" | sed 's|.*://[^:]*:\([^@]*\)@.*|\1|')
DB_HOST=$(echo "$DB_URL_CLEAN" | sed 's|.*@\([^:]*\):.*|\1|')
DB_PORT=$(echo "$DB_URL_CLEAN" | sed 's|.*:\([0-9]*\)/.*|\1|')
DB_DATABASE=$(echo "$DB_URL_CLEAN" | sed 's|.*/\([^/]*\)$|\1|')

echo "Parsed values:"
echo "  DB_HOST: $DB_HOST"
echo "  DB_PORT: $DB_PORT"
echo "  DB_DATABASE: $DB_DATABASE"
echo "  DB_USER: $DB_USER"
echo "  DB_PASSWORD: $DB_PASSWORD"
