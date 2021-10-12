<?php

namespace app\modules\admin\modules\mail\models;

use app\models\Member;
use Yii;
use yii\db\Query;

/**
 * This is the model class for table "{{%addresser}}".
 *
 * @property int $id
 * @property string $account 邮箱账户
 * @property string $pwd 密码
 * @property string|null $remark 备注
 * @property int $created_at 添加时间
 * @property int $created_by 添加人
 * @property int $updated_at 更新时间
 * @property int $updated_by 更新人
 */
class Addresser extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%addresser}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['account', 'pwd'], 'required'],
            [['remark'], 'string'],
            [['account'], 'string', 'max' => 60],
            [['pwd'], 'string', 'max' => 255],
            [['account'], 'unique'],
            [['account'], 'email'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'account' => '邮箱账户',
            'pwd' => '邮箱登录密码',
            'remark' => '备注',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
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
            $this->pwd = base64_encode($this->pwd);

            return true;
        } else {
            return false;
        }
    }

    /**
     * 发件人列表
     *
     * @return array
     */
    public static function map()
    {
        return (new Query())
            ->select(['account'])
            ->from('{{%addresser}}')
            ->indexBy('id')
            ->column();
    }
}
