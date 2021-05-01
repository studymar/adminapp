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
 * This is the model class for table "navigation".
 *
 * @property int $id
 * @property int $sort
 * @property string $name
 * @property string $path
 * @property int $page_id
 * @property int $release_id
 * @property string $created
 * @property string $updated
 *
 * @property Page $page
 * @property Release $release
 * @property Subnavigation[] $subnavigations
 */
class Navigation extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'navigation';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['sort', 'release_id'], 'integer'],
            //[['name', 'release_id'], 'required'],
            [['name'], 'required'],
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
            'sort' => 'Sort',
            'name' => 'Name',
            'path' => 'Url',
            'page_id' => "Seite",
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
            'sort',
            'name',
            'path',
            'page_id',
            'created',
            'updated',
            'created_by',
            'updated_by',
            'release',
        ];
    }
    
    public function extraFields()
    {
        return [
            'release',
            'subnavigations',
        ];
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
     * @return \yii\db\ActiveQuery
     */
    public function getPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'page_id']);
    }    

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSubnavigations()
    {
        return $this->hasMany(Subnavigation::className(), ['navigation_id' => 'id']);
    }

    /**
     * Gibt die Subnavigation zurück, die öffentlich sichtbar ist
     * @return \yii\db\ActiveQuery
     */
    public function getSubnavigationsActive()
    {
        return $this->hasMany(Subnavigation::className(), ['navigation_id' => 'id'])
            ->joinWith('release',false)
            ->where(['release.is_released'=>1])
            ->andWhere('release.from_date <= NOW() OR release.from_date IS NULL')
            ->andWhere('release.to_date >= NOW() OR release.to_date IS NULL')
            ->orderBy('sort asc')
            ->select([
                'subnavigation.id',
                'subnavigation.navigation_id',
                'subnavigation.name',
                'subnavigation.sort',
                'subnavigation.path',
                'subnavigation.page_id',
                'subnavigation.release_id',                
            ]);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by'])->select(['id','firstname','lastname']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by'])->select(['id','firstname','lastname']);
    }
    
    /**
     * Gibt die Anzahl items zurück
     * @return int
     */
    public static function getAnzItems()
    {
        return Navigation::find()->count();
    }

    /**
     * Sortiert ein Item eine Position höher in der Navigati
     * @return Navigation
     */
    public function sortUp()
    {
        $beforeItem  = Navigation::find()->where(['sort'=>($this->sort - 1)])->one();
        $this->sort = $this->sort - 1;
        $beforeItem->sort = $beforeItem->sort + 1;
        if($this->save() && $beforeItem->save()){
            return $this;
        }
        else {
            throw new \yii\web\UnprocessableEntityHttpException(json_encode($this->getErrors()));
        }
    }

    /**
     * Sortiert alle FolgeItems eins hoch (z.b. beim Löschen)
     * @return boolean
     */
    public function decreaseFollowing()
    {
        $items  = Navigation::find()->where(['>','sort',$this->sort])->all();
        foreach ($items as $item){
            $item->sort = $item->sort-1;
            if($item->save()){
                return $this;
            }
            else {
                throw new \yii\web\UnprocessableEntityHttpException(json_encode($item->getErrors()));
            }
        }
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
     * Erstellt ein leeres Navigationsitem und fügt es ans Ende der Liste
     * @return boolean
     *
    public function createEmptyItem()
    {
        //...dann speichern
        $release = Release::create();
        $model = new Navigation();
        $model->release_id = $release->id;
        $model->sort = Navigation::getAnzItems()+1;
        $model->name = 'Neuer Menüpunkt';
        if($model->save()){
            return $model;
        }
        else {
            throw new \yii\web\UnprocessableEntityHttpException(json_encode($model->getErrors()));
        }
        return false;
    }
    
    /**
     * Erstellt ein Navigationsitem (incl.Page) und fügt es ans Ende der Liste
     * @return boolean
     * @param Release $release
     */
    public function createNavigationItem(Release $release, $pagetype = false)
    {
        //release anlegen
        $release->save();

        //dann navigation anlegen
        $this->release_id = $release->id;
        $this->sort = Navigation::getAnzItems()+1;
        //Seite dazu anlegen
        $page = Page::createPage($this->name, $this->release->is_released, $pagetype);
        $this->page_id = $page->id;
        if($this->save()){
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
        $this->sort = Navigation::getAnzItems()+1;
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
     * Gibt das Menu zurück mit allen öffentlich sichtbaren Einträgen
     * return Navigation[]
     */
    public static function getReleasedNavigationTree(){
        return Navigation::find()
            ->select([
                'navigation.id',
                'navigation.name',
                'navigation.sort',
                'navigation.path',
                'navigation.page_id',
                'navigation.release_id',
                ])
            ->joinWith('release')
            ->with('subnavigationsActive','subnavigationsActive.release','page')
            ->where(['release.is_released'=>1])
            ->andWhere('release.from_date <= NOW() OR release.from_date IS NULL')
            ->andWhere('release.to_date >= NOW() OR release.to_date IS NULL')
            ->orderBy('sort asc')
            //->asArray()
            ->all();
    }

    /**
     * Gibt das Menu zurück mit allen (auch nicht freigegeben) Einträgen
     * return Navigation[]
     */
    public static function getNavigationTree(){
        return Navigation::find()
            ->select([
                'navigation.id',
                'navigation.name',
                'navigation.sort',
                'navigation.path',
                'navigation.page_id',
                'navigation.release_id',
                ])
            ->joinWith('release')
            ->with('subnavigationsActive','subnavigationsActive.release','page')
            ->orderBy('sort asc')
            //->asArray()
            ->all();
        
    }

    
}
