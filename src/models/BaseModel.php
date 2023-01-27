<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\models;


use bedezign\yii2\audit\AuditTrailBehavior;
use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * @package dmstr\modules\prototype\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class BaseModel extends ActiveRecord
{
    use EditorEntry;

    /**
     * Column attribute 'created_at'
     */
    const ATTR_CREATED_AT = 'created_at';

    /**
     * Column attribute 'updated_at'
     */
    const ATTR_UPDATED_AT = 'updated_at';

    /**
     * @inheritdoc
     *
     * Use yii\behaviors\TimestampBehavior for created_at and updated_at attribute
     *
     * @return array
     */
    public function behaviors()
    {

        $behaviors = parent::behaviors();

        $behaviors['timestamp'] = [
            'class'              => TimestampBehavior::class,
            'createdAtAttribute' => static::ATTR_CREATED_AT,
            'updatedAtAttribute' => static::ATTR_UPDATED_AT,
            'value'              => new Expression('NOW()'),
        ];
        return $behaviors;
    }

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
