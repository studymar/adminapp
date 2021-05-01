<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[Pagetype]].
 *
 * @see Pagetype
 */
class PagetypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Pagetype[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Pagetype|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}