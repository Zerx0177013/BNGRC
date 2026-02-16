<?php

namespace app\controllers;

use app\models\RegionModel;
use Flight;
use flight\Engine;

class RegionController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllRegions(): array {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);
		return $model->getAllRegions();
	}

	public function renderRegionList(): void {
		$regions = $this->getAllRegions();

		$this->app->render('regions', [
			'regions' => $regions,
			'currentPage' => 'regions',
		]);
	}

	public function renderRegionDetail($id): void {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);
		$region = $model->getRegionById($id);

		if ($region) {
			$this->app->render('region_detail', [
				'region' => $region,
				'currentPage' => 'regions',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);
		$region = $model->getRegionById($id);

		if ($region) {
			$this->app->render('region-form', [
				'region' => $region,
				'currentPage' => 'regions',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$this->app->render('region-add', [
			'currentPage' => 'regions',
		]);
	}

	public function createRegion(): void {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);

		$name = $this->app->request()->data->name;

		$regionId = $model->createRegion($name);

		if ($regionId) {
			$this->app->json(['success' => true, 'message' => 'Region created', 'regionId' => $regionId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create region'], 500);
		}
	}

	public function updateRegion($id): void {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);

		$name = $this->app->request()->data->name;

		$success = $model->updateRegion($id, $name);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Region updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update region'], 500);
		}
	}

	public function deleteRegion($id): void {
		$pdo = $this->app->db();
		$model = new RegionModel($pdo);

		$success = $model->deleteRegion($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Region deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete region'], 500);
		}
	}
}
