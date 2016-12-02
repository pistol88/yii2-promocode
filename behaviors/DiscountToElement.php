<?php
namespace pistol88\promocode\behaviors;

use yii;
use yii\base\Behavior;

class DiscountToElement extends Behavior
{
    public $eventName = 'element_cost';
    
    public function events()
    {
        $eventName = $this->eventName;

        return [
            $eventName => 'doDiscount'
        ];
    }

    public function doDiscount($event)
    {
        if(yii::$app->promocode->has() && $targetModels = yii::$app->promocode->getTargetModels()) {
            $persent = yii::$app->promocode->get()->promocode->discount;

            if($persent > 0 && $persent <= 100 && $event->cost > 0) {
                foreach($targetModels as $target) {
                    if($target->item_model == $event->element->model && $target->item_id == $event->element->id) {
                        $event->cost = $event->cost-($event->cost*$persent)/100;
                    }
                }
            }
        }

        return $this;
    }
}
