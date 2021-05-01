<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[Teaser]].
 *
 * @see Teaser
 */
class TeaserQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Teaser[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Teaser|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}