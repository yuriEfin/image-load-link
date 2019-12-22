<?php

use yii\bootstrap\Html;

/**
 * @var array $images
 */
?>
<div class="row">
    <?php if (!$images): ?>
        <?= Yii::t('app', 'No uploaded photo...') ?>
    <?php endif; ?>
    <?php foreach ($images as $image) : ?>
        <?= Html::img($image) ?>
    <?php endforeach; ?>
</div>

