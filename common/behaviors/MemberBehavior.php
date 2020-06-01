<?php
/**
 * 
 * Author: vamper
 * DateTime: 2016/11/21 13:24
 * Description:
 */

namespace common\behaviors;


use Yii;
use yii\base\Behavior;
use common\models\Member;

/**
 * 
 */
class MemberBehavior extends Behavior
{
	public $memberIdAttribute = 'member_id';
	public function getMember()
	{
		return $this->owner->hasOne(Member::className(), ['id' => $this->memberIdAttribute]);
	}	
	
}