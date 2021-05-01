<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use yii\base\Exception;
use app\models\user\User;
use app\models\helpers\DateConverter;

/**
 * This is the model class for table "text".
 *
 * @property integer $id
 * @property string $headline
 * @property string $text
 * @property string $created
 * @property integer $created_by
 * @property integer $updated
 * @property integer $updated_by
 * @property integer $page_has_template_id
 *
 * @property User $createdBy
 * @property PageHasTemplate $pageHasTemplate
 * @property User $updatedBy
 */
class Text extends \yii\db\ActiveRecord  implements IsDeletablePageHasTemplateInterface
{

    const TEMPLATE_ID = 1;
    
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'updated',
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }    
    
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['headline', 'text'], 'string'],
            [['created', 'updated'], 'safe'],
            [['created_by', 'updated_by', 'page_has_template_id'], 'integer'],
            [['page_has_template_id'], 'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'headline' => 'Headline',
            'text' => 'Text',
            'created' => 'Created',
            'created_by' => 'Created By',
            'updated' => 'Updated',
            'updated_by' => 'Updated By',
            'page_has_template_id' => 'Page Has Template ID',
        ];
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedimages()
    {
        return $this->hasMany(Uploadedimage::className(), ['id' => 'uploadedimage_id'])->viaTable('image_item_text', ['text_id' => 'id']);    
    }


    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplate()
    {
        return $this->hasOne(PageHasTemplate::className(), ['id' => 'page_has_template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return TextQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TextQuery(get_called_class());
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
        $this->delete();
        return true;
    }

    
/*    
    public static function create($page){
        $transaction = Yii::$app->db->beginTransaction();
        try {
            //verknüpfung herstellen
            $page_has_template = PageHasTemplate::create($page, Text::TEMPLATE_ID);

            $text = new Text();
            $text->page_has_template_id = $page_has_template->id;
            $text->headline             = Yii::t('app', "TEXT_HEADLINE_DEFAULT");
            $text->text                 = Yii::t('app', "TEXT_CONTENT_DEFAULT");
            if($text->save()){
                $transaction->commit();
                return $text;
            }
            else {
                throw new Exception($text->getErrors());
            }
        } catch (Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw new Exception($e->getMessage());
        }
        
    }
*/

    /**
     * 
     * @param int $page_has_template_id
     * @return \app\models\content\Text
     * @throws Exception
     */
    public static function create($page_has_template_id){
        $text = new Text();
        $text->page_has_template_id = $page_has_template_id;
        $text->headline             = null;
        $text->text                 = Yii::t('app', "TEXT_CONTENT_DEFAULT");
        if($text->save()){
            return $text;
        }
        else {
            throw new Exception( json_encode($text->getErrors()) );
        }
        
    }
    
    
    /**
     * Prüft, ob das Item an einem anderen Tag als dem Erstellungsdatum geändert wurde
     * Z.b. um danach Updatedatum auszugeben
     * @return boolean
     */
    public function isUpdatedAnotherDay(){
        $created = $this->created;
        $updated = $this->updated;
        //tageszahl aus created und updatet vergleichen
        if(DateConverter::convert($created, DateConverter::DATE_FORMAT_VIEW) != DateConverter::convert($updated, DateConverter::DATE_FORMAT_VIEW))
            return true;
        return false;
    }

    /**
     * Updatet das Objekt mit den Requestdaten
     * @param Array $objReq Text als Array
     * @throws Exception
     */
    public function updateFromRequest($objReq = false){
        Yii::debug("UpdateFromRequest Text",__METHOD__);
        //Normalfall, wenn nichts übergeben
        $req = Yii::$app->request->post(); // falls nicht übergeben
        //falls übergeben, Requestdaten aus Parameter
        if($objReq){
           $req = [];
           $req['Text'] = $objReq;
        }
        if($this->load($req) ){
            Yii::debug("Load Text",__METHOD__);
            if($this->validate()){
                if(!$this->save()){
                    throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);
                }
            }
            else {
                Yii::error('Got Request: '.json_encode(Yii::$app->request->post()),__METHOD__);
                Yii::error('Errors: ' . json_encode($this->getErrors()),__METHOD__);
                throw new Exception (Errormessages::$errors['DATASAVINGERROR']['message'], Errormessages::$errors['DATASAVINGERROR']['id']);
            }
        }
        else Yii::error ('Kein Text[] im Request gefunden',__METHOD__);

    }
    
    
    
}
