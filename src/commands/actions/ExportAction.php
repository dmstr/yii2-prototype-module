<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\commands\actions;

use dmstr\modules\prototype\commands\PrototypeController;
use dmstr\modules\prototype\models\Html;
use dmstr\modules\prototype\models\Less;
use dmstr\modules\prototype\models\Twig;
use const PHP_EOL;
use yii\base\Action;
use yii\base\ErrorException;
use yii\console\ExitCode;
use yii\db\ActiveRecord;
use yii\helpers\Console;
use yii\helpers\Inflector;
use yii\helpers\FileHelper;


/**
 * Class ExportAction
 * @package dmstr\modules\prototype\commands\actions
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property ActiveRecord modelClass
 * @property  string extention
 * @property  PrototypeController controller
 */
class ExportAction extends Action
{
    public $modelClass;
    public $extention;

    private static $availableModelTypes = [
        Html::class,
        Less::class,
        Twig::class
    ];

    /**
     * @return int ExitCode
     *
     * Exports files to a given location
     */
    protected function run()
    {

        $this->controller->stdout("Exporting {$this->extention} files" . PHP_EOL, Console::FG_BLUE);
        if (!class_exists($this->modelClass)) {
            $this->controller->stderr("Model class '{$this->modelClass}' does not exist", Console::FG_RED);
            return ExitCode::IOERR;
        }

        if (!\in_array($this->modelClass, self::$availableModelTypes, true)) {
            $this->controller->stderr("Model class '{$this->modelClass}' is not allowed", Console::FG_RED);
            return ExitCode::IOERR;
        }

        $exportPath = $this->controller->getExportPath();

        try {
            if (!FileHelper::createDirectory($exportPath)) {
                throw new ErrorException("Error while creating directory '{$exportPath}'");
            }
        } catch (\Exception $exception) {
            $this->controller->stderr($exception->getMessage() . PHP_EOL, Console::FG_RED);
            return ExitCode::IOERR;
        }

        $entries = $this->modelClass::find()->all();

        foreach ($entries as $entry) {
            $fileName = $entry->key;
            if ($this->controller->escapeFileNames === true) {
                $fileName = Inflector::slug(str_replace('/','-',$entry->key));
            }
            try {
                if (file_put_contents($exportPath . DIRECTORY_SEPARATOR . $fileName . '.' . $this->extention, $entry->value) === false) {
                    throw new ErrorException("Error while writing file for key '{$entry->key}'");
                }
                $this->controller->stdout('.');
            } catch (\Exception $exception) {
                $this->controller->stderr($exception->getMessage(), Console::FG_RED);
                return ExitCode::IOERR;
            }
        }

        $this->controller->stdout(PHP_EOL . 'Exported ' . count($entries) . ' files to path ' . $exportPath . PHP_EOL, Console::FG_GREEN);
        return ExitCode::OK;
    }
}