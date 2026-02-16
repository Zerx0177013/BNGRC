<?php

namespace app\controllers;

use app\models\UserModel;
use flight\Engine;

class UserController
{

	protected Engine $app;

	public function __construct($app)
	{
		$this->app = $app;
	}

	public function getNumberOfUsers()
    {
        $db = $this->app->db() ;
        $model =new UserModel($db);
        $count = $model->getNumberOfUsers();
        return $count ?: 0;
    }

}