<?php

namespace app\models\content;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\db\Expression;
use app\models\user\User;
use app\models\content\Document;
use app\models\content\DocumentList;

/**
 * This is the model class for table "document_item".
 *
 * @property int $id
 * @property int $document_list_id
 * @property int $document_id
 * @property int $sort
 * @property string $created
 * @property int $created_by
 *
 * @property Document $document
 * @property User $createdBy
 * @property DocumentList $documentList
 */
class DocumentItem extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'document_item';
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
    public function rules()
    {
        return [
            [['document_list_id', 'document_id'], 'required'],
            [['document_list_id', 'document_id', 'sort', 'created_by'], 'integer'],
            [['created'], 'safe'],
            [['document_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['document_id' => 'id']],
            [['created_by'], 'exist', 'skipOnError' => true, 'targetClass' => User::className(), 'targetAttribute' => ['created_by' => 'id']],
            [['document_list_id'], 'exist', 'skipOnError' => true, 'targetClass' => DocumentList::className(), 'targetAttribute' => ['document_list_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'document_list_id' => 'Document List ID',
            'document_id' => 'Document ID',
            'sort' => 'Sort',
            'created' => 'Created',
            'created_by' => 'Created By',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocument()
    {
        return $this->hasOne(Document::className(), ['id' => 'document_id']);
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
    public function getDocumentList()
    {
        return $this->hasOne(DocumentList::className(), ['id' => 'document_list_id']);
    }
}
