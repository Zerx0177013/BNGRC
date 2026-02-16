<?php

namespace app\controllers;

use app\models\RecapModel;
use flight\Engine;

class RecapController
{
	protected Engine $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function renderRecapPage(): void
	{
		$pdo = $this->app->db();
		$recapModel = new RecapModel($pdo);

		$recapData = $recapModel->getRecapitulatif();

		$this->app->render('recap', [
			'currentPage' => 'recap',
			'data' => $recapData,
			'recap' => []
		]);
	}

	public function getRecapDataJson(): void
	{
		$pdo = $this->app->db();
		$recapModel = new RecapModel($pdo);

		$recap = $recapModel->getRecapitulatif();

		$this->app->json([
			'success' => true,
			'data' => [
				'montant_total' => $recap['montant_total'],
				'montant_satisfait' => $recap['montant_satisfait'],
				'montant_restant' => $recap['montant_restant'],
				'pourcentage_satisfait' => $recap['pourcentage_satisfait']
			]
		]);
	}
}
