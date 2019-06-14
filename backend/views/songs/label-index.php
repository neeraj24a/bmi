<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Stream;
use backend\models\Questions;
use backend\models\Answers;
/* @var $this yii\web\View */
/* @var $searchModel backend\models\UsersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = 'Songs';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-12">
            <?php Pjax::begin(); ?>
            <div class="card">
                <div class="card-header card-header-rose card-header-icon">
                    <!--<div class="card-icon">
                        <i class="material-icons">assignment</i>
                    </div>-->
                    <div class="col-lg-6">
                        
                    </div>
                    <div class="col-lg-6">
                          <?= Html::a('Reset', ['/songs'], ['class' => 'mb-sm btn btn-success ml-10 pull-right']) ?>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <?=
                        GridView::widget([
                            'dataProvider' => $dataProvider,
                            'filterModel' => $searchModel,
                            'tableOptions' => ['class' => 'table'],
                            'columns' => [
                                ['class' => 'yii\grid\SerialColumn'],
                                'title',
                                'artist',
                                [
                                    'attribute' => 'id',
                                    'label' => 'Feedbacks',
                                    'value' => function($model) {
                                        $feedbacks = Answers::find()->where(['track' => $model->id])->all();
                                        return count($feedbacks);
                                    }
                                ],
                                [
                                    'label'=>'Export Feedbacks',
                                    'format' => 'raw',
                                    'value'=>function ($model) {
                                        return Url::base().'songs/feedbacks?id='.$model->id;
                                    },
                                ],
                            ],
                        ]);
                        ?>
                    </div>
                </div>
            </div>
<?php Pjax::end(); ?>
        </div>
    </div>
</div>
