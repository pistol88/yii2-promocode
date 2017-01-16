<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\bootstrap\Modal;
use kartik\date\DatePicker;
use yii\helpers\Url;
use pistol88\promocode\assets\Asset;
Asset::register($this);


/* @var $this yii\web\View */
/* @var $model common\models\PromoCodes */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="promo-codes-form">
    <div class="container">
            <?php $form = ActiveForm::begin(); ?>
            <input type="hidden" name="backUrl" value="<?=Html::encode(yii::$app->request->referrer);?>" />
        <div class="row">
            <div class="col-md-6">
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <?= $form->field($model, 'description')->textarea(); ?>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-4">
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
                            $date = '';
                            $params = ['value' => $code];
                        } else {
                            $params = [];
                            $date = date('d.m.Y',strtotime($model->date_elapsed));
                        }

                        echo $form->field($model, 'code')->textInput($params) ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'discount')->textInput() ?>
                    </div>
                    <div class="col-md-4">
                        <?= $form->field($model, 'status')->dropDownList([
                            '1' => 'Активен',
                            '0' => 'Отключен',
                        ]);
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'date_elapsed')->widget(DatePicker::classname(), [
                            'language' => 'ru',
                            'type' => DatePicker::TYPE_COMPONENT_PREPEND,
                            'options' => [
                                'placeholder' => 'Дата истечения промокода',
                                'value' =>  $date,
                            ],
                            'removeButton' => false,
                            'pluginOptions' => [
                                'autoclose'=>true,
                                'format' => 'd.m.yyyy',
                            ],
                        ])->label('Дата истечения промокода')->hint('Выберите дату истечения срока действия промокода')
                        ?>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'amount')->label('Количество использований')->hint('Здесь задается количество использований промокода')
                        ?>
                    </div>
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-success', 'data-role' => 'sendForm']) ?>
                </div>
            </div>
            <?php if($targetModelList) { ?>
                <div class="col-md-6 promocode-right-column">
                    <h3>Прикрепить только к:</h3>
                    <div class="row">
                        <div class="col-md-4">
                            <?php foreach($targetModelList as $modelName => $modelType){   ?>
                                <?php
                                Modal::begin([
                                    'header' => '<h2>Привязать промокод к: '.$modelName.'</h2>',
                                    'size' => 'modal-lg',
                                    'toggleButton' => [
                                        'tag' => 'button',
                                        'class' => 'btn btn-sm btn-block btn-primary',
                                        'label' => $modelName . ' <i class="glyphicon glyphicon-plus"></i>',
                                        'data-model' => $modelType['model'],
                                    ]
                                ]);
                                ?>
                                <iframe src="/promocode/tools/product-window?targetModel=<?= $modelName ?>" frameborder="0" style="width: 100%; height: 400px;">
                                </iframe>
                                <?php
                                Modal::end();
                                ?>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <table class="table table-bordered">
                            <tbody data-role="model-list" id="modelList">
                            <?php
                            if ($items) {
                                foreach ($items as $item) {
                                    foreach ($item as $item_id => $item_attr) {
                                        ?>
                                        <tr data-role="item">
                                            <td><label>
                                                    <?=$item_attr['name']?>   
                                                </label>
                                                <input type="hidden" data-role="product-model" name="targetModels<?=$item_id?>"
                                                       data-name="<?= str_replace(['[',']','\\'],"",$item_id)?>"/>
                                            </td>
                                            <td>
                                                <span data-href="ajax-delete-target-item" class="btn glyphicon glyphicon-remove" style="color: red;"        data-role="remove-target-item"
                                                      data-target-model="<?=$item_attr['model'] ?>"
                                                      data-target-model-id="<?=$item_attr['model_id'] ?>"></span>
                                            </td>

                                        </tr>
                                    <?php    }
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>

            <?php ActiveForm::end(); ?>
        </div>
        <div>
            <?php if ($model->getTransactions()->all()) { ?>
                <input type="button" class="btn btn-primary" data-toggle="collapse" data-target="#toggleHistory" value="История использований">
                <br>
                <div id="toggleHistory" class="collapse">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Дата использования</th>
                                <th>Номер заказа</th>
                                <th>Кем использован</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($model->getTransactions()->orderBy(['date' => SORT_DESC])->all() as $promoCodeUse) {?>
                                <tr>
                                    <td><?= date('d.m.Y H:i:s',strtotime($promoCodeUse->date)) ?></td>
                                    <td><a href="<?=Url::to(['/order/order/view', 'id' => $promoCodeUse->order_id]) ?>"><?= $promoCodeUse->order_id ?></a></td>
                                    <td><?= ($promoCodeUse->user) ? $usesModelMap[$promoCodeUse->user] : '' ?></td>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
</div>
