<?php

namespace app\controllers;

use app\models\DonModel;
use app\models\ArticleModel;
use Flight;
use flight\Engine;

class DonController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllDons(): array {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);
		return $model->getAllDons();
	}

	public function renderDonList(): void {
		$dons = $this->getAllDons();

		$this->app->render('dons', [
			'dons' => $dons,
			'currentPage' => 'dons',
		]);
	}

	public function renderDonDetail($id): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);
		$don = $model->getDonById($id);

		if ($don) {
			$this->app->render('don_detail', [
				'don' => $don,
				'currentPage' => 'dons',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$donModel = new DonModel($pdo);
		$articleModel = new ArticleModel($pdo);
		
		$don = $donModel->getDonById($id);
		$articles = $articleModel->getAllArticles();

		if ($don) {
			$this->app->render('don-form', [
				'don' => $don,
				'articles' => $articles,
				'currentPage' => 'dons',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$pdo = $this->app->db();
		$articleModel = new ArticleModel($pdo);
		$besoinModel = new \app\models\BesoinModel($pdo);
		
		$articles = $articleModel->getAllArticles();
		$besoins = $besoinModel->getAllBesoins();

		$this->app->render('don-add', [
			'articles' => $articles,
			'besoins' => $besoins,
			'currentPage' => 'dons',
		]);
	}

	public function createDon(): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);

		$idArticle = $this->app->request()->data->id_article;
		$quantite = $this->app->request()->data->quantite;
		$dateDon = $this->app->request()->data->date_don ?? null;

		$donId = $model->createDon($idArticle, $quantite, $dateDon);

		if ($donId) {
			$this->app->json(['success' => true, 'message' => 'Don created', 'donId' => $donId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create don'], 500);
		}
	}

	public function updateDon($id): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);

		$idArticle = $this->app->request()->data->id_article;
		$quantite = $this->app->request()->data->quantite;

		$success = $model->updateDon($id, $idArticle, $quantite);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Don updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update don'], 500);
		}
	}

	public function deleteDon($id): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);

		$success = $model->deleteDon($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Don deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete don'], 500);
		}
	}
}
