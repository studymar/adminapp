<?php

namespace app\models\content;

/**
 * This is the ActiveQuery class for [[PagetypeDefaultTemplate]].
 *
 * @see PagetypeDefaultTemplate
 */
class PagetypeDefaultTemplateQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return PagetypeDefaultTemplate[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return PagetypeDefaultTemplate|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}