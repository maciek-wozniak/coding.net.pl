<?php

namespace app\modules\api\controllers;

use app\models\User;
use yii\rest\ActiveController;

/**
 * Default controller for the `api` module
 */
class UserController extends ActiveController
{
    public $modelClass = 'app\models\User';
    public $createScenario = User::SCENARIO_REGISTERED_BY_API;
}
