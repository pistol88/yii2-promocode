<?php
namespace pistol88\promocode\widgets;

use yii\helpers\Html;
use yii\helpers\Url;
use pistol88\promocode\models\PromoCodeUse;
use yii;

class Enter extends \yii\base\Widget
{

    public function init()
    {
        parent::init();

        \pistol88\promocode\assets\WidgetAsset::register($this->getView());
    }

    public function run()
    {
        $model = new PromoCodeUse;
        
        return $this->render('enter_form', ['model' => $model]);
    }
}