<?php

namespace app\controllers;

use app\models\CategorieBesoinModel;
use Flight;
use flight\Engine;

class CategorieBesoinController {

	protected Engine $app;

	public function __construct($app) {
		$this->app = $app;
	}

	public function getAllCategories(): array {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);
		return $model->getAllCategories();
	}

	public function renderCategoryList(): void {
		$categories = $this->getAllCategories();

		$this->app->render('categories', [
			'categories' => $categories,
			'currentPage' => 'categories',
		]);
	}

	public function renderCategoryDetail($id): void {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);
		$category = $model->getCategoryById($id);

		if ($category) {
			$this->app->render('category_detail', [
				'category' => $category,
				'currentPage' => 'categories',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderEditForm($id): void {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);
		$category = $model->getCategoryById($id);

		if ($category) {
			$this->app->render('category-form', [
				'category' => $category,
				'currentPage' => 'categories',
			]);
		} else {
			$this->app->notFound();
		}
	}

	public function renderAddForm(): void {
		$this->app->render('category-add', [
			'currentPage' => 'categories',
		]);
	}

	public function renderItemForm(): void {
		$categories = $this->getAllCategories();

		$this->app->render('item-new', [
			'categories' => $categories,
		]);
	}

	public function createCategory(): void {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);

		$name = $this->app->request()->data->name;

		$categoryId = $model->createCategory($name);

		if ($categoryId) {
			$this->app->json(['success' => true, 'message' => 'Category created', 'categoryId' => $categoryId], 201);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to create category'], 500);
		}
	}

	public function updateCategory($id): void {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);

		$name = $this->app->request()->data->name;

		$success = $model->updateCategory($id, $name);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Category updated']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to update category'], 500);
		}
	}

	public function deleteCategory($id): void {
		$pdo = $this->app->db();
		$model = new CategorieBesoinModel($pdo);

		$success = $model->deleteCategory($id);

		if ($success) {
			$this->app->json(['success' => true, 'message' => 'Category deleted']);
		} else {
			$this->app->json(['success' => false, 'message' => 'Failed to delete category'], 500);
		}
	}
}
