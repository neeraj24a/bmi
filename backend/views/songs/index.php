<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\GridView;
use yii\widgets\Pjax;
use backend\models\Stream;

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
                        <h4 class="card-title pull-left bold" style="font-weight: bold;">Songs List</h4>
                        <?php if (!empty($month)): ?>
                            <?php
                            $time = strtotime($month . '-01');
                            $result = date("F Y", $time);
                            ?>

                            <p class="txt-black pull-left bold" style="margin-top:14px;margin-left:20px;">Total Stream in <?php echo $result; ?>: <?php echo $stream; ?> & &nbsp; Total Download in <?php echo $result; ?>: <?php echo $download; ?></p>
                        <?php else: ?>
                            <p class="txt-black pull-left bold" style="margin-top:14px;margin-left:20px;">All Time Total Stream: <?php echo $stream; ?> & &nbsp; All Time Total Download: <?php echo $download; ?></p>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-6">
                        <form>
                            <div class="col-lg-8">
                                <div class="form-group">
                                    <div class="form-group bmd-form-group">
                                        <select name="month" class="form-control" id="months">
                                            <option value="">All</option>
                                            <?php
                                            $selected = '';
                                            for ($i = 0; $i <= 18; ++$i) {
                                                $time = strtotime(sprintf('-%d months', $i));
                                                $value = date('Y-m', $time);
                                                if ($value > '2018-02') {
                                                    if ($value == $month) {
                                                        $selected = 'selected';
                                                    } else {
                                                        $selected = '';
                                                    }
                                                    $label = date('F Y', $time);
                                                    printf('<option value="%s" %s>%s</option>', $value, $selected, $label);
                                                } else {
                                                    break;
                                                }
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <button type="submit" class="col-lg-6 mb-sm btn btn-warning ml-10">Search</button>
                                <?= Html::a('Reset', ['/songs'], ['class' => 'mb-sm btn btn-success ml-10 pull-right']) ?>
                            </div>
                        </form>
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
                                    'label' => 'Stream Count',
                                    'value' => function($model) use($month) {
                                        if (!empty($month)) {
                                            $start_date = $month . '-01';
                                            $end_date = $month . '-31';
                                            $stream = Stream::find()->where(['between', 'createdAt', $start_date, $end_date])
                                                            ->andWhere(['track' => $model->id, 'type' => 0])->all();
                                        } else {
                                            $stream = Stream::findAll(['track' => $model->id, 'type' => 0]);
                                        }
                                        return count($stream);
                                    }
                                ],
                                [
                                    'attribute' => 'id',
                                    'label' => 'Download Count',
                                    'value' => function($model) use($month) {
                                        if (!empty($month)) {
                                            $start_date = $month . '-01';
                                            $end_date = $month . '-31';
                                            $download = Stream::find()->where(['between', 'createdAt', $start_date, $end_date])
                                                            ->andWhere(['track' => $model->id, 'type' => 1])->all();
                                        } else {
                                            $download = Stream::findAll(['track' => $model->id, 'type' => 1]);
                                        }
                                        return count($download);
                                    }
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