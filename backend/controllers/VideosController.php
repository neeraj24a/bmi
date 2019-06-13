<?php

namespace backend\controllers;
use Yii;
use \yii\helpers\Url;
use backend\models\SongsSearch;
use backend\models\Stream;
use backend\models\Track;
use yii\filters\VerbFilter;
class VideosController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors() {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }
    
    public function beforeAction($action) {
        if ( Yii::$app->user->isGuest )
            return Yii::$app->getResponse()->redirect(Url::to(['/login'],302));
        return parent::beforeAction($action);
    }
    
    public function actionIndex() {
        $month = date('Y-m');
        $array = [];
        $array['SongsSearch'] = [];
        if(isset($_GET['month']) && !empty($_GET['month'])){
            $month = $_GET['month'];
        }
        $searchModel = new SongsSearch();
        $params = '';
        if(isset($_GET['SongsSearch'])){
            $params = Yii::$app->request->queryParams;
        }
        $title = '';
        $artist = '';
        if(!empty($params)){
            $title = $params['SongsSearch']['title'];
            $artist = $params['SongsSearch']['artist'];
        }
        $condition = "";
        if(!empty($title) && !empty($artist)){
            $condition = " AND t.title LIKE '%$title%' AND t.artist LIKE '%$artist%'";
        } elseif (!empty($title) && empty($artist)) {
            $condition = " AND t.title LIKE '%$title%'";
        } elseif(empty($title) && !empty($artist)) {
            $condition = " AND t.artist LIKE '%$artist%'";
        }
        if(!empty($month)){
            $start_date = $month.'-01';
            $end_date = $month.'-31';
            
            $sql = "select COUNT(*) AS download from track t, stream s where s.createdAt BETWEEN '$start_date' AND '$end_date' AND t.type = 'video' and t.id = s.track and s.type = 1".$condition;
            $sql2 = "select COUNT(*) AS stream from track t, stream s where s.createdAt BETWEEN '$start_date' AND '$end_date' AND t.type = 'video' and t.id = s.track and s.type = 0".$condition;
        } else {
            $sql = "select COUNT(*) AS download from track t, stream s where t.type = 'video' and t.id = s.track and s.type = 1".$condition;
            $sql2 = "select COUNT(*) AS stream from track t, stream s where t.type = 'video' and t.id = s.track and s.type = 0".$condition;
        }
        
        $params['SongsSearch']['type'] = 'video';
        $dataProvider = $searchModel->search($params);
        
        
        $connection = Yii::$app->getDb();
        $command = $connection->createCommand($sql);
        $result = $command->queryAll();
        $command2 = $connection->createCommand($sql2);
        $result2 = $command2->queryAll();
        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'month' => $month,
            'stream' => $result2[0]['stream'],
            'download' => $result[0]['download']
        ]);
    }

}
