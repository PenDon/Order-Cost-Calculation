<?php

use yii\helpers\Html;
use yii\grid\GridView;

/* @var $this yii\web\View */
/* @var $searchModel app\modules\admin\modules\mail\models\EmailSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = '邮件列表';
$this->params['breadcrumbs'][] = $this->title;

$this->params['menus'] = [
    ['label' => Yii::t('app', 'List'), 'url' => ['index']],
    ['label' => Yii::t('app', 'Create'), 'url' => ['create']],
    ['label' => '批量发送邮件', 'url' => ['batch-send']],
];
?>
<div class="email-index">

    <?= $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            [
                'class' => 'yii\grid\SerialColumn',
                'contentOptions' => ['class' => 'serial-number']
            ],
            [
                'label' => '发件人邮箱',
                'attribute' => 'addresser.account'
            ],
            [
                'label' => '收件人邮箱',
                'attribute' => 'addressee.account',
            ],
            [
                'label' => '所用模板名称',
                'attribute' => 'template.name'
            ],
            'type:emailType',
            'status:emailStatus',
            //'remark:ntext',
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
