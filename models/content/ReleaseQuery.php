<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[Release]].
 *
 * @see Release
 */
class ReleaseQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Release[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Release|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}