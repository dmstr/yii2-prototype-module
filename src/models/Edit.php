<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\models;


use Yii;
use yii\base\Model;
use yii\db\ActiveRecord;

/**
 * @package dmstr\modules\prototype\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * --- PROPERTIES ---
 *
 * @property array $models
 * @property array $keys
 * @property array $values
 * @property string $modelClass
 * @property Less[] $_models
 */
class Edit extends Model
{
    public $_models = [];
    public $modelClass;

    public $keys = [];
    public $values = [];

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['required'] = [
            [
                'keys',
                'values'
            ],
            'required'
        ];
        return $rules;
    }

    /**
     * @param array $models
     */
    public function setModels($models)
    {
        $this->_models = $models;
    }

    public function init()
    {
        parent::init();

        /** @var ActiveRecord $modelClass */
        $modelClass = $this->modelClass;
        foreach ($this->_models as $model) {
            /** @var Less $model */
            $model = $modelClass::findOne($model['id']);
            if ($model) {
                $this->keys[$model->id] = $model->key;
                $this->values[$model->id] = $model->value;
            }
        }
    }

    /**
     * @return array
     */
    public function models()
    {
        return $this->_models;
    }

    /**
     * @return bool
     * @throws \yii\db\Exception
     */
    public function save()
    {
        if ($this->validate()) {
            /** @var ActiveRecord $modelClass */
            $modelClass = $this->modelClass;
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($this->models() as $model) {
                $modelId = $model['id'];

                $config = [
                    'id' => $modelId,
                ];
                /** @var Less|null $model */
                $model = $modelClass::findOne($config);

                if ($model === null) {
                    $model = new $modelClass($config);
                }

                $model->key = $this->keys[$modelId];
                $model->value = $this->values[$modelId];

                if ($model->save() === false) {
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        return false;
    }


}
