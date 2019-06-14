<?php

namespace backend\controllers;

use Yii;
use \yii\helpers\Url;
use backend\models\SongsSearch;
use backend\models\Stream;
use backend\models\Track;
use backend\models\Questions;
use backend\models\Answers;
use yii\filters\VerbFilter;

class SongsController extends \yii\web\Controller {
    
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
	$session = Yii::$app->session;
        $type = $session->get('user-type');
        $label = $session->get('label');
	
	if($type == 'general'){
		$searchModel = new SongsSearch();
		$searchModel->recordlabel = $label;
		$params = '';
		if(isset($_GET['SongsSearch'])){
		    $params = Yii::$app->request->queryParams;
		}
		$params['SongsSearch']['recordlabel'] = $label;
		$dataProvider = $searchModel->search($params);
		return $this->render('label-index', [
		    'searchModel' => $searchModel,
		    'dataProvider' => $dataProvider,
		]);
	} else {
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

		    $sql = "select COUNT(*) AS download from track t, stream s where s.createdAt BETWEEN '$start_date' AND '$end_date' AND t.type = 'audio' and t.id = s.track and s.type = 1".$condition;
		    $sql2 = "select COUNT(*) AS stream from track t, stream s where s.createdAt BETWEEN '$start_date' AND '$end_date' AND t.type = 'audio' and t.id = s.track and s.type = 0".$condition;
		} else {
		    $sql = "select COUNT(*) AS download from track t, stream s where t.type = 'audio' and t.id = s.track and s.type = 1".$condition;
		    $sql2 = "select COUNT(*) AS stream from track t, stream s where t.type = 'audio' and t.id = s.track and s.type = 0".$condition;
		}

		$params['SongsSearch']['type'] = 'audio';
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
	
	public function actionExport(){
		$model = Track::find()->All();
		$filename = 'Data-'.Date('YmdGis').'-Tracks.xls';
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=".$filename);
		echo '<table border="1" width="100%">
			<thead>
				<tr>
					<th>COUNT</th>
					<th>Artist</th>
					<th>Title</th>
					<th>Writer</th>
				</tr>
			</thead>';
			foreach($model as $data){
				$stream = $stream = Stream::findAll(['track' => $data->id, 'type' => 0]);
				echo '
					<tr>
						<td>'.count($stream).'</td>
						<td>'.$data['artist'].'</td>
						<td>'.$data['title'].'</td>
						<td>NA</td>
					</tr>
				';
			}
		echo '</table>';

	}
	
	public function actionFeebacks($id){
		$sql = "SELECT t.name, q.question, a.answer FROM `answers` a, `questions` q, `track` t WHERE t.id = '".$id."' AND a.track = t.id AND a.question = q.id";
		$command = $connection->createCommand($sql);
		$result = $command->queryAll();
		$filename = 'Feedback-'.Date('YmdGis').'-Tracks.xls';
		header("Content-type: application/vnd-ms-excel");
		header("Content-Disposition: attachment; filename=".$filename);
		echo '<table border="1" width="100%">
			<thead>
				<tr>
					<th>Song Name</th>
					<th>Question</th>
					<th>Answer</th>
				</tr>
			</thead>';
			foreach($result as $data){
				echo '
					<tr>
						<td>'.$data['name'].'</td>
						<td>'.$data['question'].'</td>
						<td>'.$data['answer'].'</td>
					</tr>
				';
			}
		echo '</table>';
	}
	
	public function actionCsv(){
		$sql = "SELECT COUNT(*) as nos, t.title, t.artist FROM `stream` s, `track` t WHERE s.type = 0 AND t.type = 'audio' AND s.track = t.id GROUP BY s.track Order BY nos DESC";
		$filename = "reports.csv";
		header('Content-type: application/csv');
		header('Content-Disposition: attachment; filename='.$filename);
		
		$fp = fopen('php://output', 'w');
		$fields = ['COUNT','ARTIST','TITLE'];
		foreach($fields as $field){
				$header[] = $field;
		}   
		fputcsv($fp, $header);
		$command = $connection->createCommand($sql);
        	$result = $command->queryAll();
		foreach ($result as $data) {
				fputcsv($fp, $data);
		}
		fclose($fp);
		exit;
	}
    
}
