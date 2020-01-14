<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2020 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace dmstr\modules\prototype\assets;


use yii\bootstrap\BootstrapAsset;
use yii\web\AssetBundle;
use yii\web\JqueryAsset;

/**
 * @package dmstr\modules\prototype\assets
 * @author Elias Luhr <e.luhr@herzogkommunikation.de>
 */
class EditorAsset extends AssetBundle
{
    public $sourcePath = __DIR__ . '/web/editor';

    public $css = [
        'styles/editor.less'
    ];
    public $js = [
        'js/editor.js'
    ];

    public $depends = [
        BootstrapAsset::class,
        JqueryAsset::class
    ];
}
