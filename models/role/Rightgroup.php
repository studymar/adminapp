<?php

namespace app\models\role;

use Yii;

/**
 * This is the model class for table "rightgroup".
 *
 * @property integer $id
 * @property string $name
 * @property integer $sort
 * @property string $created
 *
 * @property Right[] $rights
 */
class Rightgroup extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'rightgroup';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
            [['sort'], 'integer'],
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
            'sort' => 'Sort',
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRights()
    {
        return $this->hasMany(Right::className(), ['rightgroup_id' => 'id'])->select(['id','name','info','rightgroup_id']);
    }

    /**
     * @inheritdoc
     * @return RightgroupQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new RightgroupQuery(get_called_class());
    }
}
