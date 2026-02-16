<?php

namespace app\controllers;

use app\models\ArticleModel;
use app\models\CategorieBesoinModel;
use Flight;
use flight\Engine;

class ArticleController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllArticles(): array {
		$pdo = $this->app->db();
		$model = new ArticleModel($pdo);
		return $model->getAllArticles();
	}

	public function renderArticleList(): void {
		$articles = $this->getAllArticles();

		$this->app->render('article', [
			'articles' => $articles,
			'currentPage' => 'articles',
		]);
	}

	public function renderArticlesByCategorie(): void {
		$pdo = $this->app->db();
		$articles = $this->getAllArticles();

		$categoryModel = new CategorieBesoinModel($pdo);
		$categories = $categoryModel->getAllCategories();

		$articlesByCategorie = [];
		foreach ($articles as $article) {
			$catId = $article['id_categorie'];
			if (!isset($articlesByCategorie[$catId])) {
				$articlesByCategorie[$catId] = [];
			}
			$articlesByCategorie[$catId][] = $article;
		}

		$this->app->render('article', [
			'articles' => $articles,
			'categories' => $categories,
			'articlesByCategorie' => $articlesByCategorie,
			'currentPage' => 'articles',
		]);
	}

	public function renderArticleDetail($id): void {
		$pdo = $this->app->db();
		$model = new ArticleModel($pdo);
		$article = $model->getArticleById($id);

		if ($article) {
			$this->app->render('article_detail', [
				'article' => $article,
				'currentPage' => 'articles',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$articleModel = new ArticleModel($pdo);
		$categoryModel = new CategorieBesoinModel($pdo);
		
		$article = $articleModel->getArticleById($id);
		$categories = $categoryModel->getAllCategories();

		if ($article) {
			$this->app->render('article-form', [
				'article' => $article,
				'categories' => $categories,
				'currentPage' => 'articles',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$pdo = $this->app->db();
		$categoryModel = new CategorieBesoinModel($pdo);
		$categories = $categoryModel->getAllCategories();

		$this->app->render('article-add', [
			'categories' => $categories,
			'currentPage' => 'articles',
		]);
	}

	public function createArticle(): void {
		$pdo = $this->app->db();
		$model = new ArticleModel($pdo);

		$name = $this->app->request()->data->name;
		$prix = $this->app->request()->data->prix;
		$idCategorie = $this->app->request()->data->id_categorie;

		$articleId = $model->createArticle($name, $prix, $idCategorie);

		if ($articleId) {
			$this->app->json(['success' => true, 'message' => 'Article created', 'articleId' => $articleId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create article'], 500);
		}
	}

	public function updateArticle($id): void {
		$pdo = $this->app->db();
		$model = new ArticleModel($pdo);

		$name = $this->app->request()->data->name;
		$prix = $this->app->request()->data->prix;
		$idCategorie = $this->app->request()->data->id_categorie;

		$success = $model->updateArticle($id, $name, $prix, $idCategorie);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Article updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update article'], 500);
		}
	}

	public function deleteArticle($id): void {
		$pdo = $this->app->db();
		$model = new ArticleModel($pdo);

		$success = $model->deleteArticle($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Article deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete article'], 500);
		}
	}
}
