<?php

namespace app\models\content;

use Yii;

/**
 * This is the model class for table "history_page".
 *
 * @property integer $id
 * @property integer $parentpage_id
 * @property string $urlname
 * @property string $headline
 * @property string $linkname
 * @property integer $sort
 * @property string $seotitle
 * @property string $seokeywords
 * @property string $seodescription
 * @property integer $is_startpage
 * @property integer $content_admin_only
 * @property integer $details_admin_only
 * @property integer $show_same_level_only
 * @property string $created
 * @property integer $created_by
 * @property integer $release_id
 * @property integer $pagetype_id
 * @property string $updated
 * @property integer $updated_by
 * @property string $info
 *
 * @property User $createdBy
 * @property Pagetype $pagetype
 * @property Page $parentpage
 * @property Release $release
 * @property User $updatedBy
 */
class HistoryPage extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'history_page';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parentpage_id', 'sort', 'is_startpage', 'content_admin_only', 'details_admin_only', 'show_same_level_only', 'created_by', 'release_id', 'pagetype_id', 'updated_by'], 'integer'],
            [['headline', 'seotitle', 'seokeywords', 'seodescription', 'info'], 'string'],
            [['created', 'updated'], 'safe'],
            [['release_id', 'pagetype_id'], 'required'],
            [['urlname'], 'string', 'max' => 255],
            [['linkname'], 'string', 'max' => 25]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'parentpage_id' => 'Parentpage ID',
            'urlname' => 'Urlname',
            'headline' => 'Headline',
            'linkname' => 'Linkname',
            'sort' => 'Sort',
            'seotitle' => 'Seotitle',
            'seokeywords' => 'Seokeywords',
            'seodescription' => 'Seodescription',
            'is_startpage' => 'Is Startpage',
            'content_admin_only' => 'Only Admin',
            'details_admin_only' => 'Edit Admin',
            'created' => 'Created',
            'created_by' => 'Created By',
            'release_id' => 'Release ID',
            'pagetype_id' => 'Pagetype ID',
            'updated' => 'Updated',
            'updated_by' => 'Updated By',
            'info' => 'Info',
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
    public function getPagetype()
    {
        return $this->hasOne(Pagetype::className(), ['id' => 'pagetype_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParentpage()
    {
        return $this->hasOne(Page::className(), ['id' => 'parentpage_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelease()
    {
        return $this->hasOne(Release::className(), ['id' => 'release_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUpdatedBy()
    {
        return $this->hasOne(User::className(), ['id' => 'updated_by']);
    }

    /**
     * @inheritdoc
     * @return HistoryPageQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new HistoryPageQuery(get_called_class());
    }
}
