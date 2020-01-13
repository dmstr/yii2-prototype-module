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
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * @package dmstr\modules\prototype\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class BaseModel extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key', 'value'], 'required'],
            [['value'], 'string'],
            [['key'], 'string', 'max' => 255],
            [['key'], 'unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('prototype', 'ID'),
            'key' => Yii::t('prototype', 'Key'),
            'value' => Yii::t('prototype', 'Value'),
        ];
    }

    /**
     * @inheritdoc
     * @return ActiveQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ActiveQuery(static::class);
    }

}
