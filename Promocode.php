<?php
namespace pistol88\promocode;

use yii\base\Component;
use pistol88\promocode\models\PromoCode as PromoCodeModel;
use pistol88\promocode\models\PromoCodeUse;
use yii\web\Session;
use yii;

class Promocode extends Component
{
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
        
        if(!$promocodeModel = $promocode::findOne(['code' => $promocodeId])) {
            throw new \Exception('Промокод не найден');
        }

        $data = [];
        $data['PromoCodeUse']['promocode_id'] = $promocodeModel->id;

        $date = date('Y-m-d H:i:s');
        
        $data['PromoCodeUse']['date'] = $date;
        $data['PromoCodeUse']['user_id'] = $this->userId;
        
        if ($this->promocodeUse->load($data) && $this->promocodeUse->validate()) {
            $this->clear();
            $this->promocodeUse->save();
            return true;
        }
        else {
            return false;
        }
    }
    
    public function getCode() {
        if(!$this->has()) {
            return false;
        }

        return $this->get()->promocode->code;
    }
    
    public function find() {
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
    
    public function checkExists($code)
    {
        return PromoCodeModel::findOne(['code' => $code]);
    }
}
