<?php

use yii\db\Schema;
use yii\db\Migration;

class m170116_073411_promocode extends Migration
{

    public function init()
    {
        $this->db = 'db';
        parent::init();
    }

    public function safeUp()
    {
        $tableOptions = 'ENGINE=InnoDB';

        $this->createTable(
            '{{%promocode}}',
            [
                'id'=> $this->primaryKey(11),
                'title'=> $this->string(256)->notNull(),
                'description'=> $this->text()->notNull(),
                'code'=> $this->string(14)->notNull(),
                'discount'=> $this->integer(2)->notNull(),
                'status'=> $this->integer(1)->notNull(),
                'date_elapsed'=> $this->datetime()->null()->defaultValue(null),
                'amount'=> $this->integer(11)->null()->defaultValue(null),
            ],$tableOptions
        );
        $this->createIndex('code','{{%promocode}}','code',true);
    }

    public function safeDown()
    {
        $this->dropIndex('code', '{{%promocode}}');
        $this->dropTable('{{%promocode}}');
    }
}
