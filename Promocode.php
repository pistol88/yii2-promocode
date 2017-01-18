<?php
namespace pistol88\promocode;

use yii\base\Component;
use pistol88\promocode\models\PromoCode as PromoCodeModel;
use pistol88\promocode\models\PromoCodeUse;
use pistol88\promocode\models\PromoCodeUsed;
use pistol88\promocode\events\PromocodeEvent;
use yii;

class Promocode extends Component
{
    const EVENT_PROMOCODE_ENTER = 'promocode_enter';
    
    public $promocode = NULL;
    public $promocodeUse = NULL;
    
    private $userId;

    public function init()
    {
        $this->promocode = new PromoCodeModel;
        $this->promocodeUse = new PromoCodeUse;
        
        $session = yii::$app->session;
        
        if(!$userId = yii::$app->user->id) {
            if (!$userId = $session->get('tmp_user_id')) {
                $userId = md5(time() . '-' . yii::$app->request->userIP . Yii::$app->request->absoluteUrl);
                $session->set('tmp_user_id', $userId);
            }
        }
        
        $this->userId = $userId;
        
        parent::init();
    }
    
    public function enter($promocodeId)
    {
        $promocode = $this->promocode;
        
        if(!$promocodeModel = $promocode::findOne(['code' => $promocodeId, 'status' => 1])) {
            throw new \Exception('Промокод не найден');
        }

        if ($promocode::findOne(['code' => $promocodeId])->status == 0) {
            throw new \Exception('Промокод не действителен');
        }

        if (!$this->checkPromoCodeStatus($promocodeId)) {
            throw new \Exception('Промокод не действителен');
        }

        $data = [];
        $data['PromoCodeUse']['promocode_id'] = $promocodeModel->id;

        $date = date('Y-m-d H:i:s');
        
        $data['PromoCodeUse']['date'] = $date;
        $data['PromoCodeUse']['user_id'] = $this->userId;
        
        if ($this->promocodeUse->load($data) && $this->promocodeUse->validate()) {
            $promocodeEvent = new PromocodeEvent(['code' => $promocodeId, 'data' => $data['PromoCodeUse']]);
            $this->trigger(self::EVENT_PROMOCODE_ENTER, $promocodeEvent);

            $this->clear();
            $this->promocodeUse->save();
            return true;
        } else {
            return false;
        }
    }
    
    public function getCode()
    {
        if(!$this->has()) {
            return false;
        }

        return $this->get()->promocode->code;
    }
    
    
    public function find()
    {
        $promocodeUse = $this->promocodeUse;
        return $promocodeUse::find()->where(['user_id' => $this->userId]);
    }

    public function get()
    {
        return $this->find()->one();
    }
    
    public function has()
    {
        if($this->get()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function clear()
    {
        if($code = $this->get()) {
            return $code->delete();
        } else {
            return false;
        }
    }
    
    public function getTargetModels()
    {
        if($code = $this->get()) {
            return $code->promocode->targetModels;
        } else {
            return false;
        }
    }
    
    public function checkExists($code)
    {
        return PromoCodeModel::findOne(['code' => $code]);
    }

    public function setPromoCodeStatus($promoCode,$status)
    {
        $promoCode->status = $status;

        return $promoCode->save(false);
    }

    public function checkPromoCodeStatus($code)
    {

        $promoCode = $this->checkExists($code);

        if (empty($promoCode->date_elapsed)) {
            return true;
        }

        if (strtotime($promoCode->date_elapsed) < strtotime(date('Y:m:d H:m:s'))) {
            $this->setPromoCodeStatus($promoCode,0);
            return false;
        } else {
            return true;
        }
    }

    public function setPromoCodeAmount($promoCode)
    {
        if ($promoCode->amount == null) {
            return true;
        }

        if ($promoCode->amount > 0) {
            $promoCode->amount = $promoCode->amount-1;
        }
        if ($promoCode->amount <= 0) {
            $this->setPromoCodeStatus($promoCode,0);
        }
        return $promoCode->save();
    }

    public function setPromoCodeUse($promoCode,$orderId)
    {
        $model = new PromoCodeUsed();
        $model->promocode_id = $promoCode->id;
        $model->date = date('Y-m-d H:i:s');
        if  (!empty(yii::$app->user->id)) {
            $model->user = yii::$app->user->id;
        }
        $model->order_id = $orderId;
        if ($model->validate()) {
            $this->setPromoCodeAmount($promoCode);
            $model->save();
            return true;
        } else {
            return $model->getErrors();
        }
    }
}
