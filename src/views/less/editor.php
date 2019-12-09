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
 * @var array $allEntries
 * @var array $pendingEntries
 * @var Less $currentEntry
 * @var \dmstr\modules\prototype\models\Search $searchModel
 */

use dmstr\modules\prototype\models\Less;
use yii\web\View;

$this->registerCss(<<<CSS
.editor-main {
    display: flex;
}
.editor-sidebar {
    width: 25%;
    padding-right: 1rem;
}
.editor-top-navigation {
    list-style-type: none;
    padding-left: 0;
    margin-bottom: 1rem;
}
.editor-top-navigation > li {
    display: inline-block;
}
.editor-canvas {
    width: 75%;
}
CSS
);

?>

<!--Ah cool! Weiß nicht ob da geht, aber evtl. einen kleinen Hinweis einblenden wenn ne Klammer oder ein Semikolon fehlt?-->
<!--Suche geht ja eig auch gut über die cmd+f.-->
<!--Oder wär das übergreifend über alle Less-Dateien?-->
<!--Das wär natürlich mega. -->
<!--Und zu ner bestimmten Zeilennummer springen fänd ich persönlich auch manchmal ganz praktisch.-->

<main class="editor-main">
    <?php
    echo $this->render('editor/_sidebar', [
        'allEntries' => $allEntries,
        'searchModel' => $searchModel,
        'currentEntry' => $currentEntry
    ]);
    echo $this->render('editor/_editor', [
        'activeEntries' => $activeEntries,
        'pendingEntries' => $pendingEntries,
        'searchModel' => $searchModel,
        'currentEntry' => $currentEntry
    ]);
    ?>
</main>
