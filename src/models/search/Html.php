<?php

namespace dmstr\modules\prototype\models\search;

use dmstr\modules\prototype\models\Html as HtmlModel;
use dmstr\modules\prototype\traits\Searchable;
use yii\base\Model;
use yii\data\ActiveDataProvider;


/**
 * @package dmstr\modules\prototype\models\search
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Html extends HtmlModel
{
    use Searchable;
}
