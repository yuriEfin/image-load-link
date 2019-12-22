<?php


namespace app\models\form;

use Yii;
use yii\base\Model;

/**
 * Class UploadForm
 */
class UploadForm extends Model
{
    /**
     * @var string
     */
    public $link;
    /**
     * @var string
     */
    public $path;
    /**
     * @var int
     */
    public $width = 200;
    /**
     * @var int
     */
    public $height = 200;

    public function rules()
    {
        return [
            [['link', 'width', 'height'], 'required'],
            [['width'], 'default', 'value' => 200],
            [['height'], 'default', 'value' => 200],
            [['link'], 'url', 'defaultScheme' => 'http'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'link' => Yii::t('app', 'Link'),
            'path' => Yii::t('app', 'Path'),
            'height' => Yii::t('app', 'Height'),
            'width' => Yii::t('app', 'Width'),
        ];
    }
}