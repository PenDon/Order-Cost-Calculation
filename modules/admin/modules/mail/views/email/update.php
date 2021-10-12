<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Email */

$this->title = '修改邮件: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '邮件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="email-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
