<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\ProgrammingLanguage */

$this->title = 'Create Programming Language';
$this->params['breadcrumbs'][] = ['label' => 'Programming Languages', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="programming-language-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
