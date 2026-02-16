<?php

namespace app\controllers;

use app\models\DonModel;
use app\models\ArticleModel;
use app\models\BesoinModel;
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
		$this->renderAddForm();
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
		$this->app->notFound();
	}

	public function renderAddForm(): void {
		$pdo = $this->app->db();
		$articleModel = new ArticleModel($pdo);
		$besoinModel = new BesoinModel($pdo);
		
		$articles = $articleModel->getArticlesWithBesoinsEnAttente();
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

		$data = $this->app->request()->data;
		$idArticle = $data->id_article ?? null;
		$quantite = $data->quantite ?? null;
		$dateDon = !empty($data->date_don) ? $data->date_don : null;

		if (!$idArticle || !$quantite) {
			$this->app->json(['success' => false, 'message' => 'Article et quantité sont requis'], 400);
			return;
		}

		$donId = $model->createDon($idArticle, $quantite, $dateDon);

		if ($donId) {
			$this->app->json(['success' => true, 'message' => 'Don créé avec succès', 'donId' => $donId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la création du don'], 500);
		}
	}

	public function updateDon($id): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);

		$data = $this->app->request()->data;
		$idArticle = $data->id_article ?? null;
		$quantite = $data->quantite ?? null;

		if (!$idArticle || !$quantite) {
			$this->app->json(['success' => false, 'message' => 'Article et quantité sont requis'], 400);
			return;
		}

		$success = $model->updateDon($id, $idArticle, $quantite);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Don modifié avec succès']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la modification du don'], 500);
		}
	}

	public function deleteDon($id): void {
		$pdo = $this->app->db();
		$model = new DonModel($pdo);

		$success = $model->deleteDon($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Don supprimé avec succès']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la suppression du don'], 500);
		}
	}
}
