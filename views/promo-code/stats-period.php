<?php

use nex\datepicker\DatePicker;

$this->title = 'Статистика по промокодам за период';
$this->params['breadcrumbs'][] = ['label' => 'Промокод', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'Статистика по промокодам за период';
?>
<div class="promo-codes-stats">
    <div class="row session-finder">
        <div class="col-md-6">
            <form action="" method="get">
                <p>Выберите период:</p>
                <div class="row">
                    <div class="col-md-4">
                        <?= DatePicker::widget([
                            'name' => 'dateStart',
                            'addon' => false,
                            'value' => date('d.m.Y', strtotime($dateStart)),
                            'size' => 'sm',
                            'language' => 'ru',
                            'options' => [
                                'onchange' => '',
                            ],
                            'placeholder' => 'На дату...',
                            'clientOptions' => [
                                'format' => 'L',
                                'minDate' => '2015-01-01',
                                'maxDate' => date('Y-m-d'),
                            ],
                            'dropdownItems' => [
                                ['label' => 'Yesterday', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('-1 day')],
                                ['label' => 'Tomorrow', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('+1 day')],
                                ['label' => 'Some value', 'url' => '#', 'value' => 'Special value'],
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-4">
                        <?= DatePicker::widget([
                            'name' => 'dateStop',
                            'addon' => false,
                            'value' => date('d.m.Y', strtotime($dateStop)),
                            'size' => 'sm',
                            'language' => 'ru',
                            'options' => [
                                'onchange' => '',
                            ],
                            'placeholder' => 'На дату...',
                            'clientOptions' => [
                                'format' => 'L',
                                'minDate' => '2015-01-01',
                                'maxDate' => date('Y-m-d'),
                            ],
                            'dropdownItems' => [
                                ['label' => 'Yesterday', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('-1 day')],
                                ['label' => 'Tomorrow', 'url' => '#', 'value' => \Yii::$app->formatter->asDate('+1 day')],
                                ['label' => 'Some value', 'url' => '#', 'value' => 'Special value'],
                            ],
                        ]);?>
                    </div>
                    <div class="col-md-4">
                        <input type="submit" value="Применить" class="btn btn-submit" />
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="row well">
        <h1>Статистика промокодов за период:
            <?=date('d.m.Y', strtotime($dateStart));?> - <?=date('d.m.Y', strtotime($dateStop));?>
        </h1>
    </div>
    <div class="row">
        <table class="table table-bordered table-hover table-responsive">
            <tbody>
            <tr>
                <th>Название</th>
                <th>Количество применений</th>
                <th>Средняя стоимость</th>
                <th>Доля промокода в заказах</th>
            </tr>
            <?php if (isset($promocodes)){ ?>
                <?php foreach ($promocodes as $key => $promocode){ ?>
                    <tr>
                        <td><?=$promocode['name']?></td>
                        <td><?=$promocode['stats']?></td>
                        <td><?=$promocode['avgSum']?></td>
                        <td><?=$promocode['percent']?> %</td>
                    </tr>
                <?php   } ?>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>
