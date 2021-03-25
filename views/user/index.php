<?php

use yii\helpers\Html;
use yii\grid\GridView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $searchModel app\models\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Users';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Create User', ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'email:email',
            'birthday',
            [
                'attribute' => 'status',
                'value' => function ($model) {
                    if ($model->status === User::STATUS_ACTIVE) {
                        return 'Active';
                    }
                    if ($model->status === User::STATUS_INACTIVE) {
                        return 'Inactive';
                    }
                }
            ],
            [
                'attribute' => 'registration_method',
                'value' => function ($model) {
                    if ($model->registration_method === User::REGISTRATION_BY_CLI) {
                        return 'CLI';
                    }
                    if ($model->registration_method === User::REGISTRATION_BY_API) {
                        return 'API';
                    }
                    if ($model->registration_method === User::REGISTRATION_BY_UI) {
                        return 'UI';
                    }
                }
            ],
            //'createad_at',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>


</div>
