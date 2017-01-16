<?php
namespace pistol88\promocode\models;

use Yii;


class PromoCode extends \yii\db\ActiveRecord
{
    
    public static function tableName()
    {
        return '{{%promocode}}';
    }

    public function rules()
    {
        return [
            [['title', 'code', 'discount', 'status'], 'required'],
            [['description'], 'string'],
            [['discount', 'status','amount'], 'integer'],
            [['date_elapsed'], 'safe'],
            [['title'], 'string', 'max' => 256],
            [['code'], 'unique'],
            [['code'], 'string', 'max' => 14]
        ];
    }

    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название кода(акции)',
            'description' => 'Описание',
            'code' => 'Код',
            'discount' => '% скидки',
            'status' => 'Статус',
            'date_elapsed' => 'Срок истечения',
            'amount' => 'Количество использований'
        ];
    }
    
    public function getTargetModels()
    {
        return $this->hasMany(PromocodeToItem::className(), ['promocode_id' => 'id']);
    }

    public function getTransactions()
    {
        return $this->hasMany(PromoCodeUsed::className(),['promocode_id' => 'id']);
    }
}
