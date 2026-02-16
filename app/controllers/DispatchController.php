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
	 * Render simulation page
	 */
	public function renderSimulationPage(): void {
		$this->app->render('simulation', [
			'currentPage' => 'dispatch',
		]);
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

			// Get dispatch data with simulated results
			$dispatchesData = $model->getSimulatedDispatchesParVilleArticle($simulatedResults);
			
			// Get totals by category for comparison
			$dispatchParCategorie = $model->getDispatchParCategorie(); // Dispatches réels
			$dispatchSimuleParCategorie = $this->calculateSimulatedDispatchByCategory($simulatedResults);
			$donsParCategorie = $donModel->getDonsParCategorie();

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
			error_log('Simulation error: ' . $e->getMessage());
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la simulation : ' . $e->getMessage()], 500);
		}
	}

	/**
	 * Calculate simulated dispatch totals by category
	 */
	private function calculateSimulatedDispatchByCategory(array $simulatedResults): array {
		$categories = [];
		
		foreach ($simulatedResults as $result) {
			$cat = $result['nom_categorie'];
			if (!isset($categories[$cat])) {
				$categories[$cat] = [
					'nom_categorie' => $cat,
					'total' => 0
				];
			}
			$categories[$cat]['total'] += $result['quantite_attribuee'];
		}
		
		// Sort by category name
		ksort($categories);
		
		return array_values($categories);
	}
}
