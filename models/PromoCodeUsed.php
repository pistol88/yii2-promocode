<?php
namespace pistol88\promocode\models;

use Yii;


class PromoCodeUsed extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%promocode_used}}';
    }

    public function rules()
    {
        return [
            [['promocode_id', 'order_id', 'date'], 'required'],
            [['promocode_id', 'order_id', 'user'], 'integer'],
            [['date'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'promocode_id' => 'ID Промокода',
            'order_id' => 'ID Заказа',
            'date' => 'Дата использования',
            'user' => 'Использовано пользователем',
        ];
    }
}
