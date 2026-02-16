#!/bin/bash
# =============================================================
#  BNGRC - Script de déploiement sur serveur PRODUCTION
#  Serveur: 172.16.7.97
#  Base: db_s2_ETU003918
#  User: ETU003918
# =============================================================

# ---- Configuration PRODUCTION ----
MYSQL_BIN="/opt/lampp/bin/mysql"
MYSQL_USER="ETU003918"
MYSQL_PASS="s7mSG5Zt"
MYSQL_HOST="172.16.7.131"
MYSQL_DB="db_s2_ETU003918"

# ---- Répertoire du script ----
SCRIPT_DIR="$(cd "$(dirname "$0")" && pwd)"
SQL_DIR="$(cd "$SCRIPT_DIR/.." && pwd)"

# ---- Fichier critique : création des tables ----
SQL_TABLES="$SQL_DIR/2026-02-16_01_tables.sql"

# ---- Créer répertoire temporaire ----
TEMP_DIR=$(mktemp -d)
echo "Répertoire temporaire créé : $TEMP_DIR"

# ---- Nettoyage au cas où le script s'arrête ----
trap "rm -rf '$TEMP_DIR'" EXIT

# ---- Couleurs ----
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m'

# ---- Variables de suivi ----
WARNINGS=0
SUCCESS_COUNT=0
TOTAL_COUNT=0

# ---- Fonction pour nettoyer un fichier SQL ----
clean_sql_file() {
    local source_file="$1"
    local dest_file="$2"
    
    # Supprimer uniquement les lignes DROP DATABASE, CREATE DATABASE et USE
    # Ne PAS remplacer 'bngrc' dans les noms de tables
    sed '/^DROP DATABASE/d; /^CREATE DATABASE/d; /^USE /d' "$source_file" > "$dest_file"
}

# ---- Fonction pour exécuter un fichier SQL ----
run_sql_file() {
    local file="$1"
    local is_critical="$2"

    if [ ! -f "$file" ]; then
        echo -e "${RED}[ERREUR]${NC} Fichier introuvable : $file"
        if [ "$is_critical" = "true" ]; then
            exit 1
        fi
        ((WARNINGS++))
        return
    fi

    # Créer une version nettoyée du fichier
    local cleaned_file="$TEMP_DIR/$(basename "$file")"
    clean_sql_file "$file" "$cleaned_file"

    echo -n "  $(basename "$file") ... "

    local error_file=$(mktemp)
    
    # Exécuter le fichier nettoyé directement dans la base (syntaxe simplifiée)
    "$MYSQL_BIN" -h"$MYSQL_HOST" -u"$MYSQL_USER" -p"$MYSQL_PASS" "$MYSQL_DB" < "$cleaned_file" 2>"$error_file"

    if [ $? -eq 0 ]; then
        echo -e "${GREEN}[OK]${NC}"
        rm -f "$error_file"
        ((SUCCESS_COUNT++))
    else
        if [ "$is_critical" = "true" ]; then
            echo -e "${RED}[ÉCHEC CRITIQUE]${NC}"
            echo ""
            echo -e "${RED}════════════════════════════════════════${NC}"
            echo -e "${RED}  ERREUR CRITIQUE - ARRÊT DU SCRIPT${NC}"
            echo -e "${RED}════════════════════════════════════════${NC}"
            echo -e "Fichier: ${YELLOW}$(basename "$file")${NC}"
            echo ""
            echo -e "${RED}Détails de l'erreur:${NC}"
            cat "$error_file"
            echo ""
            rm -f "$error_file"
            exit 1
        else
            echo -e "${YELLOW}[WARN]${NC}"
            if [ -s "$error_file" ]; then
                echo -e "${YELLOW}    Erreur: $(head -1 "$error_file")${NC}"
            fi
            rm -f "$error_file"
            ((WARNINGS++))
        fi
    fi
    
    ((TOTAL_COUNT++))
}

# ---- Vérification de MySQL ----
if [ ! -x "$MYSQL_BIN" ]; then
    echo -e "${RED}[ERREUR]${NC} MySQL client introuvable à : $MYSQL_BIN"
    exit 1
fi

echo "=========================================="
echo "  BNGRC - Déploiement PRODUCTION"
echo "  Serveur: $MYSQL_HOST"
echo "  Base: $MYSQL_DB"
echo "=========================================="
echo ""

# ========== PHASE 1 : CRÉATION DE LA BASE ET DES TABLES ==========
echo -e "${BLUE}═══════════════════════════════════════════${NC}"
echo -e "${BLUE}  PHASE 1 : CRÉATION DE LA BASE (CRITIQUE)${NC}"
echo -e "${BLUE}═══════════════════════════════════════════${NC}"
echo ""

run_sql_file "$SQL_TABLES" "true"

# ========== PHASE 2 : FICHIERS SQL DU RÉPERTOIRE INSERTION ==========
echo ""
echo -e "${YELLOW}═══════════════════════════════════════════${NC}"
echo -e "${YELLOW}  PHASE 2 : FICHIERS SQL (insertion/)${NC}"
echo -e "${YELLOW}═══════════════════════════════════════════${NC}"
echo ""

if [ -d "$SCRIPT_DIR" ]; then
    while IFS= read -r -d '' sql_file; do
        run_sql_file "$sql_file" "false"
    done < <(find "$SCRIPT_DIR" -maxdepth 1 -type f -name "*.sql" -print0 | sort -z)
fi

# ========== PHASE 3 : FICHIERS SQL DU RÉPERTOIRE SCRIPT ==========
echo ""
echo -e "${YELLOW}═══════════════════════════════════════════${NC}"
echo -e "${YELLOW}  PHASE 3 : FICHIERS SQL (script/)${NC}"
echo -e "${YELLOW}═══════════════════════════════════════════${NC}"
echo ""

if [ -d "$SQL_DIR" ]; then
    while IFS= read -r -d '' sql_file; do
        if [ "$(basename "$sql_file")" != "$(basename "$SQL_TABLES")" ]; then
            run_sql_file "$sql_file" "false"
        fi
    done < <(find "$SQL_DIR" -maxdepth 1 -type f -name "*.sql" -print0 | sort -z)
fi

# ========== RÉSUMÉ ==========
echo ""
echo "=========================================="
if [ $WARNINGS -eq 0 ]; then
    echo -e "  ${GREEN}✓ Base de données '$MYSQL_DB' prête !${NC}"
    echo -e "  ${GREEN}$SUCCESS_COUNT/$TOTAL_COUNT fichiers SQL exécutés avec succès${NC}"
else
    echo -e "  ${YELLOW}⚠ Base de données '$MYSQL_DB' prête avec $WARNINGS avertissement(s)${NC}"
    echo -e "  ${GREEN}$SUCCESS_COUNT/$TOTAL_COUNT fichiers SQL exécutés avec succès${NC}"
fi
echo "=========================================="
echo ""
