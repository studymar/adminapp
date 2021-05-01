<?php

namespace app\models\content;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "link_item".
 *
 * @property integer $id
 * @property string $name
 * @property integer $target_page_id
 * @property string $extern_url
 * @property string $created
 *
 * @property HistoryTeaser[] $historyTeasers
 * @property HistoryTermin[] $historyTermins
 * @property Page $targetPage
 * @property LinklistColumnItem[] $linklistColumnItems
 * @property Teaser[] $teasers
 * @property Termin[] $termins
 */
class LinkItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'link_item';
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
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'extern_url'], 'string'],
            [['target_page_id'], 'integer'],
            [['created'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'target_page_id' => 'Target Page ID',
            'extern_url' => 'Extern Url',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTeasers()
    {
        return $this->hasMany(HistoryTeaser::className(), ['link_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryTermins()
    {
        return $this->hasMany(HistoryTermin::className(), ['link_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTargetPage()
    {
        return $this->hasOne(Page::className(), ['id' => 'target_page_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLinklistColumnItems()
    {
        return $this->hasMany(LinklistColumnItem::className(), ['link_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeasers()
    {
        return $this->hasMany(Teaser::className(), ['link_item_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTermins()
    {
        return $this->hasMany(Termin::className(), ['link_item_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return LinkItemQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new LinkItemQuery(get_called_class());
    }

   public function create(){
        $item = new LinkItem();
        if($item->save()){
            return $item;
        }
        Yii::error (json_encode($item->getErrors ()),__METHOD__);
   }
    
   public function updateFromRequest(){
        if($this->load(Yii::$app->request->post()) && $this->validate()){
            if($this->extern_url == ""){ 
                $this->extern_url = null;
            }
            return $this->save();
        }
       exit;
   }
    
   public function hasLink(){
       return ($this->extern_url != null || $this->target_page_id != null);
   }
    
}
