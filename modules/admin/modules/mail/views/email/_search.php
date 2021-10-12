<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\EmailSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="form-outside form-search form-layout-column">
    <div class="email-search form">

        <?php $form = ActiveForm::begin([
            'action' => ['index'],
            'method' => 'get',
            ]); ?>

    <?= $form->field($model, 'addresser_id')->dropDownList(\app\modules\admin\modules\mail\models\Addresser::map(), ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'addressee_id')->dropDownList(\app\modules\admin\modules\mail\models\Addressee::map(), ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'template_id')->dropDownList(\app\modules\admin\modules\mail\models\Template::map(), ['prompt' => '请选择']) ?>


    <?= $form->field($model, 'type')->dropDownList(\app\modules\admin\modules\mail\models\Email::typeOptions(), ['prompt' => '请选择']) ?>

    <?= $form->field($model, 'status')->dropDownList(\app\modules\admin\modules\mail\models\Email::statusOptions(), ['prompt' => '请选择']) ?>


        <div class="form-group buttons">
            <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
            <?= Html::resetButton('重置', ['class' => 'btn btn-outline-secondary']) ?>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
