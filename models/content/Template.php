<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "template".
 *
 * @property integer $id
 * @property integer $type
 * @property integer $controllername
 * @property integer $objectname
 * @property string $created
 * @property integer $created_by
 * @property integer $isselectable
 *
 * @property PageAllowsTemplate[] $pageAllowsTemplates
 * @property Page[] $pages
 * @property PageHasTemplate[] $pageHasTemplates
 * @property PagetypeHasTemplate[] $pagetypeHasTemplates
 * @property User $createdBy
 */
class Template extends \yii\db\ActiveRecord
{
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['type', 'created_by', 'isselectable'], 'integer'],
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
            'type' => 'Type',
            'created' => 'Created',
            'created_by' => 'Created By',
            'isselectable' => 'Isselectable',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageAllowsTemplates()
    {
        return $this->hasMany(PageAllowsTemplate::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['id' => 'page_id'])->viaTable('page_allows_template', ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPageHasTemplates()
    {
        return $this->hasMany(PageHasTemplate::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagetypeHasTemplates()
    {
        return $this->hasMany(PagetypeHasTemplate::className(), ['template_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @inheritdoc
     * @return TemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new TemplateQuery(get_called_class());
    }
    
    /**
     * Gibt eine Liste der auswählbare Templates für neue Inhalte zurück
     * 
     */
    public static function getSelectableTemplates(){
        return self::find()->where('isselectable = 1')->all();
    }
    
}
