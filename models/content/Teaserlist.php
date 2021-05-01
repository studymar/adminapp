<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\models\user\User;

/**
 * This is the model class for table "teaserlist".
 *
 * @property integer $id
 * @property string $created
 * @property integer $created_by
 * @property integer $page_has_template_id
 *
 * @property Teaser[] $teasers
 * @property User $createdBy
 * @property PageHasTemplate $pageHasTemplate
 */
class Teaserlist extends \yii\db\ActiveRecord
{
    public $maxVisibleItems = 10;
    public $maxEditItems    = 15;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'teaserlist';
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
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => false,
                'updatedByAttribute' => 'created_by',
            ],
        ];
    }    
    
    
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['created'], 'safe'],
            [['created_by', 'page_has_template_id'], 'integer'],
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
            'created' => 'Created',
            'created_by' => 'Created By',
            'page_has_template_id' => 'Page Has Template ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeasers()
    {
        return $this->hasMany(Teaser::className(), ['teaserlist_id' => 'id']);
    }
    
    public function getVisibleTeasers()
    {
        return $this->getAllTeasers()
            ->andWhere(['release.is_released'=>1])
            ->andWhere('release.from_date <= NOW() OR release.from_date IS NULL')
            ->andWhere('release.to_date >= NOW() OR release.to_date IS NULL');
    }

    public function getAllTeasers()
    {
        return $this->hasMany(Teaser::className(), ['teaserlist_id' => 'id'])
            ->select([
                'teaser.id',
                'teaser.teaserlist_id',
                'teaser.headline',
                'teaser.text',
                'teaser.created',
                'teaser.updated',
                'teaser.link_item_id',
                'teaser.sort',
                'teaser.image_list_id',
                'teaser.document_list_id',
                'teaser.release_id',
                ])
            ->joinWith('release')
            ->with('linkItem')
            ->with('linkItem.targetPage')
            ->with('documentList.documents')
            ->with('imageList.uploadedimages')
            ->where(['teaser.teaserlist_id'=>$this->id])
            ->orderBy('teaser.sort desc');
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
     * @inheritdoc
     * @return TeaserlistQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TeaserlistQuery(get_called_class());
    }
    
    /**
     * Erstellt eine neue Instanz und legt sie in der DB an
     * @param int $page_has_template_id
     * @return Teaserlist
     * @throw Exception
     */
    public static function create($page_has_template_id){
        try {
            $item                           = new Teaserlist();
            $item->page_has_template_id     = $page_has_template_id;
            if($item->save())
                return $item;
            else {
                Yii::error(json_encode($item->getErrors()));
                throw new Exception(Yii::t('errors', 'DATASAVINGERROR'));
            }
        } catch (\Exception $e) {
            Yii::error($e->getMessage());
            throw new Exception(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
    }

    /**
     * Löscht die ganze Teaserlist
     */
    public function deleteAllTeasersOfList(){
        $items = $this->getTeasers()->all();
        foreach($items as $item){
            var_dump($item);
            exit;
            $release = $item->release;
            $item->delete();
            $release->delete();
        }
        return true;
        
    }
    
    
    /**
     * Löscht die ganze Teaserlist
     */
    public function deleteList(){
        try {
            
            //objecte vor löschen ablegen
            $pageHasTemplate    = $this->pageHasTemplate;
            $release            = $pageHasTemplate->release;
            
            //erst alle Teaser aus der Liste löschen
            $this->deleteAllTeasersOfList();
            
            //dann die Liste selbst löschen
            $this->delete();
            
            //dann PageHasTemplate löschen und folgende hochsortieren
            $pageHasTemplate->decreaseFollowing();
            $pageHasTemplate->delete();
            $release->delete();
            
            //dann das relase löschen
        } catch (Exception $e) {
            Yii::error($e->getMessage());
            throw new Exception(Yii::t('errors', 'DATASAVINGERROR'));
        }
    }

    /**
     * Sortiert alle uebergebenen Teasers
     * Der Reihenfolge nach wird das Objekt anhand der ID geladen und sort gespeichert
     * @param integer[] $ids des Teasers
     * @return boolean
     */
    public static function sortTeasers($ids){
        $i = 1;
        $ids = array_reverse($ids);
        foreach($ids as $id){
            $model = Teaser::find()->where(['id'=>$id])->one();
            $model->sort = $i++;
            if(!$model->save())
                return false;
        }
        return true;
    }


    
}
