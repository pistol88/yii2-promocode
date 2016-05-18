<?php
namespace pistol88\promocode\behaviors;

use yii;
use yii\base\Behavior;
use pistol88\cart\Cart;

class Discount extends Behavior
{

    public function events()
    {
        return [
            Cart::EVENT_CART_COST => 'doDiscount'
        ];
    }

    public function doDiscount($event)
    {
        if(yii::$app->promocode->has()) {
            $persent = yii::$app->promocode->get()->promocode->discount;

            if($persent > 0 && $persent <= 100 && $event->cost > 0) {
                $event->cost = $event->cost-($event->cost*$persent)/100;
            }
        }
        return $this;
    }
}
