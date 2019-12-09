<?php

namespace dmstr\modules\prototype\models\search;

use dmstr\modules\prototype\models\Twig as TwigModel;
use dmstr\modules\prototype\traits\Searchable;

/**
 * @package dmstr\modules\prototype\models\search
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class Twig extends TwigModel
{
    use Searchable;
}
