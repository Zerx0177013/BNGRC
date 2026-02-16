#!/bin/bash

# Script pour charger les données de test dans la base de données Takalo-Takalo
# Date: 2026-02-13

# Couleurs pour l'affichage
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Configuration de la base de données
DB_USER="root"
DB_PASS=""
DB_NAME="takalo"
SCRIPT_DIR="$(cd "$(dirname "${BASH_SOURCE[0]}")" && pwd)"
PARENT_SCRIPT_DIR="$(dirname "$SCRIPT_DIR")"

# Fonction pour afficher les messages
print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_info() {
    echo -e "${YELLOW}→ $1${NC}"
}

# Vérifier si mysql de XAMPP est disponible
MYSQL_PATH="/opt/lampp/bin/mysql"
if [ ! -f "$MYSQL_PATH" ]; then
    print_error "MySQL de XAMPP n'est pas trouvé à $MYSQL_PATH"
    print_info "Assurez-vous que XAMPP est installé et que MySQL est démarré"
    exit 1
fi

echo "================================================"
echo "  Chargement des données Takalo-Takalo"
echo "================================================"
echo ""

# Demander le mot de passe MySQL si nécessaire
read -sp "Entrez le mot de passe MySQL pour l'utilisateur '$DB_USER' (laissez vide si aucun): " DB_PASS
echo ""
echo ""

# Construire la commande MySQL avec le chemin XAMPP
if [ -z "$DB_PASS" ]; then
    MYSQL_CMD="$MYSQL_PATH -u $DB_USER"
else
    MYSQL_CMD="$MYSQL_PATH -u $DB_USER -p$DB_PASS"
fi

# Liste des scripts à exécuter dans l'ordre
declare -a scripts=(
    "$PARENT_SCRIPT_DIR/2026-02-09_01_tables.sql:Création de la base de données et tables"
    "$PARENT_SCRIPT_DIR/2026-02-09_01_view.sql:Création des vues"
    "2026-02-13_00_insertCategories.sql:Insertion des catégories et statuts"
    "2026-02-13_01_insertUser.sql:Insertion des utilisateurs"
    "2026-02-10_01_INSERTADMIN.sql:Insertion de l'administrateur"
    "2026-02-13_02_insertObjets.sql:Insertion des objets"
    "2026-02-13_03_insertDemandes.sql:Insertion des demandes"
    "test_v_historique.sql"
)

# Compteurs
total=${#scripts[@]}
success=0
failed=0

# Exécuter chaque script
for item in "${scripts[@]}"; do
    IFS=':' read -r script description <<< "$item"
    
    # Déterminer le chemin complet du script
    if [[ "$script" == /* ]]; then
        # Chemin absolu
        script_path="$script"
    elif [[ "$script" == "$PARENT_SCRIPT_DIR"* ]]; then
        # Chemin déjà avec PARENT_SCRIPT_DIR
        script_path="$script"
    else
        # Chemin relatif au répertoire insertion
        script_path="$SCRIPT_DIR/$script"
    fi
    
    print_info "$description..."
    
    if [ ! -f "$script_path" ]; then
        print_error "Fichier non trouvé: $script_path"
        ((failed++))
        continue
    fi
    
    # Exécuter le script SQL
    if $MYSQL_CMD $DB_NAME < "$script_path" 2>/tmp/mysql_error.log; then
        print_success "$description - OK"
        ((success++))
    else
        print_error "$description - ERREUR"
        if [ -s /tmp/mysql_error.log ]; then
            echo -e "${RED}Détails de l'erreur:${NC}"
            cat /tmp/mysql_error.log
        fi
        ((failed++))
    fi
    echo ""
done

# Résumé
echo "================================================"
echo "  Résumé du chargement"
echo "================================================"
echo "Total de scripts: $total"
echo -e "${GREEN}Réussis: $success${NC}"
if [ $failed -gt 0 ]; then
    echo -e "${RED}Échoués: $failed${NC}"
fi
echo ""

if [ $failed -eq 0 ]; then
    print_success "Toutes les données ont été chargées avec succès!"
    echo ""
    echo "Vous pouvez maintenant vous connecter avec:"
    echo "  - Username: admin (ou alice_martin, bob_dupont, etc.)"
    echo "  - Password: password123"
else
    print_error "Certains scripts ont échoué. Vérifiez les erreurs ci-dessus."
    exit 1
fi
