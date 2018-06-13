<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2018 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 *  --- VARIABLES ---
 *
 * @var $lessFiles string[] List of file paths to less files
 * @var $namespace string Asset bundle's namespace
 */

echo "<?php\n";
echo "namespace {$namespace};\n\n";
echo "use \yii\web\AssetBundle as BaseAssetBundle;\n\n";
?>
/**
 * Class AssetBundle
 * @package <?=$namespace . "\n"?>
 */
class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@<?=str_replace("\\",'/', $namespace)?>/web';

    public $css = [
<?php
foreach ($lessFiles as $lessFile) {
echo "\t\t'less/" . $lessFile . "',\n";
}
?>
    ];
}