<?php

namespace app\controllers;

use Yii;
use yii\web\Controller;
use yii\base\Exception;
use yii\web\UnprocessableEntityHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;
use yii\data\ActiveDataProvider;
use app\models\Errormessages;
use app\models\filters\MyAccessControl;
use app\models\role\Right;
use app\models\FeatureFlags;
use app\models\voting\Votingtopic;
use app\models\voting\Votingquestion;
use app\models\voting\Votingtype;
use app\models\voting\Votinganswer;
use app\models\voting\Votingweights;
use app\models\voting\Votingoption;
use JsonException;

class VotingController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => MyAccessControl::className(),
                //'only' => ['create', 'update'],
                'except' => ['index'],
                'rules' => [
                    // allow authenticated users
                    [
                        'allow' => true,
                        'actions' => ['edit','edit-topic','get-or-save-votingweights-list-of-topic',
                            'create-topic', 'edit-topicvotings', 'get-all-topics', 'sort-up-topic',
                            'get-all-votings', 'edit-question','show-results','get-results','delete-votinganswer',
                            'get-all-votingweights-of-topic'
                        ],
                        'roles' => [Right::VOTINGADMIN],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['index','get-topic','get-votingweights'],
                        'roles' => ['@'],
                    ],
                    // everything else is denied by default
                ],
            ],            
        ];
    }

    public function beforeAction($action) 
    { 
        if(!FeatureFlags::VOTING_ACTIVATED) throw new NotFoundHttpException(Yii::t('errors', 'NOTSUPPORTED'));

        $this->enableCsrfValidation = false; 
        return parent::beforeAction($action); 
    }    
    
    /**
     * Displays List of Votingtopics
     *
     * @return Array
     *
    public function actionIndex()
    {
        return $this->render('index',[
        ]);
        
    }
    
    /* Adminbereich*/

    /**
     * Bearbeitung der Umfragen
     * Zeigt edit, sort und lösch-Buttons an
     * @return array
     */
    public function actionEdit()
    {
        return $this->render('edit',[
        ]);
            
    }
    
    /**
     * Lädt alle Votingtopics
     * @return JSON
     */
    public function actionGetAllTopics()
    {
        $this->layout = 'nolayout';

        try {
            $items = Votingtopic::getAllVotingtopics();
            return $this->asJson($items);

        } catch (\yii\db\Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        } catch (Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        }

    }
    

     /*
     * Lädt die aktuell aktiven Votingtopics
     * @return JSON
     */
    public function actionGetTopics()
    {
        $this->layout = 'nolayout';

        try {
            $items = Votingtopic::getPublicVotingtopics();
            return $this->asJson($items);

        } catch (\yii\db\Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        } catch (Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        }

    }


    /**
     * Create Topic
     * @return array
     */
    public function actionCreateTopic()
    {        
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            $model       = new Votingtopic();
            if($model->load(Yii::$app->request->post()) && $model->validate()){
                $item       = VotingTopic::create($model->headline);           

                $ret['saved'] = true;
                $transaction->commit();
            }
            else {
                $ret['errormessages'] = $model->getErrors();
            }
            
            return $this->asJson($ret);
 
        } catch (Exception $e) {
            /* model errors while save */
            Yii::error('VotingTopic - Error Create: '.$e, __METHOD__);
            $transaction->rollBack();
            if($item && $item->hasErrors()){
                $ret['errormessages'] = $model->getErrors();
            }
            $ret['saved'] = false;
            return $this->asJson($ret);
            
        } catch (\yii\db\IntegrityException $e) {
            $transaction->rollBack();
            Yii::error('VotingTopic - Error Create: '.$e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
            
    }

    /**
     * Create Topic
     * @param int $p ID des Topics
     * @return array
     */
    public function actionSaveTopic($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            $model       = Votingtopic::find()->where(['id'=>$p])->one();
            if($model && $model->load(Yii::$app->request->post()) ){
                $model->updateFromRequest();

                $ret['saved'] = true;
                $transaction->commit();
            }
            else {
                $ret['errormessages'] = $model->getErrors();
            }
            
            return $this->asJson($ret);
 
        } catch (Exception $e) {
            /* model errors while save */
            Yii::error('VotingTopic - Error Edit: '.$e, __METHOD__);
            $transaction->rollBack();
            if($item && $item->hasErrors()){
                $ret['errormessages'] = $model->getErrors();
            }
            $ret['saved'] = false;
            return $this->asJson($ret);
            
        } catch (\yii\db\IntegrityException $e) {
            $transaction->rollBack();
            Yii::error('VotingTopic - Error Edit: '.$e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
            
    }    

    /**
     * Löschen eines Topics
     * @param int $p ID des Topics
     * @return array
     */
    public function actionDeleteTopic($p)
    {
        $model = Votingtopic::find()->where('id = '.$p)->one();
        $sort = $model->sort;
        if($model->delete()){
            //alle folgenden runtersortieren
            Votingtopic::decreaseSortAfter($sort);
            $ret['deleted'] = true;
        }
        else
            $ret['deleted'] = false;

        return $this->asJson($ret);
            
            
    }

    
    /**
     * Lädt alle Votings zu einem Topic
     * @param int $p ID des Topics
     * @return JSON
     */
    public function actionGetAllVotings($p)
    {
        $this->layout = 'nolayout';
        $model      = Votingtopic::find()->where('id = '.$p)->one();

        try {
            $items = $model->getVotingquestions()->asArray()->all();
            foreach($items as &$item){
                if(isset($item['id']) && $item['id'] != "0"){
                    if(is_array($item) && isset($item['votingtype']['name']) && $item['votingtype']['name']=="text")
                        $item["countresults"] = Votinganswer::countResultsByAnswersByVotingquestionId($item["id"]);
                    else if(is_array($item))
                        $item["countresults"] = Votinganswer::countResultsByAnswererByVotingquestionId($item["id"],$item["hasweighting"]);
                }
            }
            return $this->asJson(['items'=>$items]);

        } catch (\yii\db\Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        } catch (Exception $e){
            throw new ServerErrorHttpException("Votingtopiclist konnte nicht geladen werden");
        }

    }

    /**
     * Laden der Gewichtung im Topic
     * @param int $p ID des Topics
     * @return array
     */
    public function actionGetAllVotingweightsOfTopic($p)
    {
        //neuladen und ausgeben
        $settings = Votingweights::find()->where('votingtopic_id = '.$p)->all();
        
        return $this->asJson([
            'items'=> $settings,
        ]);
    }
    
    
    /**
     * Laden/Speichern der Gewichtung im Topic
     * @param int $p ID des Topics
     * @return array
     */
    public function actionSaveVotingWeightsOfTopic($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        try {
            //$settings = Votingweights::find()->indexBy('id')->where('votingtopic_id = '.$p)->all();
            $settings = Votingweights::find()->where('votingtopic_id = '.$p)->all();

            //nochmal durchgehen, um neue Einträge zu finden und zu speichern /wurden oben automatisch igrnoeriet
            $items = Yii::$app->request->post("Votingweights");
            if($items){
                //delete vorherige
                //if(count($settings) != count($items ))
                //Votingweights::deleteAll('votingtopic_id = '.$p);
                foreach($items as $item){
                    if($item["id"] == 0){
                        $new = new Votingweights();
                        $new->votingtopic_id = $p;
                        $new->stimmen = $item["stimmen"];
                        $new->id = $item["id"];
                        $new->name = $item["name"];
                        $new->active = 0;
                        $new->save();
                    }
                    else {
                        $vw = Votingweights::find()->where(['id'=>$item["id"]])->one();
                        $vw->votingtopic_id = $p;
                        $vw->stimmen = $item["stimmen"];
                        $vw->name = $item["name"];
                        $vw->active = 0;
                        $vw->save();
                    }
                }
                $transaction->commit();
                //neuladen und ausgeben
                $settings = Votingweights::find()->where('votingtopic_id = '.$p)->all();
                $ret['saved'] = true;
            }

            return $this->asJson($ret);

        } catch (Exception | \yii\db\IntegrityException $e ) {
            /* model errors while save */
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException();
        }
    }

    /**
     * Erstellen einer Umfragen
´     * @param int $p ID des Topics
     * @return array
     */
    public function actionCreateQuestion($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            $model          = new Votingquestion();
            $model->votingtopic_id = $p;
            if($model->updateFromRequest() ){
                Votingquestion::updateOptionsFromRequest($model->id);
                $ret['saved'] = true;
                
                $transaction->commit();
            }
            $ret['errormessages'] = $model->getErrors();
            
            return $this->asJson($ret);
 
        } catch (Exception $e) {
            /* model errors while save */
            Yii::error('Votingquestion - Error Create: '.$e, __METHOD__);
            $transaction->rollBack();
            if($item && $item->hasErrors()){
                $ret['errormessages'] = $model->getErrors();
            }
            $ret['saved'] = false;
            return $this->asJson($ret);
            
        } catch (\yii\db\IntegrityException $e) {
            $transaction->rollBack();
            Yii::error('Votingquestion - Error Create: '.$e, __METHOD__);
            throw new ServerErrorHttpException(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
        
    }

    /**
     * Bearbeiten einer Umfragen
´     * @param int $p ID der Umfrage
     * @return array
     */
    public function actionEditQuestion($p)
    {
        $this->layout = 'nolayout';
        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        $ret = [];
        $ret['saved'] = false;
        $ret['errormessages'] = [];
        try {
            $model          = Votingquestion::find()->where(['id'=>$p])->one();
            if($model->updateFromRequest()){
                if(count($model->votinganswers)<1){
                    Votingquestion::updateOptionsFromRequest($p);
                    $ret['saved'] = true;
                    $transaction->commit();
                }
                else {
                    $ret['saved'] = false;
                    $transaction->commit(); //rest trotzdem speichern, nur options nicht,
                    //weil, bereits antworten drauf sind
                }
                
            }
            $ret['errormessages'] = $model->getErrors();
            return $this->asJson($ret);
 
        } catch (Exception | \yii\db\IntegrityException $e ) {
            /* model errors while save */
            Yii::error('Votingquestion - Error Edit: '.$e, __METHOD__);
            $transaction->rollBack();
            throw new ServerErrorHttpException();
        }
        
        
    }

    /**
     * Speichern der Votingoptions einer question
     * @param int $p ID der Votingquestion
     * @return array
     */
    public function actionGetVotingoptions($p)
    {
        $settings = Votingoption::find()->where('votingquestion_id = '.$p)->all();

        return $this->asJson([
            'items'=> $settings,
        ]);
    }

    /**
     * Löschen einer Abstimmung
     * @param int $p ID der question
     * @return array
     */
    public function actionDeleteQuestion($p)
    {
        $model = Votingquestion::find()->where('id = '.$p)->one();
        $topic_id = $model->votingtopic_id;
        $sort = $model->sort;
        $options = Votingoption::find()->where(['votingquestion_id'=>$model->id])->all();
        foreach($options as $option)
            $option->delete();
        if($model->delete()){
            //alle folgenden runtersortieren
            Votingquestion::decreaseSortAfter($topic_id,$sort);
            $ret['deleted'] = true;
        }
        else
            $ret['deleted'] = false;

        return $this->asJson($ret);
            
            
    }    
    
    
    /**
     * Abruf der Ergebnisse eines Votings
     * @param $p ID der Votingquestion
     * @return array
     */
    public function actionGetResults($p)
    {
        try {
            $modelQuery = Votingquestion::find()->where('id = '.$p);
            $model = Votingquestion::find()->where('id = '.$p)->one();
                
            $countresults   = 0;
            $sumValues      = 0;
            $resultstatistics = [];
            //$active         = Votingweights::countActiveVW($model->votingtopic);
            
            if($model){
                //bei Gewichtung
                if($model->hasweighting){
                    if($model->votingtype->name == "radio")
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithWeightingRadio($model);
                    else if($model->votingtype->name == "checkbox")
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithWeightingCheckbox($model);
                    else if($model->votingtype->name == "text") {
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithoutWeighting($model);                        
                    }
                }
                //ohne Gewichtung
                else {
                    if($model->votingtype->name == "radio")
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithoutWeightingRadioOrCheckbox($model);
                    else if($model->votingtype->name == "checkbox")
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithoutWeightingRadioOrCheckbox($model);
                    else if($model->votingtype->name == "text") {
                        $result = \app\models\voting\Votingresultstatistic::getResultstatisticsWithoutWeighting($model);                        
                    }
                }
                
                return $this->asJson([
                    'result' => $result
                ]);
                
                //Anzahl antworten rausuchen
                $countresults   = Votinganswer::countResultsByAnswerer($model);
                //Anzahl abgegebener Stimmen
                if($model->hasweighting && $model->votingtype->name == "radio"){
                    $sumValues      = Votinganswer::countResultsByStimmen($model);
                    if($sumValues == null) $sumValues = 0;
                }
                else {
                    $sumValues      = Votinganswer::countResultsByValues($model);
                }
                
                //results nur ausgeben, wenn erlaubt und schon abgestimmt
                if($model->votingtype->name == "text")
                    $resultstatistics = Votinganswer::getResultStatisticsPerValue($model, $sumValues);
                else if($model->hasweighting && $model->votingtype->name == "radio")
                    $resultstatistics = Votinganswer::getResultStatisticsPerOption($model, $sumValues, true);
                else
                    $resultstatistics = Votinganswer::getResultStatisticsPerOption($model, $sumValues);
                //aktive Antworter laden
                $active = $model->votingtopic->getActiveVotingWeights();
            }
            
            $votingweightsstatistic = [];
            if($model->hasweighting){
                $votingweightsstatistic = Votingweights::find()->where('votingtopic_id = '.$model->votingtopic_id)->with('votinganswers')->asArray()->all();
            }
            
            return $this->asJson([
                'countresults' => $countresults,
                'resultstatistics' => $resultstatistics,
                'sumValues' => $sumValues,
                'votingweightsstatistic' => $votingweightsstatistic,
                'active' => $active
            ]);


        } catch (\yii\db\Exception $e){
            Yii::error($e->getMessage().$e->getFile().'('.$e->getLine().')'.$e->getTraceAsString());
            throw new ServerErrorHttpException("Votingquestion konnte nicht geladen werden");
        } catch (Exception $e){
            Yii::error($e->getMessage().$e->getFile().'('.$e->getLine().')'.$e->getTraceAsString());
            throw new ServerErrorHttpException("Votingquestion konnte nicht geladen werden: ");
        }

    }
    
    /**
     * Löschen einer Abstimmung einer VW innerhalb einer Question
     * @param int $p ID der question
     * @param int $p ID des vw
     * @return array
     */
    public function actionDeleteResultsOfVw($p,$p2)
    {
        $ret = [];
        $ret['deleted'] = false;
        if(Votinganswer::deleteResultOfVW($p, $p2)){
            $ret['deleted'] = true;
        }
        
        return $this->asJson($ret);
            
            
    }    
    
    /**
     * Löschen einer Abstimmung einer Question
     * @param int $p ID der question
     * @return array
     */
    public function actionDeleteResultsOfQuestion($p)
    {
        $ret = [];
        $ret['deleted'] = false;
        if(Votinganswer::deleteResultOfQuestion($votingquestion)){
            $ret['deleted'] = true;
        }
        
        return $this->asJson($ret);
            
    }    
    
    
    /**
     * Ruft die Stimmen(Gewichtung) eines Users (Votingweights) innerhalb eines Votingtopics ab
     * @param $p ID des Topics
     * @param $p2 ID des Votingweights
     * @return array
     */
    public function actionGetVotingweightsOfUser($p,$p2)
    {
        $this->layout = 'nolayout';

        try {
            $votingweights  = Votingweights::find()
                    ->where('votingtopic_id = '.$p)
                    ->where('id = '.$p2)
                    ->one();
            $votingweights->setActive();
            
            return $this->asJson([
                'votingweights'=>$votingweights,
            ]);

        } catch (\yii\db\Exception $e){
            Yii::error($e->getMessage(),__METHOD__);
            throw new ServerErrorHttpException("Votingweights konnte nicht geladen werden");
        } catch (Exception $e){
            Yii::error($e->getMessage(),__METHOD__);
            throw new ServerErrorHttpException("Votingweights konnte nicht geladen werden");
        }

    }


    
    
    
    /**
     * Zeigt das aktuelle Voting zu einem Topic
     * @param $p ID des Topics
     * @return array
     */
    public function actionVote($p)
    {
        try {
            $model = Votingtopic::find()->where('id = '.$p)->one();
            //prüfen, ob aktiv, sonst zur Startseite weiterleiten
            if(!$model->active)
                return $this->redirect(['voting/index']);
                

            return $this->render('vote',[
                'model' => $model,
            ]);

        } catch (\yii\db\Exception $e){
            throw new ServerErrorHttpException("Votingtopic konnte nicht geladen werden");
        } catch (Exception $e){
            throw new ServerErrorHttpException("Votingtopic konnte nicht geladen werden");
        }

    }


    /**
     * Ruft eine Umfrage und dessen Resultate eines Topics ab
     * @param $p ID des Topics
     * @param $p2 ID des Votingweights [optional] hierdurch werden die bisherigen Antworten des Gewichtungsusers rausgesucht,
     * statt der IP
     * @return array
     */
    public function actionGetVotingOfTopic($p,$p2 = false)
    {
        $this->layout = 'nolayout';

        try {
            $topic  = Votingtopic::find()
                    ->where('id = '.$p)
                    ->with('votingweights')
                    ->asArray()
                    ->one();
            
            if(!$topic["active"])
                throw new NotFoundHttpException(Yii::t('errors', 'NOTSUPPORTED'));
            
            $voting = Votingquestion::find()
                    ->where('votingtopic_id = '.$p)
                    ->andWhere('active = 1')
                    ->with('votingtype')
                    ->with('votingoptions');

            $votingObj = $voting->one();
            
            $myAnswers      = [];
            $countresults   = 0;
            $sumValues      = 0;
            $resultstatistics = [];
            if($votingObj){
                //wenn mit Gewichtung, bisherige Antworten des GewichtungsUsers heraussuchen
                if($p2){
                    $myAnswers      = Votinganswer::getAnswersOfVotingweights($votingObj,$p2);
                }
                //sonst die Antworten der aktuellen IP heraussuchen
                else
                    $myAnswers      = Votinganswer::getAnswersOfIp($votingObj);
                $countresults   = Votinganswer::countResultsByAnswerer($votingObj);
                //Stimmen zählen nach Anzahl Ergebnisse oder nach Stimmengewichtung
                if($votingObj->hasweighting && $votingObj->votingtype->name == "radio")
                    $sumValues      = Votinganswer::countResultsByStimmen($votingObj);
                else
                    $sumValues      = Votinganswer::countResultsByValues($votingObj);

                //results nur ausgeben, wenn erlaubt und schon abgestimmt
                if($votingObj->showresults && count($myAnswers)>0){
                    if($votingObj->votingtype->name == "text")
                        $resultstatistics = Votinganswer::getResultStatisticsPerValue($votingObj, $sumValues);
                    else if($votingObj->hasweighting && $votingObj->votingtype->name == "radio")
                        $resultstatistics = Votinganswer::getResultStatisticsPerOption($votingObj, $sumValues, true);
                    else
                        $resultstatistics = Votinganswer::getResultStatisticsPerOption($votingObj, $sumValues);
                }
            }
            
            return $this->asJson([
                'topic'=>$topic,
                'voting'=>$voting->asArray()->one(),
                'myanswers' => $myAnswers,
                'countresults' => $countresults,
                'resultstatistics' => $resultstatistics,
                'sumValues' => $sumValues,
            ]);

        } catch (\yii\db\Exception $e){
            Yii::error($e->getMessage(),__METHOD__);
            throw new ServerErrorHttpException("Voting konnte nicht geladen werden");
        } catch (Exception $e){
            Yii::error($e->getMessage(),__METHOD__);
            throw new ServerErrorHttpException("Voting konnte nicht geladen werden");
        }

    }

    /**
     * Specihert eine Umfrage
     * @param $p ID der Question
     * @return array
     */
    public function actionSaveVotingOfQuestion($p)
    {
        $this->layout = 'nolayout';

        $connection = \Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $voting = Votingquestion::find()
                    ->where('id = '.$p)
                    ->andWhere('active = 1')
                    ->one();

            //$model = new Votinganswer();
            //Votinganswer::saveAnswers(Yii::$app->request->post('Votinganswers', []));
            
            $answers            = Yii::$app->request->post('Votinganswer', []);
            $votingweights_id   = Yii::$app->request->post('Votinganswer_votingweights_id', []);
            foreach($answers as $answer){
                if($votingweights_id && $voting->hasweighting){
                    $votingweights      = Votingweights::find()->where('id = '.$votingweights_id)->one();
                    Votinganswer::createAnswers($voting, $answer, $votingweights_id, $votingweights->stimmen);
                }
                else
                    Votinganswer::createAnswers($voting, $answer, $votingweights_id);
            }
            $transaction->commit();

            //wenn mit Gewichtung, bisherige Antworten des GewichtungsUsers heraussuchen
            if($votingweights_id)
                $myAnswers      = Votinganswer::getAnswersOfVotingweights($voting,$votingweights_id);
            //sonst die Antworten der aktuellen IP heraussuchen
            else
                $myAnswers      = Votinganswer::getAnswersOfIp($voting);
            $countresults   = Votinganswer::countResultsByAnswerer($voting);
            
            return $this->asJson([
                'myanswers' => $myAnswers,
                'countresults' => $countresults,                
            ]);

        } catch (\yii\db\Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException("Voting konnte nicht gespeichert werden");
        } catch (Exception $e){
            $transaction->rollBack();
            Yii::error($e->getMessage(), __METHOD__);
            throw new ServerErrorHttpException("Voting konnte nicht gespeichert werden");
        }

    }



    /**
     * Bearbeitung eines Topics
     * @param int $p ID des Topics
     * @return array
     *
    public function actionEditTopic($p)
    {
        $model = Votingtopic::find()->where('id = '.$p)->one();
        
        if($model->load(Yii::$app->request->post())){
            if($model->active == "on") 
                $model->active = 1;
            else $model->active = 0;
                
            if($model->save())
                return $this->redirect(['voting/edit']);
            else
                Yii::debug(Yii::$app->request->post());
        }
        else {
            Yii::debug($model->getErrors(),__METHOD__);
        }
        
        return $this->render('editTopic',[
            'model' => $model
        ]);
            
    }
    



    /**
     * Create Topic
     * @return array
     *
    public function actionCreateTopic()
    {
        $topError   = false;
        $model      = new Votingtopic(); 
        $model->id = 0;
        if($model->load(Yii::$app->request->post()) && $model->validate()){
            $model->sort = Votingtopic::getMaxSort()+1;
            if($model->save())
                return $this->redirect(['voting/edit']);
        }
        else {
            Yii::debug($model->getErrors(),__METHOD__);
        }
        
        return $this->render('createTopic',[
            'model'     => $model,
            'topError'  => $topError,
        ]);
            
    }

    
    
    /**
     * Sortiert ein Topic hoch
     * @param int $p ID des Topics
     * @return array
     */
    public function actionSortUpTopic($p)
    {
        try {
            /******* Begin Speichern ************/
            $ret = array();
            $model      = Votingtopic::find()->where('id = '.$p)->one();

            //höchsten Sort-Wert finden
            //nur durchführen, wenn nicht schon höchstes Element
            if ($model->sort < Votingtopic::getMaxSort())
            {
                $model->sortUp();
                return $this->redirect(['voting/edit']);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Item nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
            
    }

    /**
     * Bearbeitung der Umfragen
     * Zeigt edit, sort und lösch-Buttons an
     * @param int $p ID des Topics
     * @return array
     */
    public function actionEditTopicVotings($p)
    {
        $model      = Votingtopic::find()->where('id = '.$p)->one();

        return $this->render('editTopicVotings',[
            'model' => $model,
        ]);

    }

    

    /**
     * Löschen der Umfrage
     * @param int $p ID der Votingquestion
     * @return array
     */
    public function actionDeleteVoting($p)
    {
        $model = Votingquestion::find()->where('id = '.$p)->one();
        $sort = $model->sort;
        if($model->delete()){
            //alle folgenden runtersortieren
            Votingquestion::decreaseSortAfter($model->votingtopic_id, $sort);
            return $this->redirect(['voting/edit-topic-votings', 'p'=>$model->votingtopic_id]);
        }

    }

    /**
     * Sortiert eine Umfrage hoch
     * @param int $p ID des Votingquestion
     * @return array
     */
    public function actionSortUpVoting($p)
    {
        try {
            /******* Begin Speichern ************/
            $ret = array();
            $model      = Votingquestion::find()->where('id = '.$p)->one();

            //höchsten Sort-Wert finden
            //nur durchführen, wenn nicht schon höchstes Element
            if ($model->sort < Votingquestion::getMaxSort($model->votingtopic_id))
            {
                $model->sortUp();
                return $this->redirect(['voting/edit-topic-votings','p'=>$model->votingtopic_id]);
            }
            /******* End Speichern ************/
            
            // errormessages setzen
            $ret['errormessages'] = $model->getErrors();

        } catch (UnprocessableEntityHttpException $e){
            $ret['errormessages']['topError'] = ["Ein Fehler ist beim speichern aufgetreten, bitte versuchen Sie es später erneut."];
            Yii::error($e,__METHOD__);
        } catch (Exception $e){
            $ret['errormessages']['topError'] = ["Item nicht gefunden."];
            // throw new ServerErrorHttpException("Cannot find Useraddress for User.");
        }
            
    }

    
    /**
     * Bearbeitungsform einer Umfragen
´     * @param int $p ID der Votingquestion
     * @return array
     *
    public function actionEditQuestion($p)
    {
        $model      = Votingquestion::find()->where('id = '.$p)->one();
        $allVotingtypes = Votingtype::find()->asArray()->all();

        if($model->load(Yii::$app->request->post() )){
            if($model->active == "on") 
                $model->active = 1;
            else $model->active = 0;
            if($model->showresults == "on") 
                $model->showresults = 1;
            else $model->showresults = 0;
                
            if($model->save()){
                return $this->redirect(['voting/edit-topic-votings','p'=>$model->votingtopic_id]);
            }
            else
                Yii::debug(Yii::$app->request->post());
        }
        else {
            Yii::debug($model->getErrors(),__METHOD__);
        }
        
        return $this->render('editQuestion',[
            'model' => $model,
            'allVotingtypes' => $allVotingtypes,
        ]);

    }

    /**
     * Speichern der Votingoptions einer question
     * @param int $p ID der Votingquestion
     * @return array
     *
    public function actionGetOrSaveVotingoptions($p)
    {
        //$settings = Votingweights::find()->indexBy('id')->where('votingtopic_id = '.$p)->all();
        $settings = Votingoption::find()->where('votingquestion_id = '.$p)->all();

        //delete vorherige
        Votingoption::deleteAll('votingquestion_id = '.$p);

        //nochmal durchgehen, um neue Einträge zu finden und zu speichern /wurden oben automatisch igrnoeriet
        $items = Yii::$app->request->post("Votingoptions");
        if($items){
            foreach($items as $item){
                //if($item["id"] < 0){
                    $new = new Votingoption();
                    $new->votingquestion_id = $p;
                    $new->id = 0;
                    $new->value = $item["value"];
                    $new->save();
                //}
            }
            //neuladen und ausgeben
            $settings = Votingoption::find()->where('votingquestion_id = '.$p)->all();
        }
        
        return $this->asJson([
            'votingoptions'=> $settings,
        ]);
    }


    /**
     * Zeigt Ergebnis eines Voting
     * @param $p ID der Votingquestion
     * @return array
     */
    public function actionShowResults($p)
    {
        try {
            $model = Votingquestion::find()->where('id = '.$p)->one();
                

            return $this->render('show-results',[
                'model' => $model,
            ]);

        } catch (\yii\db\Exception $e){
            throw new ServerErrorHttpException("Votingquestion konnte nicht geladen werden");
        } catch (Exception $e){
            throw new ServerErrorHttpException("Votingquestion konnte nicht geladen werden");
        }

    }

    

    /**
     * Abruf der Ergebnisse eines Votings
     * @param $p ID der Votingquestion
     * @param $p ID der Votingweights
     * @return array
     */
    public function actionDeleteVotinganswer($p,$p2)
    {
        try {
            $model = Votinganswer::find()->where('votingquestion_id = '.$p)->andWhere('votingweights_id = '.$p2)->one();
            $id = $model->votingquestion_id;
            
            if($model->delete()){
                return $this->redirect(['voting/show-results','p'=>$id]);                
            }
                
            
        } catch (\yii\db\Exception $e){
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException("Votinganswer konnte nicht gelöscht werden");
        } catch (Exception $e){
            Yii::error($e->getMessage());
            throw new ServerErrorHttpException("Votinganswer konnte nicht gelöscht werden");
        }
    }
    
}
