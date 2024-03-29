<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\commands;

use dmstr\modules\prototype\commands\actions\ExportAction;
use dmstr\modules\prototype\models\Html;
use dmstr\modules\prototype\models\Less;
use dmstr\modules\prototype\models\Twig;
use Exception;
use Yii;
use yii\base\ErrorException;
use yii\console\Controller;
use yii\console\ExitCode;
use yii\helpers\Console;
use yii\helpers\FileHelper;


/**
 * Prototype command
 * @package dmstr\modules\prototype\commands
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property bool escapeFileNames
 * @property string exportPath
 */
class PrototypeController extends Controller
{

    public $escapeFileNames = true;
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
            'class' => ExportAction::class,
            'modelClass' => Html::class,
            'extension' => 'html',
        ];
        $actions['export-less'] = [
            'class' => ExportAction::class,
            'modelClass' => Less::class,
            'extension' => 'less',
        ];
        $actions['export-twig'] = [
            'class' => ExportAction::class,
            'modelClass' => Twig::class,
            'extension' => 'twig',
        ];
        return $actions;
    }

    /**
     * @return bool|string
     *
     * Returns path alias if alias defined otherwise return plain path
     * Removes slash from end
     */
    public function getExportPath()
    {
        try {
            $exportPath = Yii::getAlias($this->exportPath);
        } catch (Exception $e) {
            $exportPath = $this->exportPath;
        }
        return rtrim($exportPath, DIRECTORY_SEPARATOR);
    }

    /**
     * @param $mainLessFile string
     * @return int
     * @throws ErrorException
     *
     * Export asset bundle with defined style sheets applied
     */
    public function actionExportAssetBundle($mainLessFile = 'default-main.less')
    {

        $exportPath = $this->getExportPath() . DIRECTORY_SEPARATOR;

        $publishPath = $exportPath . 'web/less';
        $this->run('prototype/export-less', ['exportPath' => $publishPath]);
        $this->stdout("\n");
        $exportedFiles = FileHelper::findFiles($publishPath);

        $namespace = $this->prompt('Choose a namespace for the asset bundle:', ['default' => 'app\assets']);
        $this->stdout("\n");

        $lessFiles = [];

        foreach ($exportedFiles as $exportedFile) {
            $exportedFileName = basename($exportedFile);

            if ($this->confirm("Add file '" . $exportedFileName . "' to asset bundle?",
                $mainLessFile === $exportedFileName)) {
                $lessFiles[] = $exportedFileName;
            }
        }

        if (!empty($lessFiles)) {

            if (file_put_contents($exportPath . 'AssetBundle.php',
                    $this->renderFile(__DIR__ . '/templates/AssetBundle.php',
                        ['lessFiles' => $lessFiles, 'namespace' => $namespace])) === false) {
                $this->stdout("Error while writing file '{$exportPath}'\n", Console::FG_GREEN);
                return ExitCode::IOERR;
            }
            $this->stdout("\nExported asset bundle and assets to path '{$exportPath}'\n", Console::FG_GREEN);
            return ExitCode::OK;
        }
        $this->stdout("\nYou have to add at least 1 file to the asset bundle.\n", Console::FG_YELLOW);
        FileHelper::removeDirectory($exportPath);
        return ExitCode::OK;
    }

    public function confirm($message, $default = false)
    {
        return $this->interactive ? Console::confirm($message, $default) : $default;
    }
}
