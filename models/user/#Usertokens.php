<?php

namespace app\models\user;

use Yii;

/**
 * This is the model class for table "usertokens".
 *
 * @property string $id
 * @property string $user_id
 * @property string $token
 */
class Usertokens extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'usertokens';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'user_id'], 'number'],
            [['token'], 'string'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'token' => 'Token',
        ];
    }
}
