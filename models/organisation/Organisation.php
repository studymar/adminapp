<?php

namespace app\models\organisation;

use Yii;
use app\models\organisation\Organisationtype;

/**
 * This is the model class for table "organisation".
 *
 * @property integer $id
 * @property string $name
 * @property string $city
 * @property string $organisationnumber
 * @property string $website
 * @property integer $digitalcontact_agreement
 * @property string $created
 * @property string $deleted
 * @property integer $organisationtype_id
 * @property integer $value
 * @property string $text
 *
 * @property Contact[] $contacts
 * @property Location[] $locations
 * @property Organisationtype $organisationtype
 * @property Teamstatistics[] $teamstatistics
 * @property User[] $users
 * @property Vereinsmeldung[] $vereinsmeldungs
 * @property Wocoach[] $wocoaches
 */
class Organisation extends \yii\db\ActiveRecord
{
    //genutzt bei getAllOrganisationsAsTextValuePair für dropdowns
    public $value;
    public $text;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'organisation';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'city', 'organisationnumber', 'website'], 'string'],
            [['digitalcontact_agreement', 'organisationtype_id'], 'integer'],
            [['created', 'deleted'], 'safe'],
            [['organisationtype_id'], 'required'],
            [['value','text'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Vereinsname',
            'city' => 'Ort',
            'organisationnumber' => 'Vereinsnummer',
            'website' => 'Website',
            'digitalcontact_agreement' => 'Zustimmung Online-Kontakt',
            'created' => 'Erstellt am',
            'deleted' => 'Gelöscht am',
            'organisationtype_id' => 'Typ',
            'value' => 'Value',
            'text' => 'Text',
        ];
    }

    /**
     * Gibt ein Array mit Text(Name) und Value (ID) zurück
     * @return \yii\db\ActiveQuery
     */
    public static function getAllOrganisationsAsTextValuePair()
    {
        return Organisation::find()->select(['value'=>'id', 'text'=>'name'])->where('deleted is null')->asArray()->all();
    }
    
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getContacts()
    {
        return $this->hasMany(Contact::className(), ['organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getLocations()
    {
        return $this->hasMany(Location::className(), ['organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrganisationtype()
    {
        return $this->hasOne(Organisationtype::className(), ['id' => 'organisationtype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTeamstatistics()
    {
        return $this->hasMany(Teamstatistics::className(), ['organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVereinsmeldungs()
    {
        return $this->hasMany(Vereinsmeldung::className(), ['organisation_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getWocoaches()
    {
        return $this->hasMany(Wocoach::className(), ['organisation_id' => 'id']);
    }
    
    
    
}
