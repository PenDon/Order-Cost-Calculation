<?php

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Email */

$this->title = '添加邮件';
$this->params['breadcrumbs'][] = ['label' => '邮件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="email-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
