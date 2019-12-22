<?php

use yii\helpers\FileHelper;
use yii\web\View;
use yii\bootstrap\ActiveForm;
use app\models\form\UploadForm;
use yii\bootstrap\Html;
use \yii\helpers\Url;

/**
 * @var View       $this
 * @var string     $result
 * @var UploadForm $formModel
 * @var array      $images
 */
$form = ActiveForm::begin(['id' => 'js-upload'])
?>
<?= $form->field($formModel, 'link')->textInput() ?>
<?= $form->field($formModel, 'height')->textInput() ?>
<?= $form->field($formModel, 'width')->textInput() ?>
<?= Html::submitButton(Yii::t('app', 'Upload'), ['class' => 'btn btn-sm']) ?>
<?php ActiveForm::end(); ?>
<hr>
<h1>Uploaded photo:</h1>
<hr>
<div class="container" id="container-image">
    <?php if (!Yii::$app->request->isAjax): ?>
        <?= $this->renderAjax(
            '_images',
            [
                'images' => $images,
            ]
        ); ?>
    <?php endif; ?>
</div>