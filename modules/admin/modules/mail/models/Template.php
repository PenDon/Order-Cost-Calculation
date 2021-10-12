<?php

namespace app\modules\admin\modules\mail\models;

use app\models\Member;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%template}}".
 *
 * @property int $id
 * @property string $name 模板名称
 * @property string $subject 模板主题
 * @property string $body 模板内容
 * @property string|null $remark 备注
 * @property int $created_at 添加时间
 * @property int $created_by 添加人
 * @property int $updated_at 更新时间
 * @property int $updated_by 更新人
 */
class Template extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%template}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'subject', 'body'], 'required'],
            [['remark'], 'string'],
            [['name', 'subject'], 'string', 'max' => 60],
            [['body'], 'string', 'max' => 255],
            [['name'], 'unique'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '模板名称',
            'subject' => '模板主题',
            'body' => '模板内容',
            'remark' => '备注',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改时间',
        ];
    }

    /**
     * 创建人
     *
     * @return \yii\db\ActiveQuery
     */
    public function getCreator()
    {
        return $this->hasOne(Member::class, ['id' => 'created_by']);
    }

    /**
     * 更新人
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUpdater()
    {
        return $this->hasOne(Member::class, ['id' => 'updated_by']);
    }

    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {
            if ($insert) {
                $this->updated_at = $this->created_at = time();
                $this->updated_by = $this->created_by = Yii::$app->getUser()->getId();
            } else {
                $this->updated_at = time();
                $this->updated_by = Yii::$app->getUser()->getId();
            }

            return true;
        } else {
            return false;
        }
    }

    /**
     * 邮件列表
     *
     * @return array
     */
    public static function map()
    {
        return (new Query())
            ->select(['name'])
            ->from('{{%template}}')
            ->indexBy('id')
            ->column();
    }
}
