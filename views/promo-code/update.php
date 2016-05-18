<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */

$this->title = 'Изменить промокод: ' . ' ' . $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Промокод', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->title, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Изменить';
?>
<div class="promo-codes-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
