<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\traits;

use Yii;

/**
 * @package dmstr\modules\prototype\traits
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 *
 * @property string $key
 * @property string $value
 */
trait EditorEntry
{

    public static $cacheKey = 'active-entries';

    /**
     * @return array
     */
    public static function allEntries()
    {
        $models = self::find()->select(['id', 'key'])->orderBy(['key' => SORT_ASC])->asArray()->all();
        $allEntries = [];
        foreach ($models as $model) {
            $entryId = $model['id'];
            $allEntries[$entryId] = [
                'id' => $entryId,
                'name' => $model['key'],
                'opened' => isset(Yii::$app->session->get(md5(self::$cacheKey), [])[self::class][$entryId])
            ];
        }
        return $allEntries;
    }

    /**
     * @return array
     */
    public static function activeEntries()
    {
        $allEntries = Yii::$app->session->get(md5(self::$cacheKey), []);

        foreach ($allEntries as $key => $entries) {
            foreach ($entries as $entryId => $data) {
                $model = $key::find()->andWhere(['id' => $entryId])->asArray()->one();
                if ($model === null) {
                    self::removeEntry($entryId);
                } else {
                    $allEntries[$key][$entryId]['name'] = $model['key'];
                }
            }
        }

        if (isset($allEntries[self::class])) {
            return $allEntries[self::class];
        }
        return [];
    }

    /**
     * @param $entryId
     *
     * @return bool
     */
    public static function addEntry($entryId)
    {
        $model = self::find()->select([
            'key'
        ])->andWhere(['id' => $entryId])->orderBy(['key' => SORT_ASC])->asArray()->one();
        if ($model) {
            $activeEntries = self::activeEntries();
            $activeEntries[$entryId] = [
                'id' => $entryId,
                'name' => $model['key']
            ];
            $allEntries = Yii::$app->session->get(md5(self::$cacheKey), []);
            $allEntries[self::class] = $activeEntries;
            Yii::$app->session->set(md5(self::$cacheKey), $allEntries);
            return true;
        }
        return false;
    }

    /**
     * @param $entryId
     *
     * @return bool
     */
    public static function removeEntry($entryId)
    {

        $activeEntries = self::activeEntries();
        if (isset($activeEntries[$entryId])) {
            unset($activeEntries[$entryId]);
            $allEntries = Yii::$app->session->get(md5(self::$cacheKey), []);
            $allEntries[self::class] = $activeEntries;
            Yii::$app->session->set(md5(self::$cacheKey), $allEntries);
            return true;
        }
        return false;
    }
}
