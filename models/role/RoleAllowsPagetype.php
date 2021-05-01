<?php

namespace app\models\role;

use Yii;
use app\models\content\Pagetype;

/**
 * This is the model class for table "role_allows_pagetype".
 *
 * @property integer $role_id
 * @property integer $pagetype_id
 * @property string $created
 *
 * @property Pagetype $pagetype
 * @property Role $role
 */
class RoleAllowsPagetype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'role_allows_pagetype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['role_id', 'pagetype_id'], 'required'],
            [['role_id', 'pagetype_id'], 'integer'],
            [['created'], 'safe'],
            [['pagetype_id'], 'exist', 'skipOnError' => true, 'targetClass' => Pagetype::className(), 'targetAttribute' => ['pagetype_id' => 'id']],
            [['role_id'], 'exist', 'skipOnError' => true, 'targetClass' => Role::className(), 'targetAttribute' => ['role_id' => 'id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'role_id' => 'Role ID',
            'pagetype_id' => 'Pagetype ID',
            'created' => 'Created',
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
    public function getRole()
    {
        return $this->hasOne(Role::className(), ['id' => 'role_id']);
    }

    /**
     * @inheritdoc
     * @return RoleAllowsPagetypeQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RoleAllowsPagetypeQuery(get_called_class());
    }
}
