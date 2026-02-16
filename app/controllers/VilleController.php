<?php

namespace app\controllers;

use app\models\VilleModel;
use app\models\RegionModel;
use Flight;
use flight\Engine;

class VilleController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllVilles(): array {
		$pdo = $this->app->db();
		$model = new VilleModel($pdo);
		return $model->getAllVilles();
	}

	public function renderVilleList(): void {
		$villes = $this->getAllVilles();

		$this->app->render('villes', [
			'villes' => $villes,
			'currentPage' => 'villes',
		]);
	}

	public function renderVilleDetail($id): void {
		$pdo = $this->app->db();
		$model = new VilleModel($pdo);
		$ville = $model->getVilleById($id);

		if ($ville) {
			$this->app->render('ville_detail', [
				'ville' => $ville,
				'currentPage' => 'villes',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$villeModel = new VilleModel($pdo);
		$regionModel = new RegionModel($pdo);
		
		$ville = $villeModel->getVilleById($id);
		$regions = $regionModel->getAllRegions();

		if ($ville) {
			$this->app->render('ville-form', [
				'ville' => $ville,
				'regions' => $regions,
				'currentPage' => 'villes',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$pdo = $this->app->db();
		$regionModel = new RegionModel($pdo);
		$regions = $regionModel->getAllRegions();

		$this->app->render('ville-form', [
			'regions' => $regions,
			'currentPage' => 'villes',
		]);
	}

	public function createVille(): void {
		$pdo = $this->app->db();
		$model = new VilleModel($pdo);

		$name = $this->app->request()->data->name;
		$idRegion = $this->app->request()->data->id_region;

		$villeId = $model->createVille($name, $idRegion);

		if ($villeId) {
			$this->app->json(['success' => true, 'message' => 'Ville created', 'villeId' => $villeId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create ville'], 500);
		}
	}

	public function updateVille($id): void {
		$pdo = $this->app->db();
		$model = new VilleModel($pdo);

		$name = $this->app->request()->data->name;
		$idRegion = $this->app->request()->data->id_region;

		$success = $model->updateVille($id, $name, $idRegion);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Ville updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update ville'], 500);
		}
	}

	public function deleteVille($id): void {
		$pdo = $this->app->db();
		$model = new VilleModel($pdo);

		$success = $model->deleteVille($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Ville deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete ville'], 500);
		}
	}
}
