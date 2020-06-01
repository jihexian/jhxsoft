<?php
/**
 * Created by PhpStorm.
 * Author: wsyone  wsyone@foxmail.com
 * Time: 2018-05-31 16:49
 * Copyright:广西几何线科技有限公司 All Rights Reserved
 * site: http://www.jihexian.com
 */
namespace common\logic;
use common\components\Controller;
use yii;
use yii\helpers\ArrayHelper;
use common\models\Category;
use api\modules\v1\models\Member;
class MigrationLogic{
	
	/**
	 * 迁移article_cat数据
	 */
	public function m_article_category(){
		
		$db2 = Yii::$app->db2;
		$db = Yii::$app->db;
		$datas = $db2->createCommand('SELECT * FROM {{%article_cat}}')	
		->queryAll();
		//var_dump($datas);
		$transaction = $db->beginTransaction();
		try {		
			foreach ($datas as $data){
				$category = Category::findOne($data['cat_id']);
				if (empty($category)){
					$category = new Category();
					$category->id = $data['cat_id'];
					$category->title = $data['cat_name'];
					$category->pid = $data['parent_id'];
					$category->description = $data['cat_desc'];
					$category->sort = $data['sort_order'];
					$category->module = ['base'];
					$category->save();
				}				
			}		
			$transaction->commit();		
			return 1;	
		} catch(\Exception $e) {
			$transaction->rollBack();
			throw $e;
		} catch(\Throwable $e) {
			$transaction->rollBack();
			throw $e;
		}
	}
	/**
	 * 迁移article数据
	 */
	public function m_article(){
		
	}
	
	public function m_member(){
		$db2 = Yii::$app->db2;
		$db = Yii::$app->db;
		$datas = $db2->createCommand('SELECT * FROM {{%users}}')
		->queryAll();
		//var_dump($datas);
		
		
			foreach ($datas as $data){
				$transaction = $db->beginTransaction();
				try {
					$member = Member::findOne($data['user_id']);
					if (empty($member)){
						$member = new Member();
						$member->id = $data['user_id'];
						$member->username = $data['nickname'];
						$member->mobile = $data['mobile'];
						$member->mobile_validated = $data['mobile_validated'];
						$member->password = $data['password'];
						$member->pay_pwd = $data['paypwd'];
						$member->wx_openid = $data['openid'];
						$member->email = $data['email'];
						$member->email_validated = $data['email_validated'];
						$member->sex = '保密';
						//$member->province = $data['province'];
						//$member->city = $data[''];
						$member->score = $data['pay_points'];
						$member->level = $data['level'];
						$member->status = $data['is_lock'];
						
						
						$member->register_time = $data['reg_time'];
						$member->last_login = $data['last_login'];
						//$member->expire_in = $data[''];
						//$member->oauth_id = $data[''];
						//$member->flag = $data[''];
						$member->user_money = $data['user_money'];
						$member->frozen_money = $data['frozen_money'];
						$member->distribut_money = $data['distribut_money'];
						
						//$member->underling_number = $data[''];
						$member->total_amount = $data['total_amount'];
						$member->is_distribut = $data['is_distribut'];
						//$member->distribut_level = $data[''];
						//$member->first_leader = $data[''];
						//$member->second_leader = $data[''];
						//$member->third_leader = $data[''];
						//$member->message_mask = $data[''];
						//$member->push_id = $data[''];
						//$member->is_vip = $data[''];
						//$member->version = $data[''];
						$member->save();
						$transaction->commit();
					}
					
				} catch(\Exception $e) {
					$transaction->rollBack();
					throw $e;
				} catch(\Throwable $e) {
					$transaction->rollBack();
					throw $e;
				}
			}
			
			return 1;
		
	}
}