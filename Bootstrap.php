<?php
namespace pistol88\promocode;

use yii\base\BootstrapInterface;
use yii;

class Bootstrap implements BootstrapInterface
{
    public function bootstrap($app)
    {
        if(!$app->has('promocode')) {
            $app->set('promocode', ['class' => 'pistol88\promocode\Promocode']);
        }
        
        return true;
    }
}