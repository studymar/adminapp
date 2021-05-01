<?php

namespace app\models\user;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;


/**
 * This is the model class for table "useradress".
 *
 * @property integer $id
 * @property string $street
 * @property string $zip
 * @property string $city
 * @property string $addressinfo
 * @property string $telephone
 * @property string $mobile
 * @property string $created
 * @property string $updated
 *
 * @property User[] $users
 */
class Useraddress extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'useraddress';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['street'], 'required'],
            [['street', 'zip', 'city', 'addressinfo', 'telephone', 'mobile'], 'string'],
            [['zip', 'city'], 'required', 'message'=> 'PLZ/Ort darf nicht leer sein'],
            [['created', 'updated'], 'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'street' => 'Straße',
            'zip' => 'PLZ',
            'city' => 'Ort',
            'addressinfo' => 'Adresszusatz',
            'telephone' => 'Telefon',
            'mobile' => 'Mobil',
            'created' => 'Erstellt am',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsers()
    {
        return $this->hasMany(User::className(), ['useradress_id' => 'id']);
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
                'updatedAtAttribute' => 'updated',
                'value' => new Expression('NOW()'),
            ],
        ];
    }    
    
    
}
