# Tâches restantes - Système d'achats et dispatch BNGRC

## 1. Simulation dispatch (preview)

**Objectif :** Permettre de visualiser les résultats d'un dispatch AVANT de l'exécuter réellement.

**Modifications nécessaires :**

### A. DispatchModel
- Ajouter méthode `simulerDispatchPreview($idsDons)` 
  - Même logique que `simulerDispatch()` actuel
  - SANS insertions en base (pas d'INSERT)
  - Retourne tableau avec : id_don, id_besoin, ville, quantite_attribuee, nom_article

### B. DispatchController
- Ajouter méthode `simulateDispatch()`
  - Route : `POST /dispatch/simulate`
  - Appelle `simulerDispatchPreview()`
  - Retourne JSON avec preview des attributions

### C. Vue dispatch-dons.php
- Ajouter bouton "Simuler" à côté de "Dispatcher"
- Clic sur Simuler → affiche tableau modal avec résultats prévisionnels
- Colonnes : Don #, Besoin, Ville, Article, Quantité attribuée
- Modal contient bouton "Valider" pour exécution réelle

**Logique :**
```
User sélectionne dons → clique "Simuler"
  → fetch POST /dispatch/simulate avec dons[]
  → affiche modal avec preview tableau
  → User clique "Valider" dans modal
  → fetch POST /dispatch/execute (dispatch réel)
```

---

## 2. Bouton validation dispatch réel

**Objectif :** Séparer la simulation de l'exécution réelle.

**Modifications nécessaires :**

### A. Workflow actuel vs nouveau

**Actuel :**
- 1 seul bouton "Dispatcher" → exécution directe

**Nouveau :**
- Bouton "Simuler" → modal preview
- Bouton "Valider" dans modal → exécution réelle

### B. dispatch-dons.php
- Renommer bouton existant "Dispatcher" en "Simuler"
- Dans modal de preview, ajouter bouton "Valider le dispatch"
- Le bouton "Valider" appelle `/dispatch/execute` (route existante)

**Flow utilisateur :**
1. Sélectionne dons avec checkboxes
2. Clique "Simuler" → voir preview
3. Si OK, clique "Valider" → dispatch réel + reload
4. Si pas OK, ferme modal et ajuste sélection

---

## 3. Page récapitulation + Ajax

**Objectif :** Dashboard récapitulatif des besoins (totaux, satisfaits, restants) avec actualisation Ajax.

**Fichiers à créer :**

### A. RecapController.php(Done)
```php
class RecapController {
    public function renderRecapPage() {
        // Affiche la page recap.php
    }
    
    public function getRecapDataJson() {
        // Route : GET /recap/data
        // Retourne JSON avec statistiques
    }
}
```

### B. RecapModel.php ou ajouter dans BesoinModel(Done)
```php
public function getRecapitulatif() {
    // Retourne :
    // - besoins_totaux_montant : SUM(quantite * prix_unitaire) de TOUS les besoins
    // - besoins_satisfaits_montant : montant des dispatches + achats
    // - besoins_restants_montant : totaux - satisfaits
    
    SELECT 
        SUM(b.quantite * a.prix_unitaire) as montant_total_besoins,
        SUM(COALESCE(dispatches, 0) + COALESCE(achats, 0)) * a.prix_unitaire as montant_satisfait,
        ...
}
```

### C. Vue recap.php(Done)
- Header "Récapitulation"
- 3 cards Bootstrap :
  1. **Besoins totaux** : XXX XXX,XX Ar (badge bleu)
  2. **Besoins satisfaits** : XXX XXX,XX Ar (badge vert) + pourcentage
  3. **Besoins restants** : XXX XXX,XX Ar (badge rouge)
  
- Bouton "Actualiser" avec icône refresh
  - Appelle `/recap/data` en Ajax
  - Met à jour les 3 cards SANS reload
  - Spinner pendant chargement

**Structure JSON retourné :**
```json
{
  "success": true,
  "data": {
    "montant_total": 15000000.00,
    "montant_satisfait": 8500000.00,
    "montant_restant": 6500000.00,
    "pourcentage_satisfait": 56.67
  }
}
```

### D. Sidebar
Ajouter lien "Récapitulation" sous section "DONATION"
- Icône : `bi-clipboard-data`
- Page : `achats` → `currentPage === 'recap'`

---

## 4. Routes pour achats/recap

**Routes déjà créées (✅) :**
```php
// Achats
GET  /achats           → renderBesoinsRestants()
GET  /achats/liste     → renderAchatList()
GET  /achats/json      → getAchatsJson()
POST /achats           → createAchat()
DELETE /achats/@id     → deleteAchat()
```

**Routes à ajouter :**

### A. routes.php - Dispatch simulation
```php
// Dans le groupe /dispatch
$router->post('/simulate', [$dispatchController, 'simulateDispatch']);
```

### B. routes.php - Récapitulation
```php
// Après le groupe /achats
$router->group('/recap', function (Router $router) use ($app) {
    $recapController = new RecapController($app);
    
    $router->get('/', [$recapController, 'renderRecapPage']);
    $router->get('/data', [$recapController, 'getRecapDataJson']);
});
```

### C. Import controller
```php
use app\controllers\RecapController;
```

---

## Ordre d'implémentation recommandé

1. **Simulation dispatch** (tâche 1 + 2 ensemble)
   - Modifier DispatchModel
   - Modifier DispatchController
   - Modifier dispatch-dons.php
   - Tester le flow simulation → validation

2. **Récapitulation** (tâche 3 + 4)
   - Créer RecapModel ou méthode dans BesoinModel
   - Créer RecapController
   - Créer vue recap.php avec Ajax
   - Ajouter routes
   - Mettre à jour sidebar

---

## Vérifications finales

### Après simulation/validation dispatch :
- [ ] Tester dispatch avec simulation
- [ ] Vérifier que "Valider" exécute réellement
- [ ] Vérifier message succès + reload
- [ ] Vérifier que dons dispatchés disparaissent de la liste

### Après récapitulation :
- [ ] Vérifier calculs des montants
- [ ] Tester bouton "Actualiser" Ajax
- [ ] Vérifier que spinner s'affiche
- [ ] Vérifier que les cards se mettent à jour sans reload
- [ ] Vérifier pourcentage de satisfaction

### Tests d'intégration :
1. Créer besoin → vérifier récap "total" augmente
2. Dispatcher don → vérifier récap "satisfait" augmente
3. Acheter article → vérifier récap "satisfait" augmente
4. Vérifier récap "restant" = total - satisfait

---

## Notes techniques

### Calcul montant satisfait
```sql
-- Pour un besoin donné
SELECT 
    b.quantite * a.prix_unitaire as montant_besoin,
    
    -- Satisfait par dispatch
    COALESCE(SUM(d.quantite_attribuee), 0) * a.prix_unitaire as satisfait_dispatch,
    
    -- Satisfait par achat
    COALESCE(SUM(ac.quantite), 0) * a.prix_unitaire as satisfait_achat
    
FROM bngrc_besoin_ETU003918 b
JOIN bngrc_article_ETU003918 a ON b.id_article = a.id_article
LEFT JOIN bngrc_dispatch_ETU003918 d ON d.id_besoin = b.id_besoin
LEFT JOIN bngrc_achat_ETU003918 ac ON ac.id_besoin = b.id_besoin
GROUP BY b.id_besoin
```

### Modal preview dispatch example HTML
```html
<div class="modal fade" id="previewModal">
  <div class="modal-dialog modal-lg">
    <div class="modal-header">
      <h5>Preview du dispatch</h5>
    </div>
    <div class="modal-body">
      <table class="table">
        <thead>
          <tr>
            <th>Don #</th>
            <th>Ville</th>
            <th>Article</th>
            <th>Quantité</th>
          </tr>
        </thead>
        <tbody id="previewTableBody">
          <!-- Rempli dynamiquement via JS -->
        </tbody>
      </table>
    </div>
    <div class="modal-footer">
      <button class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
      <button class="btn btn-success" id="btnConfirmDispatch">Valider le dispatch</button>
    </div>
  </div>
</div>
```

---

**Date dernière mise à jour :** 16 février 2026
**Projet :** BNGRC - Examen S3 L2
**Étudiant :** ETU003918
