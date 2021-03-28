<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user app\models\User */

?>
<div class="email">
    <p>Hello <?= Html::encode($user->getFullName()) ?>,</p>

    <p>Your account is now active!</p>
</div>
