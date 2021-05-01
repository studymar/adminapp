<?php

namespace app\models\content\navigation;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\user\User;
use app\models\content\Release;
use app\models\content\Page;


/**
 * This is the model class for table "subnavigation".
 *
 * @property int $id
 * @property int $navigation_id
 * @property int $sort
 * @property string $name
 * @property string $path
 * @property string $page_id
 * @property int $release_id
 * @property int $created_by
 * @property int $updated_by
 *
 * @property Navigation $navigation
 * @property Page $page
 * @property Release $release
 */
class Subnavigation extends \yii\db\ActiveRecord
{
    public function __construct($p = false) {
        $this->navigation_id = $p;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'subnavigation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['navigation_id','sort', 'release_id'], 'integer'],
            [['navigation_id',], 'required'],
            [['name','path'], 'required', 'on'=>'extern'],
            [['path'], 'url'],
            [['page_id'], 'integer'],
            [['created','updated'], 'string'],
            [['name'], 'string', 'max' => 45],
            //[['release_id'], 'exist', 'skipOnError' => true, 'targetClass' => Release::className(), 'targetAttribute' => ['release_id' => 'id']],
            
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'navigation_id' => 'Navigation ID',
            'sort' => 'Sort',
            'name' => 'Name',
            'path' => 'Url',
            'page_id' => "Seite",
            'external' => 'External',
            'release_id' => 'Release ID',
        ];
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
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'updated',
                'updatedAtAttribute' => false,
                'value' => new Expression('NOW()'),
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }    
    
    public function fields()
    {
        return [
            'id',
            'navigation_id',
            'sort',
            'name',
            'path',
            'page_id',
            'created',
            'updated',
            'created_by',
            'updated_by',
            'release'
        ];
    }
    
    public function extraFields()
    {
        return [
            'release'
        ];
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
    public function getNavigation()
    {
        return $this->hasOne(Navigation::className(), ['id' => 'navigation_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'release_id'])
            ->select([
                'id',
                'is_released',
                'from_date',
                'to_date',
            ]);                
    }
    
    /**
     * Gibt die Anzahl items zurück
     * @return int
     */
    public static function getAnzItems($p)
    {
        return Subnavigation::find()->where(['navigation_id'=>$p])->count();
    }

    /**
     * Sortiert ein Item eine Position höher in der Subnavigation
     * @return Subnavigation
     */
    public function sortUp()
    {
        $beforeItem  = Subnavigation::find()->where(['navigation_id'=>$this->navigation_id,'sort'=>($this->sort - 1)])->one();
        $this->sort = $this->sort - 1;
        $beforeItem->sort = $beforeItem->sort + 1;
        if($this->save() && $beforeItem->save()){
            return $this;
        }
        else {
            throw new UnprocessableEntityHttpException(json_encode($this->getErrors()));
        }
    }

    /**
     * Sortiert alle FolgeItems eins hoch (z.b. beim Löschen)
     * @return boolean
     */
    public function decreaseFollowing()
    {
        $items  = Subnavigation::find()->where(['navigation_id'=>$this->navigation_id])->andWhere(['>','sort',$this->sort])->all();
        foreach ($items as $item){
            $item->sort = $item->sort-1;
            if($item->save()){
                return $this;
            }
            else {
                throw new UnprocessableEntityHttpException(json_encode($item->getErrors()));
            }
        }
    }

    /**
     * Erstellt ein Subnavigationsitem und fügt es ans Ende der Liste
     * @return boolean
     * @param Release $release
     */
    public function createNavigationItem(Release $release, $pagetype = false)
    {
        //release anlegen
        $release->save();

        //dann navigation anlegen
        $this->release_id = $release->id;
        $this->sort = Subnavigation::getAnzItems($this->navigation_id)+1;
        //Seite dazu anlegen
        $page = Page::createPage($this->name, $this->release->is_released, $pagetype);
        $this->page_id = $page->id;
        if($this->save()){
            //Parent in der Page eintragen
            $page->parentpage_id = $this->navigation->page_id;
            $page->save();
            return $this;
        }
        else {
            throw new \yii\web\UnprocessableEntityHttpException(json_encode($this->getErrors()));
        }
        return false;
    }    

    /**
     * Erstellt ein Navigationsitem als Extern-Link und fügt es ans Ende der Liste
     * @return boolean
     * @param Release $release
     */
    public function createNavigationItemAsLink(Release $release)
    {
        //release anlegen
        $release->save();

        //dann navigation anlegen
        $this->release_id = $release->id;
        $this->sort = Subnavigation::getAnzItems($this->navigation_id)+1;
        //Seite dazu anlegen
        if($this->save()){
            return $this;
        }
        else {
            throw new \yii\web\UnprocessableEntityHttpException(json_encode($this->getErrors()));
        }
        return false;
    }

    
    /**
     * Ob das Item grade zur geladenen Seite gehört 
     * @return boolean
     */
    public function isActive()
    {
        if(isset(Yii::$app->params['key']) && $this->path == Yii::$app->params['key'])
            return true;
        return false;
    }
    
    /**
     * Erstellt ein leeres Navigationsitem und fügt es ans Ender Liste
     * @return boolean
     */
    public function createEmptyItem($p)
    {
        //...dann speichern
        $release = Release::create();
        $model = new Subnavigation();
        $model->navigation_id = $p;
        $model->release_id = $release->id;
        $model->sort = Subnavigation::getAnzItems($p)+1;
        $model->name = 'Neuer Menüpunkt';
        if($model->save()){
            return $model;
        }
        else {
            throw new UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
        return false;
    }
    
}
