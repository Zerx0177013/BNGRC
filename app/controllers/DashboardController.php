<?php

namespace app\controllers;

use app\models\DonModel;
use app\models\BesoinModel;
use flight\Engine;

class DashboardController
{
	protected Engine $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function renderDashboard(): void
	{
		$pdo = $this->app->db();
		$donModel = new DonModel($pdo);
		$besoinModel = new BesoinModel($pdo);

		// Données pour les graphiques
		$donsParJour = $donModel->getDonsParJour();
		$dispatchParCategorie = $donModel->getDispatchParCategorie();
		$donsParArticle = $donModel->getDonsParArticle();

		// Stats totales
		$totalDons = $donModel->getStatsTotaux();
		$totalDispatches = $donModel->getStatsDispatches();
		$totalBesoins = $besoinModel->getStatsTotaux();

		// Taux calculés
		$tauxDons = ($totalBesoins['total'] > 0)
			? round(($totalDons['total'] / $totalBesoins['total']) * 100, 1)
			: 0;
		$tauxDispatch = ($totalBesoins['total'] > 0)
			? round(($totalDispatches['total'] / $totalBesoins['total']) * 100, 1)
			: 0;

		$this->app->render('index', [
			'donsParJour' => $donsParJour,
			'dispatchParCategorie' => $dispatchParCategorie,
			'donsParArticle' => $donsParArticle,
			'totalDons' => $totalDons,
			'totalDispatches' => $totalDispatches,
			'tauxDons' => $tauxDons,
			'tauxDispatch' => $tauxDispatch,
		]);
	}
}
