<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[LinkItem]].
 *
 * @see LinkItem
 */
class LinkItemQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return LinkItem[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return LinkItem|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}