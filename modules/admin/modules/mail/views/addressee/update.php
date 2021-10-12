<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Addressee */

$this->title = '修改收件人: ' . $model->account;
$this->params['breadcrumbs'][] = ['label' => '列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="addressee-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
