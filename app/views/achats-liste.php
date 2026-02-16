<?php include __DIR__ . '/header.php'; ?>
<?php include __DIR__ . '/sidebar.php'; ?>
      <!--begin::App Main-->
      <main class="app-main">
        <!--begin::App Content Header-->
        <div class="app-content-header">
          <div class="container-fluid">
            <div class="row">
              <div class="col-sm-6">
                <h3 class="mb-0">Liste des achats</h3>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-end">
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/">Accueil</a></li>
                  <li class="breadcrumb-item"><a href="<?= BASE_URL ?>/achats">Achats</a></li>
                  <li class="breadcrumb-item active" aria-current="page">Liste</li>
                </ol>
              </div>
            </div>
          </div>
        </div>
        <!--end::App Content Header-->

        <!--begin::App Content-->
        <div class="app-content">
          <div class="container-fluid">

            <!-- Alert -->
            <div id="alertContainer"></div>

            <div class="card mb-4">
              <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">
                  <i class="bi bi-list-check me-2"></i>Historique des achats
                </h3>
                <div class="d-flex gap-2">
                  <select class="form-select form-select-sm" id="filtreVille" style="width: auto;">
                    <option value="">Toutes les villes</option>
                    <?php foreach ($villes as $ville): ?>
                      <option value="<?= $ville['id_ville'] ?>" <?= (isset($selectedVille) && $selectedVille == $ville['id_ville']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($ville['nom_ville']) ?>
                      </option>
                    <?php endforeach; ?>
                  </select>
                  <a href="<?= BASE_URL ?>/achats" class="btn btn-primary btn-sm">
                    <i class="bi bi-cart-plus me-1"></i> Nouvel achat
                  </a>
                </div>
              </div>
              <div class="card-body" id="achatsTableContainer">
                <!-- Le contenu sera généré dynamiquement -->
              </div>
              <div class="card-footer text-muted">
                Total : <strong id="totalCount">0</strong> achat(s)
              </div>
            </div>

          </div>
        </div>
        <!--end::App Content-->
      </main>
      <!--end::App Main-->

      <!-- Modal Confirmation Suppression -->
      <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="deleteModalLabel">
                <i class="bi bi-exclamation-triangle me-2"></i>Confirmer la suppression
              </h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fermer"></button>
            </div>
            <div class="modal-body">
              Êtes-vous sûr de vouloir supprimer cet achat ?
            </div>
            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
              <button type="button" class="btn btn-danger" id="btnConfirmDelete">Supprimer</button>
            </div>
          </div>
        </div>
      </div>

      <script>
        (function () {
          'use strict';

          // Variables globales
          var deleteModal = null;
          var currentDeleteId = null;
          var filtreVille = null;
          var tableContainer = null;
          var totalCount = null;
          var alertContainer = null;

          // Initialisation au chargement du DOM
          document.addEventListener('DOMContentLoaded', function () {
            // Récupérer les éléments du DOM
            deleteModal = new bootstrap.Modal(document.getElementById('deleteModal'));
            filtreVille = document.getElementById('filtreVille');
            tableContainer = document.getElementById('achatsTableContainer');
            totalCount = document.getElementById('totalCount');
            alertContainer = document.getElementById('alertContainer');

            // Vérifier que tous les éléments existent
            if (!filtreVille || !tableContainer || !totalCount) {
              console.error('Erreur: éléments DOM manquants');
              return;
            }

            // Charger les données initiales
            var initialData = <?= json_encode($achats) ?>;
            renderTable(initialData);

            // Event listener pour le filtre
            filtreVille.addEventListener('change', handleFilterChange);

            // Event listener pour la confirmation de suppression
            document.getElementById('btnConfirmDelete').addEventListener('click', handleDeleteConfirm);
          });

          // Gérer le changement de filtre
          function handleFilterChange() {
            var idVille = filtreVille.value;
            var url = '<?= BASE_URL ?>/achats/json';
            
            if (idVille) {
              url += '?id_ville=' + encodeURIComponent(idVille);
            }

            console.log('Filtre changé - ID Ville:', idVille);
            console.log('URL:', url);

            fetch(url)
              .then(function (response) {
                console.log('Status:', response.status);
                if (!response.ok) {
                  throw new Error('HTTP ' + response.status);
                }
                return response.json();
              })
              .then(function (data) {
                console.log('Données:', data);
                if (data.success && Array.isArray(data.achats)) {
                  renderTable(data.achats);
                } else {
                  throw new Error('Format de réponse invalide');
                }
              })
              .catch(function (error) {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur lors du chargement des données: ' + error.message);
              });
          }

          // Rendre le tableau
          function renderTable(achats) {
            if (!tableContainer || !totalCount) {
              console.error('Conteneur non trouvé');
              return;
            }

            // Mettre à jour le compteur
            totalCount.textContent = achats.length;

            // Si aucun achat
            if (achats.length === 0) {
              tableContainer.innerHTML = 
                '<div class="text-center text-muted py-5">' +
                '<i class="bi bi-inbox fs-1 d-block mb-3"></i>' +
                '<p class="fs-5">Aucun achat pour cette sélection</p>' +
                '</div>';
              return;
            }

            // Construire le tableau
            var html = '<div class="table-responsive">' +
              '<table class="table table-bordered table-hover">' +
              '<thead class="table-dark">' +
              '<tr>' +
              '<th>#</th>' +
              '<th>Date</th>' +
              '<th>Ville</th>' +
              '<th>Article</th>' +
              '<th>Catégorie</th>' +
              '<th>Quantité</th>' +
              '<th>Prix unitaire</th>' +
              '<th>Frais</th>' +
              '<th>Montant total</th>' +
              '<th>Don</th>' +
              '<th>Action</th>' +
              '</tr>' +
              '</thead>' +
              '<tbody>';

            // Ajouter les lignes
            achats.forEach(function (achat) {
              var date = new Date(achat.date_achat);
              var dateStr = formatDate(date);

              html += '<tr>' +
                '<td>' + escapeHtml(achat.id_achat) + '</td>' +
                '<td>' + dateStr + '</td>' +
                '<td>' + escapeHtml(achat.nom_ville) + '</td>' +
                '<td>' + escapeHtml(achat.nom_article) + '</td>' +
                '<td><span class="badge bg-secondary">' + escapeHtml(achat.nom_categorie) + '</span></td>' +
                '<td>' + formatNumber(achat.quantite) + '</td>' +
                '<td>' + formatNumber(achat.prix_unitaire) + ' Ar</td>' +
                '<td>' + Math.round(parseFloat(achat.frais_percent)) + '%</td>' +
                '<td class="fw-bold">' + formatNumber(achat.montant_total) + ' Ar</td>' +
                '<td><small class="text-muted">Don #' + escapeHtml(achat.id_don) + '</small></td>' +
                '<td>' +
                '<button type="button" class="btn btn-danger btn-sm btn-delete" data-id="' + escapeHtml(achat.id_achat) + '">' +
                '<i class="bi bi-trash"></i>' +
                '</button>' +
                '</td>' +
                '</tr>';
            });

            html += '</tbody></table></div>';

            // Insérer dans le DOM
            tableContainer.innerHTML = html;

            // Attacher les event listeners aux boutons delete
            attachDeleteListeners();
          }

          // Attacher les listeners aux boutons de suppression
          function attachDeleteListeners() {
            var deleteButtons = document.querySelectorAll('.btn-delete');
            deleteButtons.forEach(function (btn) {
              btn.addEventListener('click', function (e) {
                e.preventDefault();
                currentDeleteId = btn.getAttribute('data-id');
                if (deleteModal && currentDeleteId) {
                  deleteModal.show();
                }
              });
            });
          }

          // Gérer la confirmation de suppression
          function handleDeleteConfirm() {
            if (!currentDeleteId) {
              console.error('Aucun ID à supprimer');
              return;
            }

            var url = '<?= BASE_URL ?>/achats/' + encodeURIComponent(currentDeleteId);

            fetch(url, { method: 'DELETE' })
              .then(function (response) {
                if (!response.ok) {
                  throw new Error('HTTP ' + response.status);
                }
                return response.json();
              })
              .then(function (data) {
                if (data.success) {
                  showAlert('success', data.message || 'Achat supprimé avec succès');
                  deleteModal.hide();
                  currentDeleteId = null;
                  
                  // Recharger les données après 1 seconde
                  setTimeout(function () {
                    handleFilterChange();
                  }, 1000);
                } else {
                  throw new Error(data.message || 'Erreur lors de la suppression');
                }
              })
              .catch(function (error) {
                console.error('Erreur:', error);
                showAlert('danger', 'Erreur: ' + error.message);
              });
          }

          // Afficher une alerte
          function showAlert(type, message) {
            if (!alertContainer) return;

            alertContainer.innerHTML = 
              '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
              escapeHtml(message) +
              '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Fermer"></button>' +
              '</div>';

            // Auto-masquer après 5 secondes
            setTimeout(function () {
              alertContainer.innerHTML = '';
            }, 5000);
          }

          // Formater une date
          function formatDate(date) {
            var day = ('0' + date.getDate()).slice(-2);
            var month = ('0' + (date.getMonth() + 1)).slice(-2);
            var year = date.getFullYear();
            var hours = ('0' + date.getHours()).slice(-2);
            var minutes = ('0' + date.getMinutes()).slice(-2);
            return day + '/' + month + '/' + year + ' ' + hours + ':' + minutes;
          }

          // Formater un nombre
          function formatNumber(num) {
            return parseFloat(num).toFixed(2).replace('.', ',').replace(/\B(?=(\d{3})+(?!\d))/g, ' ');
          }

          // Échapper le HTML
          function escapeHtml(text) {
            var div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
          }

        })();
      </script>

<?php include __DIR__ . '/footer.php'; ?>
