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
}
