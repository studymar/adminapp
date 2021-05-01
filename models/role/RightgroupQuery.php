<?php

namespace app\models\role;

/**
 * This is the ActiveQuery class for [[Rightgroup]].
 *
 * @see Rightgroup
 */
class RightgroupQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return Rightgroup[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return Rightgroup|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}