<?php

use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\modules\admin\modules\mail\models\Addressee */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => '收件人', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);

$this->params['menus'] = [
    ['label' => Yii::t('app', '列表'), 'url' => ['index']],
    ['label' => Yii::t('app', '添加'), 'url' => ['create']],
    ['label' => Yii::t('app', '修改'), 'url' => ['update', 'id' => $model->account]],
];
?>
<div class="addressee-view">

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'account',
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
