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

$this->registerCss(<<<CSS
.editor-main {
    display: flex;
}
.editor-sidebar {
    width: 25%;
    padding-right: 1rem;
}
.editor-sidebar .list-group {
    max-height: 350px;
    overflow-y: scroll;
}

.editor-sidebar .list-group-item  a:last-of-type{
    right: 0;
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

$this->registerJs(<<<JS
var currentLocation = document.location.toString();
var activeHash = currentLocation.split('#')[1];
var localStorageKey = 'dmstr.yii2.prototype.lastActiveHash';

$(function(){
    if (typeof activeHash === "undefined") {
      var lastActiveHash = localStorage.getItem(localStorageKey);
      if (lastActiveHash) {
        activeHash = lastActiveHash;
      }
    } else {
      activeHash = '#' + activeHash;
    }
    
    var tabEl = $('.editor-top-navigation a[data-target="' + activeHash + '"]');
    
    if (tabEl.length < 1) {
      tabEl = $('.editor-top-navigation > li:first-of-type > a')
    }
    tabEl.tab('show');
  
    $('.editor-top-navigation a').on('shown.bs.tab', function (e) {
        e.preventDefault();
        $(this).tab('show');
        var newActiveHash = $(e.target).data('target');
        window.location.hash = newActiveHash;
        localStorage.setItem(localStorageKey, newActiveHash);
    })
});
JS
);

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
