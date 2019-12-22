<?php


namespace app\helpers;

use yii\helpers\FileHelper;
use Yii;

/**
 * Class HelperImageResize
 */
class HelperImageResize
{
    public static function getImages($pathResized)
    {
        $images = FileHelper::findFiles($pathResized);
        if (!$images) {
            return [];
        }
        $result = [];
        foreach ($images as $imagePath) {
            $basename = basename($imagePath);
            $result[] = Yii::getAlias(
                    '@web'
                ) . Yii::$app->params['baseUrlResized'] . Yii::$app->session->id . '/' . $basename;
        }

        return $result;
    }
}