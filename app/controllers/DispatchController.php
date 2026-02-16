<?php

namespace app\controllers;

use app\models\DispatchModel;
use Flight;
use flight\Engine;

class DispatchController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function renderDispatchPage(): void {
		$pdo = $this->app->db();
		$model = new DispatchModel($pdo);
		$donsDisponibles = $model->getDonsWithReste();

		$this->app->render('dispatch-dons', [
			'dons' => $donsDisponibles,
			'currentPage' => 'dispatch',
		]);
	}

	public function executeDispatch(): void {
		$pdo = $this->app->db();
		$model = new DispatchModel($pdo);

		parse_str(file_get_contents('php://input'), $postData);
		$idsDons = isset($postData['dons']) ? $postData['dons'] : (isset($_POST['dons']) ? $_POST['dons'] : []);

		if (empty($idsDons)) {
			$this->app->json(['success' => false, 'message' => 'Aucun don sélectionné.'], 400);
			return;
		}

		if (!is_array($idsDons)) {
			$idsDons = [$idsDons];
		}

		try {
			$results = $model->simulerDispatch($idsDons);

			if (empty($results)) {
				$this->app->json([
					'success' => false, 
					'message' => 'Aucun besoin restant à satisfaire pour les dons sélectionnés.'
				]);
				return;
			}

			$this->app->json([
				'success' => true, 
				'message' => 'Dispatch effectué avec succès.', 
				'results' => $results,
				'count' => count($results)
			]);
		} catch (\Exception $e) {
			$this->app->json(['success' => false, 'message' => 'Erreur lors du dispatch : ' . $e->getMessage()], 500);
		}
	}

	public function renderDispatchHistory(): void {
		$pdo = $this->app->db();
		$model = new DispatchModel($pdo);
		$dispatches = $model->getAllDispatches();

		$this->app->render('dispatch-history', [
			'dispatches' => $dispatches,
			'currentPage' => 'dispatch',
		]);
	}

	public function clearDispatches(): void {
		$pdo = $this->app->db();
		$model = new DispatchModel($pdo);

		try {
			$model->clearAllDispatches();
			$this->app->json(['success' => true, 'message' => 'Tous les dispatches ont été supprimés.']);
		} catch (\Exception $e) {
			$this->app->json(['success' => false, 'message' => 'Erreur : ' . $e->getMessage()], 500);
		}
	}

	/**
	 * Get simulation data as JSON
	 */
	public function getSimulationData(): void {
		$pdo = $this->app->db();
		$model = new DispatchModel($pdo);
		$donModel = new \app\models\DonModel($pdo);

		parse_str(file_get_contents('php://input'), $postData);
		$idsDons = isset($postData['dons']) ? $postData['dons'] : (isset($_POST['dons']) ? $_POST['dons'] : []);

		if (empty($idsDons)) {
			$this->app->json(['success' => false, 'message' => 'Aucun don sélectionné.'], 400);
			return;
		}

		if (!is_array($idsDons)) {
			$idsDons = [$idsDons];
		}

		try {
			// Simulate dispatch without inserting into database
			$simulatedResults = $model->simulateDispatchOnly($idsDons);

			if (empty($simulatedResults)) {
				$this->app->json([
					'success' => false, 
					'message' => 'Aucun besoin restant à satisfaire pour les dons sélectionnés.'
				]);
				return;
			}

			// Get table data (dispatch format like dashboard)
			$dispatchesData = $model->getSimulatedDispatchesParVilleArticle($simulatedResults);
			
			// Get existing dispatches by category
			$dispatchParCategorie = $donModel->getDispatchParCategorie();
			
			// Get simulated dispatches by category (only the new simulated ones)
			$dispatchSimuleParCategorie = $this->calculateSimulatedDispatchByCategory($simulatedResults, $pdo);
			
			// Get dons by category
			$donsParCategorie = $donModel->getDonsParCategorie();

			// Debug logging
			error_log("Simulation Results Count: " . count($simulatedResults));
			error_log("Dispatch Simule Par Categorie: " . json_encode($dispatchSimuleParCategorie));

			$this->app->json([
				'success' => true, 
				'message' => 'Simulation effectuée avec succès.',
				'dispatchesData' => $dispatchesData,
				'dispatchParCategorie' => $dispatchParCategorie,
				'dispatchSimuleParCategorie' => $dispatchSimuleParCategorie,
				'donsParCategorie' => $donsParCategorie,
				'simulatedCount' => count($simulatedResults)
			]);
		} catch (\Exception $e) {
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la simulation : ' . $e->getMessage()], 500);
		}
	}

	/**
	 * Calculate simulated dispatch totals by category
	 */
	private function calculateSimulatedDispatchByCategory($simulatedResults, $pdo): array {
		$totals = [];

		foreach ($simulatedResults as $sim) {
			// Get article category
			$sql = '
				SELECT c.nom_categorie
				FROM bngrc_article_ETU003918 a
				JOIN bngrc_categorie_besoin_ETU003918 c ON a.id_categorie = c.id_categorie
				WHERE a.id_article = :id_article
			';
			$stmt = $pdo->prepare($sql);
			$stmt->execute([':id_article' => $sim['id_article']]);
			$cat = $stmt->fetch(\PDO::FETCH_ASSOC);

			if ($cat) {
				$catName = $cat['nom_categorie'];
				if (!isset($totals[$catName])) {
					$totals[$catName] = 0;
				}
				$totals[$catName] += $sim['quantite_attribuee'];
			}
		}

		// Convert to array format and sort by category name
		$result = [];
		foreach ($totals as $nom_categorie => $total) {
			$result[] = [
				'nom_categorie' => $nom_categorie,
				'total' => (string)$total  // Convert to string to match other data format
			];
		}

		// Sort by category name to ensure consistent order
		usort($result, function($a, $b) {
			return strcmp($a['nom_categorie'], $b['nom_categorie']);
		});

		return $result;
	}
}
