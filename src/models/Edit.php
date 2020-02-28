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
use yii\db\Exception;

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
 * @property array $modelErrors
 * @property Less[] $_models
 */
class Edit extends Model
{
	protected $_models = [];
	protected $_modelErrors = [];
    public $modelClass;

    const NEW_MODEL_ID = 9999999;

    public $keys = [];
    public $values = [];
    public $initNew = false;

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
        $models = $this->models();
        foreach ($models as $model) {
            /** @var Less $model */
            $model = $modelClass::findOne($model['id']);
            if ($model) {
                $this->keys[$model->id] = $model->key;
                $this->values[$model->id] = $model->value;
            }
        }
        if ($this->initNew && isset($models[self::NEW_MODEL_ID])) {
            $this->keys[self::NEW_MODEL_ID] = '';
            $this->values[self::NEW_MODEL_ID] = '';
        }

    }

    /**
     * @return array
     */
    public function models()
    {
        $models = $this->_models;
        if ($this->initNew) {
            $models[self::NEW_MODEL_ID] = [
                'id' => self::NEW_MODEL_ID,
                'name' => Yii::t('prototype','New')
            ];
        }
        return $models;
    }

    /**
     * @return bool
     * @throws Exception
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
                    $model->id = null;
                }

                $model->key = $this->keys[$modelId];
                $model->value = $this->values[$modelId];

                if ($model->save() === false) {
					$this->addModelErrors($modelId, $model->key, $model->errors);
                    $transaction->rollBack();
                    return false;
                }
            }
            $transaction->commit();
            return true;
        }
        return false;
    }

	/**
	 * @param int $modelId
	 * @param string $key
	 * @param array $errors
	 */
	private function addModelErrors($modelId, $key, $errors)
	{
		$this->_modelErrors[$key] = [
			'modelId' => $modelId,
			'key' => $key,
			'errors' => $errors
		];
	}

	public function getModelErrors()
	{
		return $this->_modelErrors;
	}
}
