<?php

use yii\db\Migration;

/**
 * Class m210515_062542_create_email_table
 */
class m210515_062542_create_email_table extends Migration
{
    /**
     * {@inheritdoc}
     */
    public function safeUp()
    {
        $this->createTable('{{%email}}', [
            'id' => $this->primaryKey(),
            'addresser_id' => $this->smallInteger()->notNull()->comment('发件人ID'),
            'addressee_id' => $this->smallInteger()->notNull()->comment('收件人ID'),
            'template_id' => $this->smallInteger()->defaultValue(0)->comment('模板ID'),
            'type' => $this->smallInteger()->defaultValue(0)->comment('邮件类型'),
            'status' => $this->smallInteger()->defaultValue(0)->comment('邮件状态'),
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
        $this->dropTable('{{%email}}');
    }

}
