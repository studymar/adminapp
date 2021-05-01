<?php

namespace app\models\content;

use Yii;
use yii\base\Exception;
use app\models\content\Teaserlist;
use app\models\content\Text;

/**
 * This is the model class for table "page_has_template".
 *
 * @property integer $id
 * @property integer $page_id
 * @property integer $template_id
 * @property integer $sort
 * @property integer $release_id
 *
 * @property Calendar[] $calendars
 * @property Downloadlist[] $downloadlists
 * @property Fotogalerie[] $fotogaleries
 * @property Linklist[] $linklists
 * @property ImageList[] $imageLists
 * @property Documentlist[] $documentlists
 * @property Page $page
 * @property Release $release
 * @property Template $template
 * @property Teaserlist[] $teaserlists
 * @property Text[] $texts
 */
class PageHasTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'page_has_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['page_id', 'template_id', 'sort', 'release_id'], 'required'],
            [['page_id', 'template_id', 'sort', 'release_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'page_id' => 'Page ID',
            'template_id' => 'Template ID',
            'sort' => 'Sort',
            'controllername' => 'Controllername',
            'release_id' => 'Release ID',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCalendars()
    {
        return $this->hasMany(Calendar::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDownloadlists()
    {
        return $this->hasMany(Downloadlist::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getFotogaleries()
    {
        return $this->hasMany(Fotogalerie::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklists()
    {
        return $this->hasMany(Linklist::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'release_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeaserlists()
    {
        return $this->hasMany(Teaserlist::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTexts()
    {
        return $this->hasMany(Text::className(), ['page_has_template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageLists()
    {
        return $this->hasMany(ImageList::className(), ['page_has_template_id' => 'id'])->with(['uploadedimages']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocumentlists()
    {
        return $this->hasMany(Documentlist::className(), ['page_has_template_id' => 'id'])->with(['documents']);
    }
    
    /**
     * @inheritdoc
     * @return PageHasTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PageHasTemplateQuery(get_called_class());
    }
    
    /**
     * Erstellt eine neue Instanz und legt sie in der DB an
     * @param Page $page
     * @param int $template_id
     * @param boolean $is_released [false]
     * @param boolean $addAtBottom [false] Default on Top
     * @return PageHasTemplate
     * @throw Exception
     */
    public static function create($page,$template_id, $is_released = false, $addAtBottom = false){
        try {
            $anzTemplatesOnPage = count($page->pageHasTemplates);

            $page_has_template = new PageHasTemplate();
            $page_has_template->page_id     = $page->id;
            $page_has_template->template_id = $template_id;
            $page_has_template->sort        = $anzTemplatesOnPage+1;
            if($addAtBottom)
                $page_has_template->sort    = 1;
            $release                        = Release::create($is_released);
            $page_has_template->release_id  = $release->id;
            if($page_has_template->save()){
                if($addAtBottom){
                    $page_has_template->increaseFollowing ();
                }
                return $page_has_template;
            }
            else {
                throw new Exception($page_has_template->getErrors());
            }
        } catch (\Exception $e) {
            throw new Exception(Yii::t('errors', 'DATASAVINGERROR'));
        } catch (Exception $e) {
            throw new Exception(Yii::t('errors', 'DATASAVINGERROR'));
        }
        
    }

    /**
     * Löscht dieses Item und das TemplateItem (Text, Teaserliste, etc.) darunter
     * @return boolean
     */
    public function deletePageHasTemplate(){
        //erst TemplateItem löschen
        $itemClass = ucfirst($this->template->objectname);
        $itemClass = 'app\\models\\content\\'.$itemClass;
        $item = $itemClass::findByPageHasTemplateId($this->id);
        $item->deleteItem(); //=Text,Teaser, etc. löschen
        $this->decreaseFollowing();

        //dann PageHasTemplate löschen
        if($this->delete())
            return true;
        return false;
    }    
    
    /**
     * Sortiert alle FolgeItems (mit höherem Sort) eins hoch (z.b. beim Löschen)
     */
    public function decreaseFollowing()
    {
        $items  = PageHasTemplate::find()->where(['>','sort',$this->sort])->andWhere(['=','page_id',$this->page_id])->all();
        foreach ($items as $item){
            $item->sort = $item->sort-1;
            if($item->save()){
            }
            else {
                throw new \yii\web\UnprocessableEntityHttpException(json_encode($item->getErrors()));
            }
        }
    }    

    /**
     * Sortiert alle FolgeItems (mit höherem Sort) eins hoch (z.b. beim Löschen)
     */
    public function increaseFollowing()
    {
        $items  = PageHasTemplate::find()
                ->where(['!=','id',$this->id])
                ->andWhere(['=','page_id',$this->page_id])
                ->andWhere(['>=','sort',$this->sort])
                ->all();
        foreach ($items as $item){
            $item->sort = $item->sort+1;
            if($item->save()){
            }
            else {
                throw new \yii\web\UnprocessableEntityHttpException(json_encode($item->getErrors()));
            }
        }
    }    
    
    
    /**
     * Sortiert alle uebergebenen PageHasTemplates
     * Der Reihenfolge nach wird das Objekt anhand der ID geladen und sort gespeichert
     * @param integer[] $pageHasTemplateIds
     * @return boolean
     */
    public static function sortTemplates($pageHasTemplateIds){
        $i = 1;
        $pageHasTemplateIds = array_reverse($pageHasTemplateIds);
        foreach($pageHasTemplateIds as $id){
            $model = PageHasTemplate::find()->where(['id'=>$id])->one();
            $model->sort = $i++;
            if(!$model->save())
                return false;
        }
        return true;
    }
    
}
