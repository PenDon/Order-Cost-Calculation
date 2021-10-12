<?php

namespace app\modules\admin\modules\mail\models;

use app\models\Member;
use app\modules\api\models\Constant;
use Yii;

/**
 * This is the model class for table "{{%email}}".
 *
 * @property int $id
 * @property int $addresser_id 发件人ID
 * @property int $addressee_id 收件人ID
 * @property int $template_id 模板ID
 * @property int|null $type 邮件类型
 * @property int|null $status 邮件状态
 * @property string|null $remark 备注
 * @property int $created_at 添加时间
 * @property int $created_by 添加人
 * @property int $updated_at 更新时间
 * @property int $updated_by 更新人
 */
class Email extends \yii\db\ActiveRecord
{
    const TYPE_SEND = 0;
    const TYPE_RECEIVE = 1;
    const STATUS_DEFAULT = 0;
    const STATUS_SENT = 1;
    const STATUS_RECEIVED = 2;
    const STATUS_PENDING = 3;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%email}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['addresser_id', 'addressee_id'], 'required'],
            ['addresser_id', 'exist', 'targetClass' => Addresser::class, 'targetAttribute' => ['addresser_id' => 'id']],
            ['addressee_id', 'exist', 'targetClass' => Addressee::class, 'targetAttribute' => ['addressee_id' => 'id']],
            ['template_id', 'exist', 'targetClass' => Template::class, 'targetAttribute' => ['template_id' => 'id']],
            ['template_id', 'default', 'value' => Constant::BOOLEAN_FALSE],
            [['addresser_id', 'addressee_id', 'template_id', 'type', 'status'], 'integer'],
            [['remark'], 'string'],
        ];
    }

    /**
     * 邮件类型
     *
     * @return array
     */
    public static function typeOptions()
    {
        return [
            self::TYPE_SEND => '发件',
            self::TYPE_RECEIVE => '收件',
        ];
    }

    /**
     * 邮件状态
     *
     * @return array
     */
    public static function statusOptions()
    {
        return [
            self::STATUS_DEFAULT=> '未处理',
            self::STATUS_SENT=> '已发送',
            self::STATUS_RECEIVED=> '已接收',
            self::STATUS_PENDING=> '待发送',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'addresser_id' => '发件人',
            'addressee_id' => '收件人',
            'template_id' => '所用模板名称',
            'type' => '邮件类型',
            'status' => '邮件状态',
            'remark' => '备注',
            'created_at' => '创建时间',
            'created_by' => '创建人',
            'updated_at' => '修改时间',
            'updated_by' => '修改人',
        ];
    }

    /**
     * 发件人
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddresser()
    {
        return $this->hasOne(Addresser::class, ['id' => 'addresser_id']);
    }

    /**
     * 收件人
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAddressee()
    {
        return $this->hasOne(Addressee::class, ['id' => 'addressee_id']);
    }

    /**
     * 模板
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::class, ['id' => 'template_id']);
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
}
