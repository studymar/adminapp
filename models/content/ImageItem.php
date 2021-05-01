<?php

namespace app\models\content;

use Yii;
use yii\db\Expression;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use app\models\user\User;

/**
 * This is the model class for table "image_item".
 *
 * @property int $id
 * @property int $image_list_id
 * @property int $uploadedimage_id
 * @property int $sort
 * @property string $created
 * @property int $created_by
 *
 * @property User $createdBy
 * @property ImageList $imageList
 * @property Uploadedimage $uploadedimage
 */
class ImageItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'image_item';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['image_list_id', 'uploadedimage_id'], 'required'],
            [['image_list_id', 'uploadedimage_id', 'sort', 'created_by'], 'integer'],
            [['created'], 'safe'],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['image_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => ImageList::className(), 'targetAttribute' => ['image_list_id' => 'id']],
            [['uploadedimage_id'], 'exist', 'skipOnError' => true, 'targetClass' => Uploadedimage::className(), 'targetAttribute' => ['uploadedimage_id' => 'id']],
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
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }     
    
    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'image_list_id' => 'Image List ID',
            'uploadedimage_id' => 'Uploadedimage ID',
            'sort' => 'Sort',
            'created' => 'Created',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getCreatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getImageList()
    {
        return $this->hasOne(ImageList::className(), ['id' => 'image_list_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUploadedimage()
    {
        return $this->hasOne(Uploadedimage::className(), ['id' => 'uploadedimage_id']);
    }
}
