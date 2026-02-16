<?php

namespace app\controllers;

use app\models\AchatModel;
use app\models\VilleModel;
use Flight;
use flight\Engine;

class AchatController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function renderBesoinsRestants() {
		$pdo = $this->app->db();
		$model = new AchatModel($pdo);
		$villeModel = new VilleModel($pdo);

		$besoins = $model->getBesoinsRestantsNonArgent();
		$donsArgent = $model->getDonsArgentDisponibles();
		$config = $model->getConfig();
		$villes = $villeModel->getAllVilles();

		$this->app->render('achats-besoins', [
			'besoins' => $besoins,
			'donsArgent' => $donsArgent,
			'config' => $config,
			'villes' => $villes,
			'currentPage' => 'achats',
		]);
	}

	public function renderAchatList() {
		$pdo = $this->app->db();
		$model = new AchatModel($pdo);
		$villeModel = new VilleModel($pdo);

		$idVille = $this->app->request()->query->id_ville ?? null;

		if ($idVille) {
			$achats = $model->getAchatsByVille($idVille);
		} else {
			$achats = $model->getAllAchats();
		}

		$villes = $villeModel->getAllVilles();

		$this->app->render('achats-liste', [
			'achats' => $achats,
			'villes' => $villes,
			'selectedVille' => $idVille,
			'currentPage' => 'achats',
		]);
	}

	public function getAchatsJson() {
		$pdo = $this->app->db();
		$model = new AchatModel($pdo);

		$idVille = $this->app->request()->query->id_ville ?? null;

		if ($idVille) {
			$achats = $model->getAchatsByVille($idVille);
		} else {
			$achats = $model->getAllAchats();
		}

		$this->app->json(['success' => true, 'achats' => $achats]);
	}

	public function createAchat() {
		$pdo = $this->app->db();
		$model = new AchatModel($pdo);

		$data = $this->app->request()->data;
		$idBesoin = $data->id_besoin ?? null;
		$idDon = $data->id_don ?? null;
		$quantite = $data->quantite ?? null;

		if (!$idBesoin || !$idDon || !$quantite) {
			$this->app->json(['success' => false, 'message' => 'Besoin, don et quantité sont requis.'], 400);
			return;
		}

		$config = $model->getConfig();
		if (!$config) {
			$this->app->json(['success' => false, 'message' => 'Configuration des frais introuvable.'], 500);
			return;
		}

		$idConfig = $config['id_config'];
		$fraisPercent = (float) $config['valeur'];

		$idArticle = $model->getArticleIdByBesoin($idBesoin);
		if (!$idArticle) {
			$this->app->json(['success' => false, 'message' => 'Besoin introuvable.'], 400);
			return;
		}

		$prixUnitaire = $model->getPrixUnitaireByBesoin($idBesoin);
		if (!$prixUnitaire) {
			$this->app->json(['success' => false, 'message' => 'Article du besoin introuvable.'], 400);
			return;
		}

		$montantHT = $quantite * $prixUnitaire;
		$montantTotal = $montantHT * (1 + $fraisPercent / 100);

		$donTrouve = $model->getDonArgentById($idDon);
		if (!$donTrouve) {
			$this->app->json(['success' => false, 'message' => 'Don en argent introuvable ou solde insuffisant.'], 400);
			return;
		}

		if ($montantTotal > (float) $donTrouve['montant_restant']) {
			$this->app->json([
				'success' => false,
				'message' => 'Solde insuffisant. Montant requis: ' . number_format($montantTotal, 2, ',', ' ') . ' Ar, disponible: ' . number_format($donTrouve['montant_restant'], 2, ',', ' ') . ' Ar.'
			], 400);
			return;
		}

		try {
			$achatId = $model->createAchat($idBesoin, $idDon, $idConfig, $quantite, $prixUnitaire, $montantTotal);
			$this->app->json([
				'success' => true,
				'message' => 'Achat effectué avec succès.',
				'achatId' => $achatId,
				'montant_total' => $montantTotal
			], 201);
		} catch (\Exception $e) {
			$this->app->json(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()], 500);
		}
	}

	public function deleteAchat($id) {
		$pdo = $this->app->db();
		$model = new AchatModel($pdo);

		$success = $model->deleteAchat($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Achat supprimé avec succès.']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Erreur lors de la suppression.'], 500);
		}
	}
}
