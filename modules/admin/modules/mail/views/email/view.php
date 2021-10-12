<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Email */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '邮件列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->id]],
];
?>
<div class="email-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'addresser.account',
            'addressee.account',
            'template.name',
            'type:emailType',
            'status:emailStatus',
            'remark:ntext',
            'created_at:datetime',
            'updated_at:datetime',
            [
                'label' => '添加人',
                'attribute' => 'creator.username'
            ],
            'updated_at:datetime',
            [
                'label' => '修改人',
                'attribute' => 'updater.username'
            ],
        ],
    ]) ?>

</div>
