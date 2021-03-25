<?php

use app\models\User;
use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\User */

$this->title = $model->getFullName();
$this->params['breadcrumbs'][] = ['label' => 'Users', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="user-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'first_name',
            'last_name',
            'pesel',
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
            [
                'label' => 'Programming languages',
                'value' => function ($model) {
                    $result = '';
                    foreach ($model->programmingLanguages as $language) {
                        $result .= $language->name . "<br/>";
                    }
                    return $result;
                },
                'format' => 'html'
            ],
            'created_at',
        ],
    ]) ?>

</div>
