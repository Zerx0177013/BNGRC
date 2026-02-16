#!/bin/bash
# =============================================================
#  Script de préparation des fichiers SQL pour la production
#  Supprime les commandes : DROP DATABASE, CREATE DATABASE, USE
# =============================================================

SOURCE_DIR="$1"
DEST_DIR="$2"
DB_NAME="$3"

if [ -z "$SOURCE_DIR" ] || [ -z "$DEST_DIR" ] || [ -z "$DB_NAME" ]; then
    echo "Usage: $0 <source_dir> <dest_dir> <db_name>"
    exit 1
fi

mkdir -p "$DEST_DIR"

# Parcourir tous les fichiers SQL
find "$SOURCE_DIR" -type f -name "*.sql" | while read -r sql_file; do
    filename=$(basename "$sql_file")
    dest_file="$DEST_DIR/$filename"
    
    # Filtrer et remplacer dans le fichier
    sed \
        -e '/^DROP DATABASE/d' \
        -e '/^CREATE DATABASE/d' \
        -e '/^USE /d' \
        -e "s/bngrc_/${DB_NAME}_/g" \
        "$sql_file" > "$dest_file"
    
    echo "  Préparé: $filename"
done

echo ""
echo "✓ Fichiers SQL préparés dans: $DEST_DIR"
