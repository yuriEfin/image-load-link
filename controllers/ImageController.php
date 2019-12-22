<?php


namespace app\controllers;

use app\helpers\HelperImageResize;
use yii\web\Controller;

/**
 * Class ImageController
 */
class ImageController extends Controller
{
    public $defaultAction = 'upload';

    /**
     * @return array
     */
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'app\controllers\actions\ImageAction',
                'view' => 'upload',
                'pathUploadOrigin' => \Yii::getAlias(\Yii::$app->params['pathUpload']['origin']),
                'pathUploadResized' => \Yii::getAlias(\Yii::$app->params['pathUpload']['resized']),
            ],
        ];
    }

    /**
     * @return \yii\web\Response
     */
    public function actionLoad()
    {
        return $this->renderAjax(
            '_images',
            [
                'images' => HelperImageResize::getImages(
                    \Yii::getAlias(\Yii::$app->params['pathUpload']['resized'])
                ),
            ]
        );
    }
}