<?php

namespace common\models;

use Yii;
use yii\base\Model;


class UploadForm extends Model
{
    public $file;
    
    public function rules()
    {
        return [
            [['file'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx','checkExtensionByMimeType'=>false],
        ];
    }
    public function attributeLabels(){
        return [
            'file'=>'文件上传'
        ];
    }
    
    public function upload()
    {
        if ($this->validate()) {
            $filename = date('Y-m-d',time()).'_'.rand(1,9999).".". $this->file->extension;
            $this->file->saveAs(Yii::getAlias('@root/upload/'). $filename);
            return ['status'=>1,'data'=>Yii::getAlias('@root/upload/'). $filename];
        } else {
            return ['status'=>0,'msg'=>current($this->getErrors())];
        }
    }
}
