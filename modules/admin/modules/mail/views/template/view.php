<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Template */

$this->title = $model->name;
$this->params['breadcrumbs'][] = ['label' => '模板列表', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => Yii::t('app', 'Update'), 'url' => ['update', 'id' => $model->name]],
];
?>
<div class="template-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'name',
            'subject',
            'body',
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
