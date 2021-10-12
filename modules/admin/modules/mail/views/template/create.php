<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Template */

$this->title = '创建模板';
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="template-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
