<?php
/**
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2019 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * --- VARIABLES ---
 *
 * @var View $this
 * @var array $activeEntries
 * @var string $mode
 * @var array $allEntries
 * @var Less[] $currentEntries
 * @var \dmstr\modules\prototype\models\Search $searchModel
 */

use dmstr\modules\prototype\models\Less;
use yii\web\View;

?>
<main class="editor-main">
    <?php
    echo $this->render('_sidebar', [
        'allEntries' => $allEntries,
        'searchModel' => $searchModel,
    ]);
    echo $this->render('_editor', [
        'activeEntries' => $activeEntries,
        'searchModel' => $searchModel,
        'currentEntries' => $currentEntries,
        'mode' => $mode
    ])
    ?>
</main>
