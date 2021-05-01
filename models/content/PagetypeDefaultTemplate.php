<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "pagetype_default_template".
 *
 * @property integer $id
 * @property integer $pagetype_id
 * @property integer $template_id
 * @property integer $sort
 * @property integer $is_released_by_default
 *
 * @property Pagetype $pagetype
 * @property Template $template
 */
class PagetypeDefaultTemplate extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagetype_default_template';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['pagetype_id', 'template_id', 'sort'], 'required'],
            [['pagetype_id', 'template_id', 'sort', 'is_released_by_default'], 'integer'],
            [['pagetype_id', 'template_id'], 'unique', 'targetAttribute' => ['pagetype_id', 'template_id'], 'message' => 'The combination of Pagetype ID and Template ID has already been taken.']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'pagetype_id' => 'Pagetype ID',
            'template_id' => 'Template ID',
            'sort' => 'Sort',
            'is_released_by_default' => 'Beim Anlegen schon freigegeben?',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagetype()
    {
        return $this->hasOne(Pagetype::className(), ['id' => 'pagetype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(Template::className(), ['id' => 'template_id']);
    }

    /**
     * @inheritdoc
     * @return PagetypeDefaultTemplateQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PagetypeDefaultTemplateQuery(get_called_class());
    }
}
