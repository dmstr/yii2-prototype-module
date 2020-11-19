<?php
namespace dmstr\modules\prototype\assets;

/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2015 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use dmstr\modules\prototype\models\Less;
use Yii;
use yii\caching\FileDependency;
use yii\helpers\FileHelper;
use yii\helpers\Json;
use yii\web\AssetBundle;

/**
 * Class DbAsset
 * @package extensions\dmstr\prototype\assets
 *
 * This class implements an AssetBundle for Less "files" stored in DB as prototype\models\Less models
 * In init() we get the less models from DB, build an overall checksum that will be compared with a cached value
 * previous run.
 *
 * If nothing has changed (checksum match, and db less was yet exported to disc) nothing will be done here
 * If "something" changed (or in the first run):
 * - if not exists, create empty sourcePath dir to prevent race condition with next req which will try to init
 *   this bundle again...
 * - write contents of less models as *.less files in a tmp dir
 * - To prevent multiple converter runs while asset publishing the main less will be converted in this tmp dir
 * - To prevent multiple publishing runs we use another tmp file and simple renames while changing the sourcePath contents
 * - To prevent unnecessary publishing you should configure a persistent cacheComponent to store the checksum
 *   which survive a restart (eg. do not use a memory cache which is part of a docker-compose stack)
 * - if something went wrong the previous state (prev. checksum in cache) is restored
 *
 */
class DbAsset extends AssetBundle
{
    const CACHE_ID = 'app\assets\SettingsAsset';
    const SETTINGS_KEY = 'app.less';
    const MAIN_LESS_FILE = 'main.less';
    const SETTINGS_SECTION = 'app.assets';

    public $sourcePath = '@runtime/settings-asset';
    public $tmpPath = '@runtime/settings-asset-tmp';
    /**
     *
     * name of the cache component that should be used for the less checksum cache
     * for high volume sites this should be set to a persistent cache which survive a
     * restart
     *
     * @var string
     */
    public $cacheComponent = 'cache';

    public $settingsKey = 'registerPrototypeAssetKey';

    public $depends = [
        'yii\web\YiiAsset',
        // if a full BootstrapAsset (CSS) is compiled, it's recommended to disable it in assetManager configuration
        'yii\bootstrap\BootstrapPluginAsset', // (JS)
    ];
    /**
     * internal cache property
     *
     * @var
     */
    protected $cache;

    public function init()
    {
        // init configured cache component
        $this->cache = Yii::$app->{$this->cacheComponent};

        $this->css[] = Yii::$app->settings->get($this->settingsKey, self::SETTINGS_SECTION).'-'.self::MAIN_LESS_FILE;

        parent::init();

        if (!$this->sourcePath) {
            // TODO: this is workaround for empty source path when using bundled assets
            return;
        } else {
            $sourcePath = Yii::getAlias($this->sourcePath);

            $models = Less::find()->all();
            $hash = sha1(Json::encode($models));
            $prevHash = $this->cache->get(self::CACHE_ID);
            $sourcePathExists = is_dir($sourcePath);
            if (($hash !== $prevHash) || ! $sourcePathExists) {

                // create empty sourcePath dir to prevent race condition with next req which will init again...
                if ( ! $sourcePathExists) {
                    FileHelper::createDirectory($sourcePath);
                }
                $dependency = new FileDependency();
                $dependency->fileName = __FILE__;
                $this->cache->set(self::CACHE_ID, $hash, 0, $dependency);

                $tmpPath = uniqid($sourcePath.'-');
                FileHelper::createDirectory($tmpPath);
                foreach ($models as $model) {
                    file_put_contents("$tmpPath/{$model->key}.less", $model->value);
                }

                // convert less with new files in tmp folder before replacing bundle sourcePath
                // to prevent multiple conversions while republishing on high-traffic sites
                $converter = Yii::$app->assetManager->getConverter();
                try {
                    foreach ($this->css as $cssFile) {
                        $result = $converter->convert($cssFile, $tmpPath);
                    }
                } catch (\Exception $exception) {
                    $this->cache->set(self::CACHE_ID, $prevHash, 0, $dependency);
                    Yii::error($exception->getMessage(), __METHOD__);
                    return false;
                }

                // force republishing of asset files by Yii Framework
                // to prevent race conditions, use 2 rename cmds to switch dir and remove prev. dir afterwards
                $sourcePathToDelete = uniqid($sourcePath.'-to-delete-');
                $sourcePathRenamed = false;
                if ($sourcePathExists) {
                    if (rename($sourcePath, $sourcePathToDelete)) {
                        $sourcePathRenamed = true;
                    } else {
                        $this->cache->set(self::CACHE_ID, $prevHash, 0, $dependency);
                        return false;
                    }
                }
                if ( ! rename($tmpPath, $sourcePath)) {
                    $this->cache->set(self::CACHE_ID, $prevHash, 0, $dependency);
                    return false;
                }
                if ($sourcePathRenamed) {
                    FileHelper::removeDirectory($sourcePathToDelete);
                }
            }
        }
    }
}
