<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\modules\mail\models\AddresseeSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '收件人';
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', '列表'), 'url' => ['index']],
    ['label' => Yii::t('app', '添加收件人'), 'url' => ['create']],
];
?>
<div class="addressee-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            'account',
            'remark:ntext',
            [
                'contentOptions' => ['class' => 'datetime'],
                'attribute' => 'created_at',
                'format' => 'datetime'
            ],
//            'created_by',
            [
                'contentOptions' => ['class' => 'datetime'],
                'attribute' => 'updated_at',
                'format' => 'datetime'
            ],
//            'updated_by',
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{view} {update} {delete}',
                'headerOptions' => ['class' => 'buttons-3 last'],
            ],
        ],
    ]); ?>


</div>
