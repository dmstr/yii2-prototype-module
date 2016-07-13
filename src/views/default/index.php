<?php

namespace _;

use insolita\wgadminlte\SmallBox;
use rmrevin\yii\fontawesome\FA;
use yii\helpers\Html;

/*
 * @var yii\web\View $this
 */
?>

<div class="row">
    <div class="col-sm-4">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => 'Less',
                'type' => SmallBox::TYPE_LBLUE,
                'icon' => 'fa fa-'.FA::_PENCIL_SQUARE,
                'footer' => 'Themes',
                'footer_link' => ['/prototype/less'],
            ]) ?>
    </div>
    <div class="col-sm-4">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => 'Twig',
                'type' => SmallBox::TYPE_ORANGE,
                'icon' => 'fa fa-'.FA::_STICKY_NOTE,
                'footer' => 'Layouts',
                'footer_link' => ['/prototype/twig'],
            ]) ?>
    </div>
    <div class="col-sm-4">
        <?= \insolita\wgadminlte\SmallBox::widget(
            [
                'head' => 'HTML',
                'type' => SmallBox::TYPE_MAR,
                'icon' => 'fa fa-'.FA::_STICKY_NOTE_O,
                'footer' => 'Snippets',
                'footer_link' => ['/prototype/html'],
            ]) ?>
    </div>
</div>

<hr/>

<p>
    This module provides routes for rendering dynamic views. Content can be varied by request parameter <code>id</code>.
</p>

<ul>
    <li><?= Html::a('/prototype/render/twig', ['/prototype/render/twig']) ?></li>
    <li><?= Html::a('/prototype/render/html', ['/prototype/render/html']) ?></li>
</ul>

<hr/>

<?= Html::a('Test page', ['test'], ['class' => 'btn btn-default']) ?>

<?= Html::a('Test page with id parameter', ['test', 'id' => '__ID__'], ['class' => 'btn btn-default']) ?>
