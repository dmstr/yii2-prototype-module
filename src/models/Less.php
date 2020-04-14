<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\helpers\FileHelper;
use mikehaertl\shellcommand\Command;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseModel
{
    use EditorEntry;

    public $exportPath = '@runtime/less/';
    public $fixedValue;
    public $lintErrors;


    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%less}}';
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['audit-trail'] = AuditTrailBehavior::class;
        return $behaviors;
    }

    /**
     * @param bool $insert
     * @param array $changedAttributes
     */
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        Yii::$app->cache->set('prototype.less.changed_at', time());
    }

    public function beforeSave($insert)
    {
        if (!$this->validateLess()) {
            return false;
        }

        return parent::beforeSave($insert);
    }

    public function beforeDelete()
    {
        if (!$this->validateLess()) {
            Yii::$app->session->addFlash('error', Yii::t('prototype', 'Could not delete record, due to validation errors'));
            return false;
        }
        return parent::beforeDelete();
    }

    /**
     * Runs stylelint on .less files exported after running $this->validateLess().
     * It generates a .stylelintrc.json config file at runtime (mandatory).
     * If there is no 'style.config' in settings module, this will be created.
     * If 'style.config' is present in settings, it will be used as content for
     * the .stylelintrc.json file, else a default config will be used.
     * If $fix is set the fixed code will be stored in the $fixedValue property.
     * Lint errors will be stored in $lintErrors property.
     * @param bool $fix if stylelint should fix the less source code
     */
    public function lintLess($fix = false)
    {
        $fileName = $this->key . '.less';
        $exportPath = Yii::getAlias($this->exportPath);
        $file = $exportPath . '.stylelintrc.json';
        $defaultConfig = '
        {
            "extends": "stylelint-config-standard",
            "rules": {
                "no-eol-whitespace": null,
                "block-no-empty": null,
                "no-descending-specificity": null,
                "declaration-empty-line-before": null,
                "at-rule-empty-line-before": null
            }
        }';

        if (Yii::$app->settings->has('stylelint.config', 'app.assets') === false) {
            Yii::$app->settings->set('stylelint.config', $defaultConfig, 'app.assets', 'object');
        }

        $config = Yii::$app->settings->get('stylelint.config', 'app.assets', $defaultConfig)->scalar;

        if (file_put_contents($file, $config) === false) {
            $errorMessage = Yii::t('prototype', 'Error while checking file {file}', ['file' => $file]);
            Yii::$app->session->addFlash('warning', $errorMessage);
            $output = $errorMessage;
        } else {
            $command = new Command();
            $command->setCommand('ls `npm root -g`');
            $command->execute();
            $npmGlobalPackages = $command->getOutput();
            $stylelint = strpos($npmGlobalPackages, 'stylelint') !== false ? true : false;
            $standard = strpos($npmGlobalPackages, 'stylelint-config-standard') !== false ? true : false;

            if ($stylelint === false) {
                Yii::$app->session->addFlash('warning', Yii::t('prototype', '"stylelint" is not installed'));
            }

            if ($standard === false) {
                Yii::$app->session->addFlash('warning', Yii::t('prototype', '"stylelint-config-standard" is not installed'));
            }

            if ($fix === true) {
                $command = new Command();
                $command->setCommand('cd ' . $exportPath . ' && npx stylelint --fix --syntax less "' . $fileName . '"');
                $command->execute();
                $this->fixedValue = file_get_contents($exportPath . $fileName);
            }

            $command = new Command();
            $command->setCommand('cd ' . $exportPath . ' && npx stylelint --syntax less "' . $fileName . '"');
            $command->execute();
            $this->lintErrors = $command->getOutput();
        }
    }

    private function validateLess()
    {
        $entryFile = Yii::$app->settings->get('registerPrototypeAssetKey', 'app.assets', 'default') . '-main.less';

        $converter = Yii::$app->assetManager->getConverter();
        $exportPath = Yii::getAlias($this->exportPath);
        $models = self::find()->all();

        FileHelper::removeDirectory($exportPath);
        FileHelper::createDirectory($exportPath);

        foreach ($models as $model) {
            $key = $model->key;
            $value = $model->value;

            // model was not saved yet and this need to work with the new property
            // values. If property is dirty use it instead of the old value.
            if ($this->primaryKey === $model->primaryKey) {
                $dirtyAttributes = $this->getDirtyAttributes();
                $key = $dirtyAttributes['key'] ?? $this->key;
                $value = $dirtyAttributes['value'] ?? $this->value;
            }

            $file = $exportPath . $key . '.less';
            $content = $value;
            if (file_put_contents($file, $content) === false) {
                Yii::$app->session->addFlash('warning', Yii::t('prototype', 'Error while checking file {file}',
                    ['file' => $file]));
                return false;
            }
        }

        try {
            $result = $converter->convert($entryFile, $exportPath);
        } catch (\Exception $exception) {
            Yii::error($exception->getMessage(), __METHOD__);
            $this->addError('value', $exception->getMessage());
            return false;
        }

        return true;
    }
}
