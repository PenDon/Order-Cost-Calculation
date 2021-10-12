<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Addresser */

$this->title = '添加发件人';
$this->params['breadcrumbs'][] = ['label' => '发件人列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="addresser-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
