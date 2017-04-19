<?php
namespace pistol88\promocode;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $fields = [];
    public $targetModelList = null;
    public $clientsModel = null;
    public $orderModel = null;
    public $informer = 'pistol88\cart\widgets\CartInformer';
    public $informerSettings = [];
    
    public function init()
    {
        parent::init();
    }
}
