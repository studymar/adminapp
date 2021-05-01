<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;

/**
 * This is the model class for table "pagetype".
 *
 * @property integer $id
 * @property string $name
 * @property string $description
 * @property string $created
 * @property integer $isselectable
 *
 * @property HistoryPage[] $historyPages
 * @property Page[] $pages
 * @property PagetypeDefaultTemplate[] $pagetypeDefaultTemplates
 * @property RoleAllowsPagetype[] $roleAllowsPagetypes
 * @property Role[] $roles
 */
class Pagetype extends \yii\db\ActiveRecord
{
    const HOMEPAGE = 1;
    const TEXTPAGE = 2;
    const FOTOGALERIEPAGE = 3;
    const TEASERPAGE = 4;
    const DOWNLOADPAGE = 5;
    const OTHERPAGE = 6;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'pagetype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['created'], 'safe'],
            [['isselectable'], 'integer'],
            [['description'], 'string', 'max' => 90]
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
            'description' => 'Description',
            'created' => 'Created',
            'isselectable' => 'Für neue Seiten auswählbar?',
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
        ];
    }    
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getHistoryPages()
    {
        return $this->hasMany(HistoryPage::className(), ['pagetype_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPages()
    {
        return $this->hasMany(Page::className(), ['pagetype_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getPagetypeDefaultTemplates()
    {
        return $this->hasMany(PagetypeDefaultTemplate::className(), ['pagetype_id' => 'id'])->orderBy(['sort'=>SORT_ASC]);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplates()
    {
        return $this->hasMany(Template::className(), ['id' => 'template_id'])->viaTable('pagetype_has_template', ['pagetype_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoleAllowsPagetypes()
    {
        return $this->hasMany(RoleAllowsPagetype::className(), ['pagetype_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRoles()
    {
        return $this->hasMany(Role::className(), ['id' => 'role_id'])->viaTable('role_allows_pagetype', ['pagetype_id' => 'id']);
    }

    /**
     * @inheritdoc
     * @return PagetypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new PagetypeQuery(get_called_class());
    }
    
    /**
     * @inheritdoc
     * @return PagetypeQuery the active query used by this AR class.
     */
    public function delete()
    {
        //erst alle templates auf der Seite entfernen
        $templates = $this->templates;
        foreach($templates as $template)
            $this->unlink('templates', $template, true );
        //dann loeschen
        return parent::delete();
    }
    
    
}
