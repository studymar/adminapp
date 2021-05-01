<?php

namespace app\models\role;

/**
 * This is the ActiveQuery class for [[RoleAllowsPagetype]].
 *
 * @see RoleAllowsPagetype
 */
class RoleAllowsPagetypeQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * @inheritdoc
     * @return RoleAllowsPagetype[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return RoleAllowsPagetype|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
