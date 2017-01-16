<?php
namespace pistol88\promocode\controllers;

use Yii;
use pistol88\promocode\models\PromoCode;
use pistol88\promocode\models\PromoCodeSearch;
use pistol88\promocode\models\PromocodeToItem;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

class PromoCodeController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => $this->module->adminRoles,
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $searchModel = new PromoCodeSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    public function actionCreate()
    {


        $model = new PromoCode();
        $targetModelList = [];


        if ($this->module->targetModelList) {
            $targetModelList = $this->module->targetModelList;
        }
        if ($model->load(Yii::$app->request->post())) {

            $model->date_elapsed = date('Y-m-d H:i:s',strtotime($model->date_elapsed));

            $model->save();

            $targets = Yii::$app->request->post();
            if (isset($targets['targetModels']) && $targets['targetModels'] != null) {
                $this->savePromocodeToModel($targets['targetModels'],$model->id);
            }
            if($backUrl = yii::$app->request->post('backUrl')) {
                return $this->redirect($backUrl);
            } else {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('create', [
                'model' => $model,
                'targetModelList' => $targetModelList,
            ]);
        }
    }

    public function actionCreateWidget()
    {
        $model = new PromoCode();

        $json = [];

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $json['result'] = 'success';
            $json['promocode'] = $model->code;
        } else {
            $json['result'] = 'fail';
            $json['errors'] = current($model->getFirstErrors());
        }

        return json_encode($json);
    }

    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $promoCodeItems = PromocodeToItem::find()->where(['promocode_id' => $id])->all();
        $targetModelList = [];
        $items = [];
        $usesModel = yii::$app->getModule('promocode')->usesModel;
        $usesModelMap = ArrayHelper::map($usesModel::find()->all(),'id','username');
        


        foreach ($promoCodeItems as $promoCodeItem) {
            $item_model = $promoCodeItem->item_model;
            $item = $item_model::findOne($promoCodeItem->item_id);
            $items[] = ['['.$promoCodeItem->item_model.']['.$item->id.']' =>
                [
                    'name' => $item->name,
                    'model' => $promoCodeItem->item_model,
                    'model_id' => $promoCodeItem->item_id,
                ]
            ];
        }

        if ($this->module->targetModelList) {
            $targetModelList = $this->module->targetModelList;
        }
        if ($model->load(Yii::$app->request->post())) {

            $targets = Yii::$app->request->post();
            $model->date_elapsed = date('Y-m-d H:i:s',strtotime($model->date_elapsed));
            $model->save();

            if (isset($targets['targetModels']) && $targets['targetModels'] != null) {
                $this->savePromocodeToModel($targets['targetModels'],$model->id);
            }

            if($backUrl = yii::$app->request->post('backUrl')) {
                return $this->redirect($backUrl);
            } else {
                return $this->redirect(['index']);
            }
        } else {
            return $this->render('update', [
                'model' => $model,
                'targetModelList' => $targetModelList,
                'items' => $items,
                'usesModelMap' => $usesModelMap,
            ]);
        }
    }

    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    protected function findModel($id)
    {
        if (($model = PromoCode::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    protected function savePromocodeToModel($productModels,$promoCodeId,$savedItems = null){
        if ($productModels){
            foreach ($productModels as $productModel => $modelItems) {
                foreach ($modelItems as $id => $value) {
                    $model = PromocodeToItem::find()->where([
                        'promocode_id' => $promoCodeId,
                        'item_model' => $productModel,
                        'item_id' =>$id,
                    ])->one();
                    if (!$model) {
                        $model = new PromocodeToItem();
                        $model->promocode_id = $promoCodeId;
                        $model->item_model = $productModel;
                        $model->item_id = $id;
                        if ($model->validate() && $model->save()){
                            // do nothing
                        } else var_dump($model->getErrors());
                    }
                } //model instance foreach
            } //model namespace foreach
        } //savePromocodeToModel
    }

    public function actionStatistic()
    {

        $orderModel = yii::$app->getModule('promocode')->orderModel;
        $orders = $orderModel::find();
        $ordersCount = $orders->count();

        $timeFrom01 = mktime(0, 0, 0, date("m")  , 1        ,   date("Y"));
        $timeLast30 = mktime(0, 0, 0, date("m")-1, date("d"),   date("Y"));
        $timeLast90 = mktime(0, 0, 0, date("m")-3, date("d"),   date("Y"));

        $data = [];
        $promoCodes = [];

        foreach ($orders->groupBy('promocode')->all() as $key => $value) {
            array_push($promoCodes,$value->promocode);
        }

        $orders = $orderModel::find();

        foreach ($promoCodes as $key => $promocode) {

            $name = $promocode ? $promocode : "Без промокода";
            $promocodeOrders = $orders->where(["promocode"=>$promocode]);

            $poClone = clone $promocodeOrders;

            $allTime = $poClone->count();

            $time = date('Y-m-d H:i:s',$timeFrom01);
            $poClone = clone $promocodeOrders;
            $thisMonth = $poClone->andWhere("date > '$time'")->count();

            $time = date('Y-m-d H:i:s',$timeLast30);
            $poClone = clone $promocodeOrders;
            $lastMonth = $poClone->andWhere("date > '$time'")->count();

            $poClone = clone $promocodeOrders;
            $avgSum = round($poClone->sum('cost') / ($allTime ? $allTime : 1),2);

//            $time = date('Y-m-d H:i:s',$timeLast90);
            $ordersClone = clone $orders;
            $percent = round(
                $ordersClone->where(["promocode"=>$promocode])
//                    ->andWhere("date > '$time'")
                    ->count()/$ordersCount * 100,2);

            $data[] = [
                'name' => $name,
                'thisMonth' => $thisMonth,
                'lastMonth' => $lastMonth,
                'allTime' => $allTime,
                'avgSum' => $avgSum,
                'percent' => $percent
            ];
        }

        return $this->render('stats',[
            'promocodes'=>$data
        ]);
    }
    
    public function actionPeriodStatistic()
    {
        
    }


    public function actionAjaxDeleteTargetItem()
    {
        $target = Yii::$app->request->post();

        $model = PromocodeToItem::find()->where([
            'promocode_id' => $target['data']['promocodeId'],
            'item_model' => $target['data']['targetModel'],
            'item_id' => $target['data']['targetModelId'],
        ])->one();
        if ($model) {
            if ($model->delete()) {
                \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
                return [
                    'status' => 'success',
                ];
            }   else return [
                'status' => 'error',
            ];
        } else
            return [
                'status' => 'success',
            ];

    }

}
