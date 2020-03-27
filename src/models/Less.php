<?php

namespace dmstr\modules\prototype\models;

use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseModel
{
    use EditorEntry;

    public $exportPath = '@runtime/less/';


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
