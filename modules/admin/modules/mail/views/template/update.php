<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Template */

$this->title = '修改模板: ' . $model->name;
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = '修改';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="template-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
