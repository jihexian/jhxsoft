<?php
/**
 * Created by PhpStorm.
 * User: yidashi
 * Date: 16/7/16
 * Time: 上午11:14
 */

namespace common\modules\attachment\actions;

use common\modules\attachment\components\UploadedFile;
use common\modules\attachment\models\Attachment;
use Yii;
use yii\base\Action;
use yii\base\DynamicModel;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\Response;


class UploadAction extends Action
{
    /**
     * @var string Path to directory where files will be uploaded
     */
    public $path;

    /**
     * @var string Validator name
     */
    public $uploadOnlyImage = true;

    /**
     * @var string Variable's name that Imperavi Redactor sent upon image/file upload.
     */
    public $uploadParam = 'file';

    /**
     * @var string 参数指定文件名
     */
    public $uploadQueryParam = 'fileparam';

    public $multiple = false;

    /**
     * @var array Model validator options
     */
    public $validatorOptions = [];

    /**
     * @var string Model validator name
     */
    private $_validator = 'image';

    public $deleteUrl = ['/upload/delete'];

    public $callback;

    public $itemCallback;
	 /*以下参数由小黑添加做生成缩略图用*/
    public $thumb=0; //是否要生成缩略图，0不生成，1生成
    public $width=320;  //缩略图默认宽度
    public $height=320; //缩略图默认高度  
    /**小黑添加的参数结束**/
    /**
     * @inheritdoc
     */
    public function init()
    {
        if (Yii::$app->request->get($this->uploadQueryParam)) {
            $this->uploadParam = Yii::$app->request->get($this->uploadQueryParam);//获取文件参数
        }
		$this->thumb=Yii::$app->request->get("thumb")?Yii::$app->request->get("thumb"):$this->thumb; //获取是否生成缩略图
		$this->width=Yii::$app->request->get("width")?Yii::$app->request->get("width"):$this->width; //缩略图宽度
		$this->height=Yii::$app->request->get("height")?Yii::$app->request->get("height"):$this->height; //缩略图高度
        if ($this->uploadOnlyImage !== true) {
            $this->_validator = 'file';
        }
    }

    /**
     * @inheritdoc
     */
    public function run()
    {
        Yii::$app->response->format = Response::FORMAT_JSON;
        if (Yii::$app->request->isPost) {
            $files = UploadedFile::getInstancesByName($this->uploadParam);
            if (!$this->multiple) {
                $res = [$this->uploadOne($files[0])];
            } else {
                $res = $this->uploadMore($files);
            }
            $result = [
                'files' => $res
            ];
            if ($this->callback instanceof \Closure) {
                $result = call_user_func($this->callback, $result);
            }
            return $result;
        } else {
            throw new BadRequestHttpException('Only POST is allowed');
        }

    }
    private function uploadMore(array $files) {
        $res = [];
        foreach ($files as $file) {

            $result = $this->uploadOne($file);
            $res[] = $result;
        }
        return $res;
    }
    private function uploadOne(UploadedFile $file)
    {
        try {
            $model = new DynamicModel(compact('file'));
            $model->addRule('file', $this->_validator, $this->validatorOptions)->validate();

            if ($model->hasErrors()) {
                throw new Exception($model->getFirstError('file'));
            } else {
                $attachment = Attachment::uploadFromPost($this->path, $file,$this->thumb,$this->width,$this->height);
                $disks=yii::$app->session->get('disks'); 
                $domain= $disks=='local' ? env('SITE_URL') :'';
                $result = [
                    'id' => $attachment->id,
                    'name' => $attachment->name,
                    'hash' => $attachment->hash,
                    'url' => $domain.$attachment->url,
                    'path' => $domain.$attachment->path,
                    'extension' => $attachment->extension,
                    'type' => $attachment->type,
                    'size' => $attachment->size,
                    'thumbImg' => $domain.$attachment->thumbImg,
                ];
                if ($this->uploadOnlyImage !== true) {
                    $result['filename'] = $file->name;
                }
            }
            if ($this->itemCallback instanceof \Closure) {
                $result = call_user_func($this->itemCallback, $result);
            }
        } catch (Exception $e) {
            $result = [
                'error' => $e->getMessage()
            ];
        }
		return $result;
    }
}
