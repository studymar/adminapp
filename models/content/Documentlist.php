<?php

namespace app\models\content;

use Yii;
use app\models\Errormessages;
use yii\base\Exception;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "document_list".
 *
 * @property int $id
 *
 * @property DocumentItem[] $documentItems
 * @property Document[] $documents
 * @property Download[] $downloads
 * @property Teaser[] $teasers
 * @property Termin[] $termins
 * @property Text[] $texts
 */
class Documentlist extends \yii\db\ActiveRecord implements IsDeletablePageHasTemplateInterface
{
    public $document_ids; //für speichern aus einer Form
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_list';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
        ];
    }
    
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            ['document_ids', 'each', 'rule' => ['number']],
        ];
    }


    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentItems()
    {
        return $this->hasMany(DocumentItem::className(), ['document_list_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['id' => 'document_id'])->viaTable('document_item', ['document_list_id' => 'id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownloads()
    {
        return $this->hasMany(Download::className(), ['document_list_id' => 'id']);
    }

    
    public static function create($page_has_template_id = null){
        $item = new DocumentList();
        $item->page_has_template_id = $page_has_template_id;
        if($item->save()){
            return $item;
        }
    }
    
    public function updateFromRequest($objReq = false){
        //alte ImageItems löschen
        DocumentItem::deleteAll('document_list_id = '.$this->id);
        //Normalfall, wenn nichts übergeben
        $req = Yii::$app->request->post(); // falls nicht übergeben
        //falls übergeben, Requestdaten aus Parameter
        if($objReq){
           $req = [];
           $req['Documentlist'] = $objReq;
        }
        
        if($this->load($req) ){
            if($this->validate(['document_ids'])){
                //neue erstellen
                $i = 1;
                Yii::debug("document_ids: ". json_encode($this->document_ids));
                if($this->document_ids){
                    foreach ($this->document_ids as $document_id){
                        $item = new DocumentItem();
                        $item->document_list_id = $this->id;
                        $item->sort = $i++;
                        $item->document_id = $document_id;
                        if(!$item->save()){
                            Yii::error('Document_id: '.$document_id,__METHOD__);
                            throw new Exception (Errormessages::$errors['DOCUMENTITEMSAVINGERROR']['message'], Errormessages::$errors['DOCUMENTITEMSAVINGERROR']['id']);
                        }
                    }
                }
                
            }
            else {
                Yii::error('Got Request: '.json_encode(Yii::$app->request->post()),__METHOD__);
                Yii::error('Errors: ' . json_encode($this->getErrors()),__METHOD__);
                throw new Exception (Errormessages::$errors['DOCUMENTLISTVALIDATIONERROR']['message'], Errormessages::$errors['DOCUMENTLISTVALIDATIONERROR']['id']);
            }
        }
    }

    
    /**
     * 
     * @param int $p
     * @return Text
     */
    public static function findByPageHasTemplateId($p){
        return self::find()->where(['page_has_template_id'=>$p])->one();
    }
    
    /**
     * Löscht das Item
     */
    public function deleteItem(){
        foreach($this->documentItems as $item){
            $item->delete();
        }
        $this->delete();
        return true;
    }
    
    
}
