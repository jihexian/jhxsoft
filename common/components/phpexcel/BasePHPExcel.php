<?php
namespace common\components\phpexcel;
use Yii;
$excelpath = dirname(Yii::$app->basePath).DIRECTORY_SEPARATOR.'common'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'phpexcel'.DIRECTORY_SEPARATOR;
require_once($excelpath."PHPExcel.php"); 
class BasePHPExcel extends \PHPExcel{
	
}