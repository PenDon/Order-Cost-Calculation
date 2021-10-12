<?php

/* @var $this yii\web\View */

/* @var $model app\modules\admin\modules\mail\forms\BatchSendForm */

use app\modules\admin\modules\mail\models\Addresser;
use app\modules\admin\modules\mail\models\Template;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '批量发送邮件';
$this->params['breadcrumbs'][] = ['label' => '邮件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="form-outside">
    <div class="email-form form">
        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

        <?= $form->field($model, 'addressees')->fileInput([]) ?>
        <?= $form->field($model, 'addresser_id')->dropDownList(Addresser::map()) ?>
        <?= $form->field($model, 'template_id')->dropDownList(Template::map()) ?>

        <div class="form-group buttons">
            <?= Html::submitButton('发送', ['class' => 'btn btn-success']) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>
</div>