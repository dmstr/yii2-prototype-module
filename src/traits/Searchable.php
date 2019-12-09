<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\traits;


use dmstr\modules\prototype\models\Html as HtmlModel;
use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * Trait Searchable
 *
 * @package dmstr\modules\prototype\traits
 */
trait Searchable
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['key'], 'safe'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = self::find();

        $dataProvider = new ActiveDataProvider(
            [
                'query' => $query,
            ]
        );

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }


        $query->andFilterWhere(['like', 'key', $this->key]);

        return $dataProvider;
    }
}
