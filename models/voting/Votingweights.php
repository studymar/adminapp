<?php

namespace app\models\voting;

use Yii;

/**
 * This is the model class for table "votingweights".
 *
 * @property int $id
 * @property int $votingtopic_id
 * @property string $name
 * @property int $stimmen
 * @property int $active
 * @property string $last_activation_time
 *
 * @property Votingtopic $votingtopic
 * @property Votingtopic $votinganswers
 */
class Votingweights extends \yii\db\ActiveRecord
{
    public $votingquestion_answers;
    
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votingweights';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['votingtopic_id'], 'required'],
            [['id','votingtopic_id', 'stimmen', 'active'], 'integer'],
            [['last_activation_time'], 'safe', 'except'=>'saveVotingweights'],
            [['name'], 'string', 'max' => 100],
            [['votingtopic_id'], 'exist', 'skipOnError' => true, 'targetClass' => Votingtopic::className(), 'targetAttribute' => ['votingtopic_id' => 'id'], 'except'=>'saveVotingweights'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'votingtopic_id' => 'Votingtopic ID',
            'name' => 'Name',
            'stimmen' => 'Stimmen',
            'active' => 'Active',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingtopic()
    {
        return $this->hasOne(Votingtopic::className(), ['id' => 'votingtopic_id']);
    }
    
    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotinganswers()
    {
        return $this->hasMany(Votinganswer::className(), ['votingweights_id' => 'id']);
    }

    
    /**
     * @return \yii\db\ActiveQuery
     */
    public static function getVotinganswersOfQuestion($votingquestion_id, $votingweights_id)
    {
        return Votinganswer::find()->where(['votingweights_id' => $votingweights_id,'votingquestion_id'=>$votingquestion_id])->all();
    }
    
    /**
     * Zählt die abgegebenen Values
     * @return int
     */
    public static function countActiveVW($votingtopic)
    {
        return Votingweights::find()->where(['votingtopic_id'=>$votingtopic->id, 'active'=>1])->count();
    }
    
    
    /**
     * Setzt die Stimmen als aktiv/anwesend
     * @param boolean $value
     * @return \yii\db\ActiveQuery
     */
    public function setActive($value = true)
    {
        if($value)
            $this->active = 1;
        else
            $this->active = 0;
        return $this->save();
    }

    

    
    
}
