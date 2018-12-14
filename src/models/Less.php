<?php

namespace dmstr\modules\prototype\models;

use dmstr\modules\prototype\models\base\Less as BaseLess;
use mikehaertl\shellcommand\Command;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

/**
 * This is the model class for table "app_less".
 */
class Less extends BaseLess
{
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'bedezign\yii2\audit\AuditTrailBehavior',
            ]
        );
    }

    public function validateLess(){
        $tmpDir = \Yii::getAlias('@runtime/settings-asset');
        $tmpFile = $tmpDir.'/'.uniqid($this->key.'-').'.less';
        FileHelper::createDirectory($tmpDir);
        file_put_contents($tmpFile, $this->value);
        $validationCommand = new Command();
        $validationCommand->setCommand('lessc');
        $validationCommand->addArg('--no-color');
        $validationCommand->addArg('--rp='.\Yii::getAlias('@runtime/settings-asset'));
        $validationCommand->addArg($tmpFile);
        \Yii::trace($validationCommand->getExecCommand(), __METHOD__);
        $validationCommand->execute();
        if ($validationCommand->getError()) {
            $this->addError('value', 'LESS validation failed: '.$validationCommand->getError());
            return false;
        } else {
            return true;
        }
    }

    public function beforeSave($insert)
    {
        if (!$this->validateLess()) {
            //return false;
        }
        return parent::beforeSave($insert);
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        \Yii::$app->cache->set('prototype.less.changed_at', time());
    }
}
