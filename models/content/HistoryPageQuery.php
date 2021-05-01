<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[HistoryPage]].
 *
 * @see HistoryPage
 */
class HistoryPageQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return HistoryPage[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return HistoryPage|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}