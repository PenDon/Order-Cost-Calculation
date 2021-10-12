<?php

use yii\db\Migration;

/**
 * Class m210515_061337_create_addresser_table
 */
class m210515_061337_create_addresser_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%addresser}}', [
            'id' => $this->primaryKey(),
            'account' => $this->string(60)->unique()->notNull()->comment('邮箱账户'),
            'pwd' => $this->string()->notNull()->comment('密码'),
            'remark' => $this->text()->comment('备注'),
            'created_at' => $this->integer()->notNull()->comment('添加时间'),
            'created_by' => $this->integer()->notNull()->comment('添加人'),
            'updated_at' => $this->integer()->notNull()->comment('更新时间'),
            'updated_by' => $this->integer()->notNull()->comment('更新人'),
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function safeDown()
    {
        $this->dropTable('{{%addresser}}');
    }
}
