<?php


namespace app\controllers\actions;

use app\helpers\HelperImageResize;
use app\models\form\UploadForm;
use linslin\yii2\curl\Curl;
use yii\base\Action;
use Yii;
use yii\helpers\FileHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Class ImageAction
 */
class ImageAction extends Action
{
    /**
     * @var string the view file to be rendered. If not set, it will take the value of [[id]].
     * That means, if you name the action as "error" in "SiteController", then the view name
     * would be "error", and the corresponding view file would be "views/site/error.php".
     */
    public $view;
    /**
     * @var string
     */
    public $pathUploadOrigin,
        $pathUploadResized;
    /**
     * @var string|false|null the name of the layout to be applied to this error action view.
     * If not set, the layout configured in the controller will be used.
     * @see   \yii\base\Controller::$layout
     * @since 2.0.14
     */
    public $layout;

    /**
     * @throws \Exception
     */
    public function init()
    {
        if (!$this->pathUploadOrigin || !$this->pathUploadResized) {
            throw new \Exception(Yii::t('error', 'Propertys paths upload no configured'));
        }
        parent::init();
    }

    /**
     * @return string
     * @throws \Exception
     */
    public function run()
    {
        if ($this->layout !== null) {
            $this->controller->layout = $this->layout;
        }
        $form = new UploadForm();
        $postParams = Yii::$app->request->post();
        $error = '';
        $pathOrigin = $this->pathUploadOrigin . '/' . Yii::$app->session->id . '/';
        $pathResized = $this->pathUploadResized . '/' . Yii::$app->session->id . '/';
        FileHelper::createDirectory($pathOrigin);
        FileHelper::createDirectory($pathResized);
        /* Ajax upload image by link */
        if (Yii::$app->request->isAjax && $form->load($postParams) && $form->validate()) {
            if ($result = (new Curl())->get($form->link)) {
                try {
                    $domainLink = parse_url($form->link, PHP_URL_HOST);
                    $fileName = uniqid('_origin') . '_' . $domainLink;
                    $fullPathOriginalImage = $pathOrigin . $fileName;
                    $fullPathResizedImage = $pathResized . $fileName;
                    file_put_contents($fullPathOriginalImage, $result);
                    $type = FileHelper::getMimeType($fullPathOriginalImage);
                    $ext = FileHelper::getExtensionsByMimeType($type)[1];

                    // crop
                    Image::crop($fullPathOriginalImage, $form->width, $form->height)
                        ->save($fullPathResizedImage . '.' . $ext, ['quality' => 80]);
                    @unlink($fullPathOriginalImage);
                } catch (\Exception $exception) {
                    $error = $this->getErrorMessage($exception);
                } catch (\Throwable $exception) {
                    $error = $this->getErrorMessage($exception);
                }
            }
        }
        if ($error) {
            var_dump($error, '$error');
            die;
        }
        $images = HelperImageResize::getImages($pathResized);

        return $this->controller->render(
            $this->view ? : $this->id,
            [
                'images' => $images,
                'baseUrl' => Yii::getAlias('@web'),
                'formModel' => $form,
                'error' => $error,
            ]
        );
    }

    /**
     * @param \Exception|\Throwable $exception
     *
     * @return string
     */
    public function getErrorMessage($exception)
    {
        return YII_DEBUG ? $exception->getMessage() . "\n" . $exception->getTraceAsString() : Yii::t(
            'app',
            'Error upload'
        );
    }
}