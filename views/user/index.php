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
//        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            'first_name',
            'last_name',
            'email:email',
            'birthday',
            'userAge',
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
                'label' => 'Till 18th birthday',
                'value' => function ($model) {
                    /** @var $model User */
                    if ($model->isUnder18()) {
                        $birthday18 = (new DateTime($model->birthday))->add(new DateInterval('P18Y'))->add(new DateInterval('P1D'));
                        $till18Birthday = $birthday18->diff(new DateTime());
                        return $till18Birthday->y . ' years, ' . $till18Birthday->m . ' months, ' . $till18Birthday->d . ' days';
                    }
                    return '-';
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
