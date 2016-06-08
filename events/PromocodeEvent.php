<?php
namespace pistol88\promocode\events;

use yii\base\Event;

class PromocodeEvent extends Event
{
    public $code;
    public $data;
}