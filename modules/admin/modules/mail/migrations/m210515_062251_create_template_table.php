<?php

use yii\db\Migration;

/**
 * Class m210515_062251_create_template_table
 */
class m210515_062251_create_template_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%template}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(60)->notNull()->unique()->comment('模板名称'),
            'subject' => $this->string(60)->notNull()->comment('模板主题'),
            'body' => $this->string()->notNull()->comment('模板内容'),
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
        $this->dropTable('{{%template}}');
    }
}
