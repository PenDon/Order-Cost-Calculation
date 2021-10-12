<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\modules\mail\models\TemplateSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '模板列表';
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
];
?>
<div class="template-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            'name',
            'subject',
            'body',
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
