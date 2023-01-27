<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\models;


use dmstr\modules\prototype\traits\EditorEntry;
use Yii;
use yii\base\Model;
use yii\db\Query;

/**
 * @package dmstr\modules\prototype\models
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 *
 * @property string $tableName
 * @property string $_tableName
 */
class Search extends Model
{
    public $term;

    private $_tableName;

    /**
     * @return string
     */
    public function formName()
    {
        return '';
    }

    /**
     * @return array
     */
    public function rules()
    {
        $rules = parent::rules();
        $rules['safe'] = [
            'term',
            'safe'
        ];
        return $rules;
    }

    /**
     * @return array
     */
    public function allEntries()
    {
        $query = (new Query())->from($this->_tableName)->select(['id', 'key','value'])->orderBy(['key' => SORT_ASC]);
        $query->andFilterHaving(['OR', ['LIKE','value', $this->term], ['LIKE','key', $this->term]]);
        $allEntries = [];
        foreach ($query->all() as $model) {
            $entryId = $model['id'];
            $allEntries[$entryId] = [
                'id' => $entryId,
                'name' => $model['key'],
                'opened' => isset(Yii::$app->session->get(md5(BaseModel::$cacheKey), [])[self::class][$entryId])
            ];
        }
        return $allEntries;
    }

    /**
     * @param mixed $tableName
     */
    public function setTableName($tableName)
    {
        $this->_tableName = $tableName;
    }
}
