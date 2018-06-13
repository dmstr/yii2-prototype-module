<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\commands\actions;

use yii\base\Action;
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
 */
class ExportAction extends Action
{
    public $modelClass;
    public $extention;

    private static $availableModelTypes = [
        'dmstr\modules\prototype\models\Html',
        'dmstr\modules\prototype\models\Less',
        'dmstr\modules\prototype\models\Twig'
    ];


    /**
     * @return bool|string
     *
     * Returns path alias if alias defined otherwise return plain path
     * Removes slash from end
     * Add sub dir with name of extention
     */
    public function getExportPath()
    {
        try {
           $exportPath = \Yii::getAlias($this->controller->exportPath);
        } catch (\Exception $e) {
            $exportPath = $this->controller->exportPath;
        }
        return rtrim($exportPath, DIRECTORY_SEPARATOR);
    }

    /**
     * @return int ExitCode
     *
     * Exports files to a given location
     */
    protected function run()
    {

        $this->controller->stdout("Exporting {$this->extention} files\n", Console::FG_BLUE);
        if (!class_exists($this->modelClass)) {
            $this->controller->stderr("Model class '{$this->modelClass}' does not exist", Console::FG_RED);
            return ExitCode::IOERR;
        }

        if (!\in_array($this->modelClass, self::$availableModelTypes, true)) {
            $this->controller->stderr("Model class '{$this->modelClass}' is not allowed", Console::FG_RED);
            return ExitCode::IOERR;
        }

        $exportPath = $this->getExportPath();

        try {
            if (!FileHelper::createDirectory($exportPath)) {
                throw new \Exception("Error while creating directory '{$exportPath}'");
            }
        } catch (\Exception $exception) {
            $this->controller->stderr($exception->getMessage() . "\n", Console::FG_RED);
            return ExitCode::IOERR;
        }

        $entries = ($this->modelClass)::find()->all();

        foreach ($entries as $entry) {
            $fileName = $entry->key;
            if ($this->controller->escapeFileNames === true) {
                $fileName = Inflector::slug(str_replace('/','-',$entry->key));
            }
            try {
                if (file_put_contents($exportPath . DIRECTORY_SEPARATOR . $fileName . '.' . $this->extention, $entry->value) === false) {
                    throw new \Exception("Error while writing file for key '{$entry->key}'");
                }
                $this->controller->stdout('.');
            } catch (\Exception $exception) {
                $this->controller->stderr($exception->getMessage(), Console::FG_RED);
                return ExitCode::IOERR;
            }
        }


        $this->controller->stdout("\nExported " . count($entries) . ' files to path ' . $exportPath . "\n", Console::FG_GREEN);
        return ExitCode::OK;
    }
}