<?php

namespace backend\controllers;
use yii;
use backend\common\controllers\Controller;
use yii\base\Exception;
use common\modules\config\models\Config;


/**
 * Site controller.
 */
class UpgradeController extends Controller
{
	
    public function actionIndex()
    {
    	$config = Config::find()->where(array('name'=>'version'))->one();
    	$version = floatval($config->value);    	
        return $this->render('index',['version'=>$version]);
    }

   	public function actionUp(){
   		$root = Yii::getAlias('@root'); 
   		$config = Config::find()->where(array('name'=>'version'))->one();
   		$version = floatval($config->value);
   		$serverVersion = array();
   		$serverVersion['version'] = '2.00';
   		$serverVersion['down_url'] = 'http://yjshop.com/2.00.tar';
   		if($version<$serverVersion['version']){
   			//安装准备：判断权限
   			clearstatcache(); // 清除文件夹权限缓存
   			$quanxuan = substr(base_convert(@fileperms($root),10,8),-4);   			
   			if(!in_array($quanxuan,array('0777','0666','0222')))
   				throw new Exception("网站根目录不可写,无法升级.");
   			//判断是否存在更新文件
   			$dowloadFlag = $this->checkDownload($serverVersion['version']);
   			if (!$dowloadFlag){
   				//1，下载zip文件
   				$result = $this->downloadFile($serverVersion['down_url'],'md5');
   				if($result != 1) return $result;
   			}   
   			$downFileName = explode('/', $serverVersion['down_url']);
   			$downFileName = end($downFileName);
   			$folderName = str_replace(".tar","",$downFileName);  // 文件夹
   			//检查文件夹是否存在
   			$dir = $root.'\\yjupgrade\\'.$serverVersion['version'];
   			if(!is_dir($dir)){
   				mkdir($dir);
   			}
   			//2，解压到指定路径
   			$path = $root.'\\yjupgrade\\'.$downFileName;
   			$phar = new \PharData($path);
			//解压文件		
			$phar->extractTo($dir,null,true);   
			//获取解压文件路径列表
			$files = $this->getAllfiles($dir);
			//var_dump($files);
			//备份文件
			$destination = $root.'\\yjbackup\\'.$version.'--'.$serverVersion['version'];			
			$this->copyAllfiles($files,$root,$destination);
			//替换文件TODO
			//$this->coverAllFiles($files, $destination, $root);
			//执行migration
			//更新版本号
			$config->value=$serverVersion['version'];
			$config->save(false);
			
   		}else{   			throw new Exception('已是最新版本');
   		}
   	}
   	/**
   	 * @param type $fileUrl 下载文件地址
   	 * @param type $md5File 文件MD5 加密值 用于对比下载是否完整
   	 * @return string 错误或成功提示
   	 */
   	public function downloadFile($fileUrl,$md5File)
   	{
   		$root = Yii::getAlias('@root');
   		$downFileName = explode('/', $fileUrl);
   		$downFileName = end($downFileName);
   		$saveDir = $root.'/yjupgrade/'.$downFileName; // 保存目录
   		if(!file_get_contents($fileUrl,0,null,0,1)){
   			return "下载升级文件不存在"; // 文件存在直接退出
   		}
   		$ch = curl_init($fileUrl);
   		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
   		curl_setopt($ch, CURLOPT_BINARYTRANSFER,1);
   		$file = curl_exec ($ch);
   		curl_close ($ch);
   		$fp = fopen($saveDir,'w');
   		fwrite($fp, $file);
   		fclose($fp);
//    		if($md5File != md5_file($saveDir))
//    		{
//    			return "下载的文件有损害, 请重试!";
//    		}
   		return 1;
   	}
   	/**
   	 * 检查是否已经下载，false为未下载，true为已下载
   	 */   	
   	
   	private function checkDownload($serverVersion){
   		$root = Yii::getAlias('@root');   		
   		$fileName = $root.'\\yjupgrade\\'.$serverVersion.'.tar'; // 保存目录
   		if (file_exists($fileName)){
   			return true;
   		}else{
   			return false;
   		}
   	}
   	private function getAllfiles($dir){
   		if(is_dir($dir)){
   			$files = array();
   			$child_dirs = scandir($dir);
   			foreach ($child_dirs as $child_dir){
   				//'.'和'..'是Linux系统中的当前目录和上一级目录，必须排除掉，
   				//否则会进入死循环，报segmentation falt 错误
   				if($child_dir != '.' && $child_dir != '..'){
   					if(is_dir($dir.'/'.$child_dir)){
   						$files[$child_dir] = $this->getAllfiles($dir.'/'.$child_dir);
   					}else{
   						$files[] = $child_dir;
   					}
   				}
   			}
   			return $files;
   		}else{
   			return $dir;
   		}
   	}
   	private function copyAllfiles($files,$source,$destination){
   		if(!is_dir($destination)){
   			mkdir($destination);
   		}
   		foreach ($files as $key=>$v){
   			if (!is_array($v)){
   				$file = $source.'\\'.$v;
   				if (!file_exists($file)){
   					continue;//版本新增文件
   				}
   				copy($file, $destination.'\\'.$v);
   			}else{
   				$dir = $destination.'\\'.$key;
   				$newSource= $source.'\\'.$key;
   				if(!is_dir($newSource)){
   					continue;//版本新增文件夹
   				}  				
   				if(!is_dir($dir)){
   					mkdir($dir);
   				}   							
   				$this->copyAllfiles($v,$newSource,$dir);
   			}
   		}
   		
   	}
   	
   	private function coverAllFiles($files,$source,$destination){
   		foreach ($files as $key=>$v){
   			if (!is_array($v)){  
   				$file = $source.'\\'.$v;
   				copy($file, $destination.'\\'.$v);
   			}else{
   				$dir = $destination.'\\'.$key;
   				$newSource= $source.'\\'.$key;   				
   				if(!is_dir($dir)){
   					mkdir($dir);
   				}
   				$this->copyAllfiles($v,$newSource,$dir);
   			}
   		}
   	}
}
