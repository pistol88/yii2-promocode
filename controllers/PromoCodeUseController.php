<?php
namespace pistol88\promocode\controllers;

use yii;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PromoCodeUseController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
            ],
        ];
    }

    public function actionEnter()
    {
        try {
            $promocode = yii::$app->request->post('promocode');
            
            if(yii::$app->request->post('clear')) {
                yii::$app->promocode->clear();
                $persent = false;
                $message = 'Промокод удален!';
            } else {
                yii::$app->promocode->enter($promocode);
                $persent = yii::$app->promocode->get()->promocode->discount;
                $message = 'Промокод применен, скидка '.$persent.'%';
            }
            
            if(yii::$app->cart) {
                $newCost = yii::$app->cart->costFormatted;
            }
            else {
                $newCost = null;
            }
            
            
            
            return json_encode(['result' => 'success', 'newCost' => $newCost, 'message' => $message]);
        }
        catch(\Exception $e) {
            return json_encode(['result' => 'fail', 'message' => $e->getMessage()]);
        }
    }
}
