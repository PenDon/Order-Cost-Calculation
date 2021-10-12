<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Addresser */

$this->title = '修改发件人: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => '发件人列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']]
];
?>
<div class="addresser-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
