<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Addressee */

$this->title = '添加收件人';
$this->params['breadcrumbs'][] = ['label' => '收件人', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', '列表'), 'url' => ['index']],
];
?>
<div class="addressee-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
