<?php

use yii\db\Migration;

/**
 * Class m210515_062048_create_addressee_table
 */
class m210515_062048_create_addressee_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%addressee}}', [
            'id' => $this->primaryKey(),
            'account' => $this->string(60)->unique()->notNull()->comment('邮箱账户'),
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
        $this->dropTable('{{%addressee}}');
    }
}
