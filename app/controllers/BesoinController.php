<?php

namespace app\controllers;

use app\models\BesoinModel;
use app\models\VilleModel;
use app\models\ArticleModel;
use Flight;
use flight\Engine;

class BesoinController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllBesoins(): array {
		$pdo = $this->app->db();
		$model = new BesoinModel($pdo);
		return $model->getAllBesoins();
	}

	public function renderBesoinList(): void {
		$besoins = $this->getAllBesoins();

		$this->app->render('besoins', [
			'besoins' => $besoins,
			'currentPage' => 'besoins',
		]);
	}

	public function renderBesoinDetail($id): void {
		$besoins = $this->getAllBesoins();
		$besoin = null;
		
		foreach ($besoins as $b) {
			if ($b['id_besoin'] == $id) {
				$besoin = $b;
				break;
			}
		}

		if ($besoin) {
			$this->app->render('besoin_detail', [
				'besoin' => $besoin,
				'currentPage' => 'besoins',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$villeModel = new VilleModel($pdo);
		$articleModel = new ArticleModel($pdo);
		
		$besoins = $this->getAllBesoins();
		$besoin = null;
		
		foreach ($besoins as $b) {
			if ($b['id_besoin'] == $id) {
				$besoin = $b;
				break;
			}
		}
		
		$villes = $villeModel->getAllVilles();
		$articles = $articleModel->getAllArticles();

		if ($besoin) {
			$this->app->render('besoin-form', [
				'besoin' => $besoin,
				'villes' => $villes,
				'articles' => $articles,
				'currentPage' => 'besoins',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$pdo = $this->app->db();
		$villeModel = new VilleModel($pdo);
		$articleModel = new ArticleModel($pdo);
		
		$villes = $villeModel->getAllVilles();
		$articles = $articleModel->getAllArticles();

		$this->app->render('besoin-add', [
			'villes' => $villes,
			'articles' => $articles,
			'currentPage' => 'besoins',
		]);
	}

	public function createBesoin(): void {
		$pdo = $this->app->db();
		$model = new BesoinModel($pdo);

		$idVille = $this->app->request()->data->id_ville;
		$idArticle = $this->app->request()->data->id_article;
		$quantite = $this->app->request()->data->quantite;
		$dateSaisie = $this->app->request()->data->date_saisie ?? null;

		$besoinId = $model->createBesoin($idVille, $idArticle, $quantite, $dateSaisie);

		if ($besoinId) {
			$this->app->json(['success' => true, 'message' => 'Besoin created', 'besoinId' => $besoinId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create besoin'], 500);
		}
	}

	public function updateBesoin($id): void {
		$pdo = $this->app->db();
		$model = new BesoinModel($pdo);

		$idVille = $this->app->request()->data->id_ville;
		$idArticle = $this->app->request()->data->id_article;
		$quantite = $this->app->request()->data->quantite;

		$success = $model->updateBesoin($id, $idVille, $idArticle, $quantite);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Besoin updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update besoin'], 500);
		}
	}

	public function deleteBesoin($id): void {
		$pdo = $this->app->db();
		$model = new BesoinModel($pdo);

		$success = $model->deleteBesoin($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Besoin deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete besoin'], 500);
		}
	}
}
