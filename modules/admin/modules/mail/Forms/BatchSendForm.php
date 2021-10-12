<?php

namespace app\modules\admin\modules\mail\forms;

use app\jobs\SendMailJob;
use app\modules\admin\modules\mail\models\Addresser;
use app\modules\admin\modules\mail\models\Template;
use PhpOffice\PhpSpreadsheet\IOFactory;
use yii\base\Model;
use yii\web\UploadedFile;

class BatchSendForm extends Model
{
    public $addressees;
    public $addresser_id;
    public $template_id;

    public function rules()
    {
        return [
            [['addresser_id', 'template_id', 'addressees'], 'required'],
            [['addresser_id', 'template_id'], 'integer'],
            ['addressees', 'file', 'extensions' => 'csv'],
            ['addresser_id', 'exist', 'targetClass' => Addresser::class, 'targetAttribute' => ['addresser_id' => 'id']],
            ['template_id', 'exist', 'targetClass' => Template::class, 'targetAttribute' => ['template_id' => 'id']],
        ];
    }

    /**
     * Push tasks to queue
     *
     * @return bool
     */
    public function save()
    {
        $this->addressees = UploadedFile::getInstance($this, 'addressees');
        $dir = 'uploads/' . $this->addressees->baseName . '.' . $this->addressees->extension;
        $this->addressees->saveAs($dir);
        $wb = IOFactory::load($dir);
        $sheet = $wb->getActiveSheet();
        $maxRow = $sheet->getHighestRow();
        for ($index = 2; $index <= $maxRow; $index++) {
            $email = $sheet->getCellByColumnAndRow(1, $index)->getValue();
            $name = $sheet->getCellByColumnAndRow(2, $index)->getValue();
            \Yii::$app->queue->push(new SendMailJob([
                'addressee' => $email,
                'addressee_name' => $name,
                'addresser_id' => $this->addresser_id,
                'template_id' => $this->template_id,
            ]));
        }

        return true;
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'addresser_id' => '发件人',
            'addressees' => '收件人',
            'template_id' => '模板',
        ];
    }
}