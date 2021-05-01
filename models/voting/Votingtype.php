<?php

namespace app\models\voting;

use Yii;

/**
 * This is the model class for table "votingtype".
 *
 * @property int $id
 * @property string $name
 * @property string $description
 *
 * @property Votingquestion[] $votingquestions
 */
class Votingtype extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'votingtype';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'description'], 'string', 'max' => 45],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'description' => 'Description',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVotingquestions()
    {
        return $this->hasMany(Votingquestion::className(), ['votingtype_id' => 'id']);
    }
}
