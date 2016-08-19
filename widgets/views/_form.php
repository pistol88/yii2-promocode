<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

?>

<div class="promo-codes-form">

    <?php $form = ActiveForm::begin(['action' => ['/promocode/promo-code/create-widget']]); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'description')->textarea(); ?>
        </div>
    </div>

    <div class="row">
        <div class="col-md-2">
            <?php
                function KeyPromoGen(){
                    $key = md5(time());
                    $new_key = '';

                    for($i=1; $i <= 4; $i ++ ){
                        $new_key .= $key[$i];
                    }
                    return strtoupper($new_key);
                }
                if($model->isNewRecord) {
                    $code = KeyPromoGen();
                    $params = ['value' => $code];
                } else {
                    $params = [];
                }

                echo $form->field($model, 'code')->textInput($params) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'discount')->textInput() ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'status')->dropDownList([
                '0' => 'Отключен',
                '1' => 'Активен',
            ]);
            ?>
        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
