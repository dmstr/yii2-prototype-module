<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\commands;

use yii\console\Controller;


/**
 * Prototype command
 * @package dmstr\modules\prototype\commands
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class PrototypeController extends Controller
{

    public $escapeFileNames = false;
    public $exportPath = '@runtime/export';

    /**
     * @param string $actionId
     * @return array|string[]
     */
    public function options($actionId)
    {
        $options = parent::options($actionId);
        $options[] = 'escapeFileNames';
        $options[] = 'exportPath';
        return $options;
    }

    /**
     * @return array
     */
    public function actions()
    {
        $actions = parent::actions();
        $actions['export-html'] = [
            'class' => 'dmstr\modules\prototype\commands\actions\ExportAction',
            'modelClass' => 'dmstr\modules\prototype\models\Html',
            'extention' => 'html',
        ];
        $actions['export-less'] = [
            'class' => 'dmstr\modules\prototype\commands\actions\ExportAction',
            'modelClass' => 'dmstr\modules\prototype\models\Less',
            'extention' => 'less',
        ];
        $actions['export-twig'] = [
            'class' => 'dmstr\modules\prototype\commands\actions\ExportAction',
            'modelClass' => 'dmstr\modules\prototype\models\Twig',
            'extention' => 'twig',
        ];
        return $actions;
    }


}