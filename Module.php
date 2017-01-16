<?php
namespace pistol88\promocode;

class Module extends \yii\base\Module
{
    public $adminRoles = ['admin', 'superadmin'];
    public $fields = [];
    public $targetModelList = null;
    public $usesModel = null;
    public $orderModel = null;
    
    public function init()
    {
        parent::init();
    }
}
