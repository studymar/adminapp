<?php

namespace app\models\organisation;

use Yii;

/**
 * This is the model class for table "organisationtype".
 *
 * @property integer $id
 * @property string $name
 * @property string $created
 *
 * @property Contactrole[] $contactroles
 * @property Organisation[] $organisations
 */
class Organisationtype extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organisationtype';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'string'],
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
            'created' => 'Created',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContactroles()
    {
        return $this->hasMany(Contactrole::className(), ['organisationtype_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisations()
    {
        return $this->hasMany(Organisation::className(), ['organisationtype_id' => 'id']);
    }
}
