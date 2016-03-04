<?php

namespace dmstr\modules\prototype\models\query;

/**
 * This is the ActiveQuery class for [[\dmstr\modules\prototype\models\Html]].
 *
 * @see \dmstr\modules\prototype\models\Html
 */
class HtmlQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        $this->andWhere('[[status]]=1');
        return $this;
    }*/

    /**
     * @inheritdoc
     * @return \dmstr\modules\prototype\models\Html[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * @inheritdoc
     * @return \dmstr\modules\prototype\models\Html|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }
}
