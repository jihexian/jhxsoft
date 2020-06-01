<?php

use yii\db\Migration;

class m181121_065505_migration extends Migration
{
    public function up()
    {
		$ta=\Yii::$app->db->createCommand("SHOW TABLES")->queryAll();
 
if(!\common\helpers\Util::deep_in_array('yj_access_token', $ta)){
 
$this->createTable('{{%access_token}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'uid' => 'INT(11) NOT NULL COMMENT \'用户id\'',
	'token' => 'VARCHAR(128) NOT NULL COMMENT \'token值\'',
	'expire_in' => 'INT(11) NOT NULL COMMENT \'在什么时间失效\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('token','{{%access_token}}','token',0);
$this->createIndex('uid','{{%access_token}}','uid',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_account_log', $ta)){
 
$this->createTable('{{%account_log}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'日志id\'',
	'member_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'用户id\'',
	'money' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'用户金额\'',
	'score' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'用户积分\'',
	'change_score' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'变动积分，正为增加，负为消费\'',
	'change_money' => 'DECIMAL(10,2) NOT NULL DEFAULT \'0.00\' COMMENT \'变动金额，正为增加，负为消费\'',
	'created_at' => 'INT(10) UNSIGNED NOT NULL COMMENT \'变动时间\'',
	'type' => 'TINYINT(3) UNSIGNED NOT NULL DEFAULT \'1\' COMMENT \'1:订单消费，2充值，3，活动赠送 4，管理员操作\'',
	'desc' => 'VARCHAR(255) NOT NULL COMMENT \'描述\'',
	'order_id' => 'INT(10) NULL COMMENT \'订单id\'',
	'user_id' => 'INT(11) UNSIGNED NULL COMMENT \'管理员id\'',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('member_id','{{%account_log}}','member_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_address', $ta)){
 
$this->createTable('{{%address}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'uid' => 'INT(100) NOT NULL COMMENT \'用户id\'',
	'userName' => 'CHAR(10) NOT NULL COMMENT \'收件人\'',
	'postalCode' => 'VARCHAR(50) NULL COMMENT \'邮政编码\'',
	'provinceName' => 'VARCHAR(100) NOT NULL COMMENT \'省\'',
	'cityName' => 'VARCHAR(100) NOT NULL COMMENT \'市\'',
	'countyName' => 'VARCHAR(200) NOT NULL COMMENT \'区\'',
	'detailInfo' => 'VARCHAR(255) NOT NULL COMMENT \'详细地址\'',
	'nationalCode' => 'CHAR(30) NULL COMMENT \'国家码\'',
	'telNumber' => 'VARCHAR(15) NOT NULL COMMENT \'电话号码\'',
	'status' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'状态\'',
	'sort' => 'INT(11) NULL DEFAULT \'99\' COMMENT \'排序，默认99\'',
	'is_default' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'是否为默认地址\'',
	'is_pickup' => 'TINYINT(1) NULL COMMENT \'是否使用提货点\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_admin_log', $ta)){
 
$this->createTable('{{%admin_log}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'route' => 'VARCHAR(255) NOT NULL',
	'description' => 'TEXT NULL',
	'created_at' => 'INT(10) NOT NULL',
	'user_id' => 'INT(10) NOT NULL DEFAULT \'0\'',
	'ip' => 'BIGINT(20) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_album', $ta)){
 
$this->createTable('{{%album}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL COMMENT \'相册名\'',
	'description' => 'VARCHAR(255) NULL COMMENT \'相册描述\'',
	'owner_id' => 'INT(11) NOT NULL COMMENT \'相册所有者\'',
	'user_id' => 'INT(11) NOT NULL COMMENT \'创建者\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_album_attachment', $ta)){
 
$this->createTable('{{%album_attachment}}', [
	'album_id' => 'INT(11) NOT NULL COMMENT \'相册ID\'',
	'attachment_id' => 'INT(11) NOT NULL COMMENT \'附件ID\'',
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_area', $ta)){
 
$this->createTable('{{%area}}', [
	'area_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(255) NOT NULL',
	'slug' => 'VARCHAR(255) NOT NULL',
	'description' => 'VARCHAR(255) NOT NULL',
	'blocks' => 'VARCHAR(255) NOT NULL',
	'PRIMARY KEY (`area_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_area_block', $ta)){
 
$this->createTable('{{%area_block}}', [
	'block_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(255) NOT NULL',
	'type' => 'VARCHAR(50) NULL',
	'widget' => 'TEXT NULL',
	'slug' => 'VARCHAR(255) NOT NULL',
	'config' => 'TEXT NULL',
	'template' => 'TEXT NULL',
	'cache' => 'INT(11) NOT NULL',
	'used' => 'SMALLINT(6) NOT NULL',
	'PRIMARY KEY (`block_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article', $ta)){
 
$this->createTable('{{%article}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(50) NOT NULL COMMENT \'标题\'',
	'category' => 'VARCHAR(50) NOT NULL COMMENT \'分类\'',
	'category_id' => 'INT(11) NOT NULL',
	'status' => 'TINYINT(1) NOT NULL COMMENT \'状态\'',
	'view' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'is_top' => 'SMALLINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否置顶\'',
	'is_hot' => 'SMALLINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否热门\'',
	'is_best' => 'SMALLINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否精华\'',
	'description' => 'VARCHAR(255) NOT NULL COMMENT \'摘要\'',
	'user_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'source' => 'VARCHAR(255) NOT NULL COMMENT \'来源\'',
	'deleted_at' => 'INT(10) NULL',
	'favourite' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'published_at' => 'INT(10) NOT NULL DEFAULT \'0\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'module' => 'VARCHAR(255) NULL DEFAULT \'base\' COMMENT \'文档类型\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('index_published_at','{{%article}}','published_at',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_data', $ta)){
 
$this->createTable('{{%article_data}}', [
	'id' => 'INT(11) NOT NULL',
	'content' => 'TEXT NOT NULL',
	'markdown' => 'SMALLINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否markdown格式\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_download', $ta)){
 
$this->createTable('{{%article_download}}', [
	'id' => 'INT(11) NOT NULL',
	'content' => 'TEXT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_exhibition', $ta)){
 
$this->createTable('{{%article_exhibition}}', [
	'id' => 'INT(11) NOT NULL',
	'start_at' => 'DATETIME NULL COMMENT \'开始时间\'',
	'end_at' => 'DATETIME NULL COMMENT \'结束时间\'',
	'city' => 'VARCHAR(50) NULL COMMENT \'举办城市\'',
	'address' => 'VARCHAR(255) NULL COMMENT \'举办地址\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_module', $ta)){
 
$this->createTable('{{%article_module}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NULL',
	'title' => 'VARCHAR(50) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_photo', $ta)){
 
$this->createTable('{{%article_photo}}', [
	'id' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_article_tag', $ta)){
 
$this->createTable('{{%article_tag}}', [
	'article_id' => 'INT(10) NOT NULL',
	'tag_id' => 'INT(10) NOT NULL',
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_attachment', $ta)){
 
$this->createTable('{{%attachment}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NULL',
	'name' => 'VARCHAR(255) NULL',
	'title' => 'VARCHAR(255) NULL',
	'description' => 'VARCHAR(255) NULL',
	'path' => 'VARCHAR(255) NOT NULL',
	'thumbImg' => 'VARCHAR(100) NULL COMMENT \'缩略图地址\'',
	'hash' => 'VARCHAR(64) NOT NULL',
	'size' => 'INT(11) NULL',
	'type' => 'VARCHAR(255) NULL',
	'extension' => 'VARCHAR(255) NULL',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_attachment_index', $ta)){
 
$this->createTable('{{%attachment_index}}', [
	'attachment_id' => 'INT(11) NOT NULL',
	'entity' => 'VARCHAR(80) NOT NULL',
	'entity_id' => 'INT(11) NOT NULL',
	'attribute' => 'VARCHAR(20) NOT NULL',
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_attribute', $ta)){
 
$this->createTable('{{%attribute}}', [
	'attribute_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'type_id' => 'INT(11) NULL COMMENT \'关联的product_type\'',
	'attribute_name' => 'VARCHAR(45) NULL COMMENT \'规格名称\'',
	'sort' => 'INT(11) NULL DEFAULT \'99\' COMMENT \'排序\'',
	'usage_mode' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'类型：1文字，2图片\'',
	'is_system' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否为系统值0否1是\'',
	'PRIMARY KEY (`attribute_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_attribute_value', $ta)){
 
$this->createTable('{{%attribute_value}}', [
	'value_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'attribute_id' => 'INT(11) NULL',
	'sort' => 'INT(11) NULL DEFAULT \'99\' COMMENT \'排序\'',
	'value_str' => 'VARCHAR(200) NULL COMMENT \'规格值(文字/图片)\'',
	'image_url' => 'VARCHAR(255) NULL COMMENT \'对应的商品展示图片路径\'',
	'is_system' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否为系统值0否1是\'',
	'PRIMARY KEY (`value_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_auth', $ta)){
 
$this->createTable('{{%auth}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NOT NULL',
	'source' => 'VARCHAR(255) NOT NULL',
	'source_id' => 'VARCHAR(255) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_auth_assignment', $ta)){
 
$this->createTable('{{%auth_assignment}}', [
	'item_name' => 'VARCHAR(64) NOT NULL',
	'user_id' => 'VARCHAR(64) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'PRIMARY KEY (`item_name`,`user_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_auth_item', $ta)){
 
$this->createTable('{{%auth_item}}', [
	'name' => 'VARCHAR(64) NOT NULL',
	'type' => 'INT(11) NOT NULL',
	'description' => 'TEXT NULL',
	'rule_name' => 'VARCHAR(64) NULL',
	'data' => 'TEXT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`name`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('fk_auth_item_rule_name','{{%auth_item}}','rule_name',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_auth_item_child', $ta)){
 
$this->createTable('{{%auth_item_child}}', [
	'parent' => 'VARCHAR(64) NOT NULL',
	'child' => 'VARCHAR(64) NOT NULL',
	'PRIMARY KEY (`parent`,`child`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('fk_auth_item_child_child','{{%auth_item_child}}','child',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_auth_rule', $ta)){
 
$this->createTable('{{%auth_rule}}', [
	'name' => 'VARCHAR(64) NOT NULL',
	'data' => 'TEXT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`name`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_book', $ta)){
 
$this->createTable('{{%book}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'book_name' => 'VARCHAR(50) NOT NULL COMMENT \'书名\'',
	'book_author' => 'INT(11) NOT NULL COMMENT \'作者\'',
	'book_link' => 'VARCHAR(128) NULL COMMENT \'书外链\'',
	'book_description' => 'VARCHAR(1000) NOT NULL COMMENT \'书简介\'',
	'category_id' => 'INT(11) NOT NULL COMMENT \'书分类\'',
	'created_at' => 'INT(11) NOT NULL',
	'updated_at' => 'INT(11) NOT NULL',
	'view' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_book_category', $ta)){
 
$this->createTable('{{%book_category}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'category_name' => 'VARCHAR(80) NOT NULL COMMENT \'分类名\'',
	'created_at' => 'INT(11) NOT NULL',
	'updated_at' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_book_chapter', $ta)){
 
$this->createTable('{{%book_chapter}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'book_id' => 'INT(11) NOT NULL COMMENT \'书\'',
	'chapter_name' => 'VARCHAR(80) NOT NULL COMMENT \'章节标题\'',
	'chapter_body' => 'TEXT NULL COMMENT \'章节正文\'',
	'pid' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'sort' => 'SMALLINT(1) NOT NULL DEFAULT \'0\'',
	'created_at' => 'INT(11) NOT NULL',
	'updated_at' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_carousel', $ta)){
 
$this->createTable('{{%carousel}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'key' => 'VARCHAR(128) NOT NULL',
	'title' => 'VARCHAR(255) NOT NULL',
	'status' => 'SMALLINT(6) NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_carousel_item', $ta)){
 
$this->createTable('{{%carousel_item}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'carousel_id' => 'INT(11) NOT NULL',
	'url' => 'VARCHAR(1024) NULL',
	'caption' => 'VARCHAR(1024) NULL',
	'image' => 'VARCHAR(255) NULL',
	'thumbImg' => 'VARCHAR(255) NULL COMMENT \'缩略图地址\'',
	'status' => 'SMALLINT(6) NOT NULL DEFAULT \'0\'',
	'sort' => 'INT(11) NULL DEFAULT \'0\'',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_cart', $ta)){
 
$this->createTable('{{%cart}}', [
	'id' => 'INT(8) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'购物车表\'',
	'user_id' => 'MEDIUMINT(8) UNSIGNED NOT NULL COMMENT \'用户id\'',
	'session_id' => 'CHAR(128) NOT NULL COMMENT \'session\'',
	'product_id' => 'MEDIUMINT(8) UNSIGNED NOT NULL COMMENT \'商品id\'',
	'product_sn' => 'VARCHAR(60) NULL COMMENT \'商品货号\'',
	'product_name' => 'VARCHAR(120) NOT NULL COMMENT \'商品名称\'',
	'market_price' => 'DECIMAL(10,2) UNSIGNED NULL DEFAULT \'0.00\' COMMENT \'市场价\'',
	'sale_price' => 'DECIMAL(10,2) NOT NULL DEFAULT \'0.00\' COMMENT \'本店价\'',
	'member_goods_price' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'会员折扣价\'',
	'num' => 'SMALLINT(5) UNSIGNED NULL DEFAULT \'0\' COMMENT \'购买数量\'',
	'sku_id' => 'VARCHAR(64) NULL COMMENT \'对应表的sku_id\'',
	'sku_values' => 'VARCHAR(300) NULL COMMENT \'商品规格组合名称\'',
	'bar_code' => 'VARCHAR(64) NULL COMMENT \'商品条码\'',
	'selected' => 'TINYINT(1) NULL DEFAULT \'1\' COMMENT \'购物车选中状态\'',
	'create_time' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'加入购物车的时间\'',
	'update_time' => 'INT(11) NULL',
	'prom_type' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'0 普通订单,1 限时抢购, 2 团购 , 3 促销优惠\'',
	'prom_id' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'活动id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('session_id','{{%cart}}','session_id',0);
$this->createIndex('user_id','{{%cart}}','user_id',0);
$this->createIndex('goods_id','{{%cart}}','product_id',0);
$this->createIndex('spec_key','{{%cart}}','sku_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_category', $ta)){
 
$this->createTable('{{%category}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(50) NOT NULL COMMENT \'名字\'',
	'pid' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'父id\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'slug' => 'VARCHAR(20) NOT NULL',
	'description' => 'VARCHAR(1000) NOT NULL',
	'article' => 'INT(10) NOT NULL DEFAULT \'0\'',
	'sort' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'width' => 'INT(11) NOT NULL DEFAULT \'320\' COMMENT \'缩略图宽度\'',
	'height' => 'INT(11) NOT NULL DEFAULT \'320\' COMMENT \'缩略图高度\'',
	'allow_publish' => 'SMALLINT(1) NULL DEFAULT \'1\' COMMENT \'是否允许发布内容\'',
	'module' => 'VARCHAR(255) NULL DEFAULT \'base\' COMMENT \'文档类型\'',
	'status' => 'INT(11) NOT NULL DEFAULT \'1\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_category_model', $ta)){
 
$this->createTable('{{%category_model}}', [
	'model_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'model_name' => 'VARCHAR(50) NULL',
	'status' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'0禁止1正常\'',
	'is_system' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否系统内置模型0否1是\'',
	'PRIMARY KEY (`model_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_category_model_attr', $ta)){
 
$this->createTable('{{%category_model_attr}}', [
	'model_attr_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'attr_name' => 'VARCHAR(50) NULL',
	'type' => 'TINYINT(1) NULL DEFAULT \'1\' COMMENT \'输入控件的类型,1:单选,2:复选,3:下拉,（4:输入框，4暂时舍弃）\'',
	'search' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'0不支持，1支持\'',
	'model_id' => 'INT(11) NULL',
	'sort' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'排序\'',
	'status' => 'TINYINT(3) NULL DEFAULT \'1\' COMMENT \'1启用0禁用\'',
	'img_url' => 'VARCHAR(255) NULL COMMENT \'图片路径\'',
	'PRIMARY KEY (`model_attr_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_category_model_attr_value', $ta)){
 
$this->createTable('{{%category_model_attr_value}}', [
	'model_attr_value_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'model_attribute_id' => 'INT(11) NULL',
	'value_str' => 'VARCHAR(255) NULL COMMENT \'属性值\'',
	'sort' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'排序\'',
	'status' => 'TINYINT(3) NULL DEFAULT \'1\' COMMENT \'1启用0禁用\'',
	'img_url' => 'VARCHAR(255) NULL COMMENT \'图片地址\'',
	'PRIMARY KEY (`model_attr_value_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_city', $ta)){
 
$this->createTable('{{%city}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NULL',
	'parent_id' => 'INT(11) NULL',
	'sort' => 'SMALLINT(1) NULL',
	'deep' => 'SMALLINT(1) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('parent_id','{{%city}}','parent_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_collection', $ta)){
 
$this->createTable('{{%collection}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'product_id' => 'INT(11) NULL',
	'member_id' => 'INT(11) NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_comment', $ta)){
 
$this->createTable('{{%comment}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'member_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'user_ip' => 'VARCHAR(20) NULL COMMENT \'用户ip\'',
	'entity' => 'VARCHAR(80) NOT NULL',
	'entity_id' => 'INT(11) NOT NULL',
	'content' => 'TEXT NOT NULL COMMENT \'内容\'',
	'parent_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'reply_member_id' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'被回复的用户id，即回复的贴主id\'',
	'is_top' => 'SMALLINT(1) NOT NULL DEFAULT \'0\'',
	'status' => 'SMALLINT(1) NOT NULL DEFAULT \'1\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'user_id' => 'INT(11) NULL COMMENT \'后台管理员id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('entity','{{%comment}}','entity, entity_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_comment_info', $ta)){
 
$this->createTable('{{%comment_info}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'entity' => 'VARCHAR(80) NOT NULL',
	'entity_id' => 'INT(11) NOT NULL',
	'status' => 'TINYINT(1) NOT NULL DEFAULT \'1\'',
	'total' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('entity','{{%comment_info}}','entity, entity_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_config', $ta)){
 
$this->createTable('{{%config}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NOT NULL COMMENT \'配置名\'',
	'value' => 'TEXT NULL COMMENT \'配置值\'',
	'extra' => 'TEXT NOT NULL',
	'description' => 'VARCHAR(255) NULL COMMENT \'配置描述\'',
	'type' => 'VARCHAR(30) NULL DEFAULT \'text\' COMMENT \'配置类型\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'group' => 'VARCHAR(30) NULL DEFAULT \'system\' COMMENT \'配置分组\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_delivery_doc', $ta)){
 
$this->createTable('{{%delivery_doc}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'发货单ID\'',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单ID\'',
	'order_sn' => 'VARCHAR(64) NOT NULL COMMENT \'订单编号\'',
	'user_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'用户ID\'',
	'admin_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'管理员ID\'',
	'consignee' => 'VARCHAR(64) NOT NULL COMMENT \'收货人\'',
	'zipcode' => 'VARCHAR(6) NULL COMMENT \'邮编\'',
	'mobile' => 'VARCHAR(20) NOT NULL COMMENT \'联系手机\'',
	'country' => 'INT(11) UNSIGNED NOT NULL COMMENT \'国ID\'',
	'province' => 'INT(11) UNSIGNED NOT NULL COMMENT \'省ID\'',
	'city' => 'INT(11) UNSIGNED NOT NULL COMMENT \'市ID\'',
	'district' => 'INT(11) UNSIGNED NOT NULL COMMENT \'区ID\'',
	'address' => 'VARCHAR(255) NOT NULL COMMENT \'地址\'',
	'shipping_code' => 'VARCHAR(32) NULL COMMENT \'物流code\'',
	'shipping_name' => 'VARCHAR(64) NULL COMMENT \'快递名称\'',
	'shipping_price' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'运费\'',
	'invoice_no' => 'VARCHAR(255) NOT NULL COMMENT \'物流单号\'',
	'tel' => 'VARCHAR(64) NULL COMMENT \'座机电话\'',
	'note' => 'TEXT NULL COMMENT \'管理员添加的备注信息\'',
	'best_time' => 'INT(11) NULL COMMENT \'友好收货时间\'',
	'create_time' => 'INT(11) NOT NULL COMMENT \'创建时间\'',
	'is_del' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'是否删除\'',
	'send_type' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'发货方式0自填快递1在线预约2电子面单3无需物流\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('order_id','{{%delivery_doc}}','order_id',0);
$this->createIndex('user_id','{{%delivery_doc}}','user_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_favourite', $ta)){
 
$this->createTable('{{%favourite}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NOT NULL',
	'article_id' => 'INT(11) NOT NULL',
	'created_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_flash_sale', $ta)){
 
$this->createTable('{{%flash_sale}}', [
	'id' => 'BIGINT(10) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(200) NOT NULL COMMENT \'活动标题\'',
	'goods_id' => 'INT(10) NOT NULL COMMENT \'参团商品ID\'',
	'sku_id' => 'VARCHAR(255) NULL',
	'price' => 'DECIMAL(10,2) NULL COMMENT \'活动价格\'',
	'goods_num' => 'INT(10) NULL COMMENT \'商品参加活动数\'',
	'buy_limit' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'每人限购数\'',
	'buy_num' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'已购买人数\'',
	'order_num' => 'INT(10) NULL DEFAULT \'0\' COMMENT \'已下单数\'',
	'description' => 'TEXT NULL COMMENT \'活动描述\'',
	'start_time' => 'INT(11) NULL COMMENT \'开始时间\'',
	'end_time' => 'INT(11) NULL COMMENT \'结束时间\'',
	'status' => 'TINYINT(1) NULL DEFAULT \'1\' COMMENT \'状态：0禁用1启用\'',
	'goods_name' => 'VARCHAR(255) NULL COMMENT \'商品名称\'',
	'version' => 'BIGINT(20) NULL COMMENT \'版本号\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_friend', $ta)){
 
$this->createTable('{{%friend}}', [
	'owner_id' => 'INT(11) NOT NULL COMMENT \'自己\'',
	'friend_id' => 'INT(11) NOT NULL COMMENT \'朋友\'',
	'created_at' => 'INT(11) NOT NULL',
	'updated_at' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`owner_id`,`friend_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('friend','{{%friend}}','owner_id, friend_id',1);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_gather', $ta)){
 
$this->createTable('{{%gather}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'url_org' => 'VARCHAR(255) NOT NULL',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_i18n_message', $ta)){
 
$this->createTable('{{%i18n_message}}', [
	'id' => 'INT(11) NOT NULL',
	'language' => 'VARCHAR(16) NOT NULL',
	'translation' => 'TEXT NULL',
	'PRIMARY KEY (`id`,`language`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_i18n_source_message', $ta)){
 
$this->createTable('{{%i18n_source_message}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'category' => 'VARCHAR(32) NULL',
	'message' => 'TEXT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_member', $ta)){
 
$this->createTable('{{%member}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'username' => 'VARCHAR(75) NULL COMMENT \'用户姓名\'',
	'mobile' => 'VARCHAR(20) NULL COMMENT \'手机号码\'',
	'mobile_validated' => 'TINYINT(2) NULL DEFAULT \'0\' COMMENT \'手机是否验证\'',
	'password' => 'VARCHAR(64) NULL COMMENT \'登录密码\'',
	'auth_key' => 'VARCHAR(30) NULL COMMENT \'验证码\'',
	'xcx_openid' => 'VARCHAR(64) NULL COMMENT \'小程序openid\'',
	'wx_openid' => 'VARCHAR(64) NULL COMMENT \'微信openid\'',
	'avatar' => 'VARCHAR(200) NULL COMMENT \'用户头像\'',
	'avatarUrl' => 'VARCHAR(200) NULL COMMENT \'用户微信头像\'',
	'email' => 'VARCHAR(30) NULL',
	'email_validated' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'邮箱验证\'',
	'sex' => 'VARCHAR(6) NULL COMMENT \'性别\'',
	'age' => 'FLOAT NULL COMMENT \'年龄\'',
	'province' => 'VARCHAR(100) NULL',
	'city' => 'VARCHAR(100) NULL',
	'score' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'积分\'',
	'level' => 'INT(11) NOT NULL DEFAULT \'1\' COMMENT \'1普通会员，2VIP会员,详情看member_level表\'',
	'status' => 'INT(11) NOT NULL DEFAULT \'1\' COMMENT \'0禁用1正常\'',
	'register_time' => 'INT(11) NULL COMMENT \'注册时间\'',
	'last_login' => 'INT(11) NULL COMMENT \'最后登录时间\'',
	'access_token' => 'VARCHAR(128) NULL',
	'expire_in' => 'INT(11) NULL',
	'oauth_id' => 'VARCHAR(128) NOT NULL COMMENT \'第三方平台授权id\'',
	'flag' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'授权次数\'',
	'pay_pwd' => 'VARCHAR(250) NULL COMMENT \'支付密码\'',
	'user_money' => 'DECIMAL(10,2) NULL COMMENT \'用户余额\'',
	'frozen_money' => 'DECIMAL(10,2) NULL COMMENT \'冻结资金\'',
	'distribut_money' => 'DECIMAL(10,2) NULL COMMENT \'累积分佣金额\'',
	'underling_number' => 'VARCHAR(45) NULL COMMENT \'分销下线人数\'',
	'total_amount' => 'DECIMAL(10,2) NULL COMMENT \'累积消费金额\'',
	'is_distribut' => 'INT(11) NULL COMMENT \'是否是分销商\'',
	'distribut_level' => 'INT(11) NULL COMMENT \'分销等级\'',
	'first_leader' => 'INT(11) NULL COMMENT \'一级\'',
	'second_leader' => 'INT(11) NULL COMMENT \'二级\'',
	'third_leader' => 'INT(11) NULL COMMENT \'三级\'',
	'message_mask' => 'VARCHAR(100) NULL COMMENT \'消息掩码\'',
	'push_id' => 'VARCHAR(45) NULL COMMENT \'推送id \'',
	'is_vip' => 'INT(11) NULL COMMENT \'是否是vip\'',
	'version' => 'BIGINT(11) NULL DEFAULT \'1\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_member_address', $ta)){
 
$this->createTable('{{%member_address}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'uid' => 'INT(11) NOT NULL',
	'province' => 'VARCHAR(50) NULL COMMENT \'省\'',
	'city' => 'VARCHAR(50) NULL COMMENT \'市\'',
	'country' => 'VARCHAR(50) NULL COMMENT \'区/县\'',
	'address' => 'VARCHAR(500) NOT NULL COMMENT \'详细地址\'',
	'name' => 'VARCHAR(50) NOT NULL COMMENT \'姓名\'',
	'mobile' => 'VARCHAR(20) NOT NULL COMMENT \'手机号码\'',
	'postcode' => 'VARCHAR(10) NULL COMMENT \'邮政编码\'',
	'isdefault' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'是否默认\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_member_distribution', $ta)){
 
$this->createTable('{{%member_distribution}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NULL COMMENT \'分销会员id\'',
	'user_name' => 'VARCHAR(50) NULL COMMENT \'会员昵称\'',
	'goods_id' => 'INT(11) NULL COMMENT \'商品id\'',
	'goods_name' => 'VARCHAR(150) NULL COMMENT \'商品名称\'',
	'cat_id' => 'SMALLINT(6) NULL DEFAULT \'0\' COMMENT \'商品分类ID\'',
	'brand_id' => 'MEDIUMINT(8) NULL DEFAULT \'0\' COMMENT \'商品品牌\'',
	'share_num' => 'INT(10) NULL DEFAULT \'0\' COMMENT \'分享次数\'',
	'sales_num' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'分销销量\'',
	'addtime' => 'INT(11) NULL COMMENT \'加入个人分销库时间\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('goods_id','{{%member_distribution}}','goods_id',0);
$this->createIndex('user_id','{{%member_distribution}}','user_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_member_extend', $ta)){
 
$this->createTable('{{%member_extend}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) UNSIGNED NULL',
	'invoice_title' => 'VARCHAR(200) NULL COMMENT \'发票抬头\'',
	'taxpayer' => 'VARCHAR(100) NULL COMMENT \'纳税人识别号\'',
	'invoice_desc' => 'VARCHAR(50) NULL COMMENT \'不开发票/明细\'',
	'realname' => 'VARCHAR(100) NULL COMMENT \'真实姓名\'',
	'idcard' => 'VARCHAR(100) NULL COMMENT \'身份证号\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_member_level', $ta)){
 
$this->createTable('{{%member_level}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'pid' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'name' => 'VARCHAR(50) NOT NULL',
	'sort' => 'INT(11) NOT NULL DEFAULT \'99\'',
	'status' => 'INT(11) NOT NULL DEFAULT \'1\'',
	'create_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_menu', $ta)){
 
$this->createTable('{{%menu}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(128) NOT NULL',
	'parent' => 'INT(11) NULL',
	'route' => 'VARCHAR(256) NULL',
	'order' => 'INT(11) NULL',
	'data' => 'TEXT NULL',
	'icon' => 'VARCHAR(50) NULL',
	'status' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'0显示1隐藏\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('parent','{{%menu}}','parent',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_message', $ta)){
 
$this->createTable('{{%message}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'from_uid' => 'INT(11) NOT NULL',
	'to_uid' => 'INT(11) NOT NULL',
	'message_id' => 'INT(11) NOT NULL COMMENT \'消息ID\'',
	'read' => 'SMALLINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否阅读\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_message_data', $ta)){
 
$this->createTable('{{%message_data}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'content' => 'TEXT NULL COMMENT \'消息内容\'',
	'group' => 'VARCHAR(128) NULL',
	'created_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_meta', $ta)){
 
$this->createTable('{{%meta}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'title' => 'VARCHAR(128) NULL',
	'keywords' => 'VARCHAR(128) NULL',
	'description' => 'VARCHAR(128) NULL',
	'entity' => 'VARCHAR(80) NULL',
	'entity_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_module', $ta)){
 
$this->createTable('{{%module}}', [
	'id' => 'VARCHAR(50) NOT NULL COMMENT \'标识\'',
	'name' => 'VARCHAR(50) NOT NULL',
	'bootstrap' => 'VARCHAR(128) NULL COMMENT \'模块初始化应用ID\'',
	'status' => 'SMALLINT(1) NOT NULL',
	'type' => 'SMALLINT(1) NOT NULL COMMENT \'模块类型1module2plugin\'',
	'config' => 'TEXT NULL COMMENT \'配置\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('id','{{%module}}','id',1);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_nav', $ta)){
 
$this->createTable('{{%nav}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'key' => 'VARCHAR(128) NULL',
	'title' => 'VARCHAR(128) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_nav_item', $ta)){
 
$this->createTable('{{%nav_item}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'nav_id' => 'INT(11) NULL',
	'title' => 'VARCHAR(128) NULL',
	'url' => 'VARCHAR(128) NULL',
	'target' => 'SMALLINT(1) NULL DEFAULT \'0\' COMMENT \'是否新窗口打开\'',
	'order' => 'SMALLINT(1) NULL',
	'status' => 'SMALLINT(1) NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_notify', $ta)){
 
$this->createTable('{{%notify}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'from_uid' => 'INT(11) NOT NULL',
	'to_uid' => 'INT(11) NOT NULL',
	'category_id' => 'INT(11) NULL COMMENT \'通知分类ID\'',
	'extra' => 'TEXT NULL COMMENT \'附加信息\'',
	'created_at' => 'INT(10) NOT NULL',
	'read' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('notify_from_uid_index','{{%notify}}','from_uid',0);
$this->createIndex('notify_to_uid_index','{{%notify}}','to_uid',0);
$this->createIndex('notify_category_id_index','{{%notify}}','category_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_notify_category', $ta)){
 
$this->createTable('{{%notify_category}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NULL',
	'title' => 'VARCHAR(255) NULL',
	'content' => 'VARCHAR(255) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('name','{{%notify_category}}','name',1);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order', $ta)){
 
$this->createTable('{{%order}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'm_id' => 'INT(11) NOT NULL COMMENT \'用户id\'',
	'order_no' => 'VARCHAR(32) NOT NULL COMMENT \'订单号\'',
	'payment_id' => 'INT(11) NOT NULL COMMENT \'支付方式0为货到付款\'',
	'payment_name' => 'VARCHAR(45) NULL COMMENT \'支付方式名称\'',
	'payment_status' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'支付状态0未支付1已经支付\'',
	'payment_no' => 'VARCHAR(50) NULL COMMENT \'第三方支付交易号\'',
	'delivery_id' => 'INT(11) NOT NULL COMMENT \'配送方式\'',
	'delivery_name' => 'VARCHAR(45) NULL COMMENT \'快递名称\'',
	'delivery_time' => 'VARCHAR(50) NULL COMMENT \'配送时间\'',
	'delivery_status' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'发货状态0未发货1已发货2为部分发货\'',
	'shop_id' => 'INT(11) NOT NULL DEFAULT \'1\' COMMENT \'店铺id\'',
	'is_shop_checkout' => 'INT(11) NOT NULL COMMENT \'是否给店铺结算货款 0:未结算;2:等待结算1:已结算\'',
	'status' => 'TINYINT(2) NOT NULL DEFAULT \'1\' COMMENT \'订单状态 1生成订单,2支付订单,3已经发货,4完成订单,5已经评价6退款,7部分退款8用户取消订单,9作废订单,10退款中\'',
	'full_name' => 'VARCHAR(50) NULL COMMENT \'收货人姓名\'',
	'tel' => 'VARCHAR(50) NULL COMMENT \'收货人电话\'',
	'prov' => 'VARCHAR(100) NULL COMMENT \'省\'',
	'city' => 'VARCHAR(100) NULL COMMENT \'市\'',
	'area' => 'VARCHAR(100) NULL COMMENT \'区\'',
	'address' => 'VARCHAR(200) NULL COMMENT \'详细地址\'',
	'sku_price' => 'DECIMAL(10,2) NOT NULL COMMENT \'商品市场总价单位\'',
	'sku_price_real' => 'DECIMAL(10,2) NOT NULL COMMENT \'商品销售价格单位\'',
	'delivery_price' => 'DECIMAL(10,2) NOT NULL COMMENT \'物流原价单位\'',
	'delivery_price_real' => 'DECIMAL(10,2) NOT NULL COMMENT \'物流支付价格单位\'',
	'discount_price' => 'DECIMAL(10,2) NOT NULL COMMENT \'改价金额单位\'',
	'order_price' => 'DECIMAL(10,2) NOT NULL COMMENT \'订单总金额单位\'',
	'pay_amount' => 'DECIMAL(10,2) NOT NULL COMMENT \'应付总价，订单总价order_price+邮费价格deliver_price+改价金额+活动减价+积分抵扣-用户使用余额\'',
	'coupons_id' => 'INT(11) NULL COMMENT \'优惠券id\'',
	'coupons_price' => 'DECIMAL(10,2) NULL COMMENT \'优惠券金额\'',
	'order_prom_id' => 'INT(11) NULL COMMENT \'订单活动（如满减活动）\'',
	'order_prom_money' => 'DECIMAL(10,2) NULL COMMENT \'订单活动扣除金额\'',
	'integral' => 'INT(11) NULL COMMENT \'使用积分\'',
	'integral_money' => 'DECIMAL(10,2) NULL COMMENT \'积分抵扣金额\'',
	'user_money' => 'DECIMAL(10,2) NULL COMMENT \'用户使用余额\'',
	'm_desc' => 'VARCHAR(255) NULL COMMENT \'用户备注\'',
	'admin_desc' => 'VARCHAR(255) NULL COMMENT \'管理员备注\'',
	'create_time' => 'INT(11) NULL COMMENT \'下单时间\'',
	'paytime' => 'INT(11) NULL COMMENT \'支付时间\'',
	'sendtime' => 'INT(11) NULL COMMENT \'发货时间\'',
	'completetime' => 'INT(11) NULL COMMENT \'完成时间\'',
	'is_del' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'0为正常1为删除\'',
	'invoice_title' => 'VARCHAR(145) NULL COMMENT \'发票抬头\'',
	'taxpayer' => 'VARCHAR(45) NULL COMMENT \'税务识别号\'',
	'is_distribut' => 'TINYINT(1) NULL COMMENT \'是否已分成\'',
	'paid_money' => 'DECIMAL(10,2) NULL COMMENT \'订金\'',
	'update_time' => 'INT(11) NULL',
	'parent_sn' => 'VARCHAR(80) NULL COMMENT \'父单单号\'',
	'prom_id' => 'INT(11) NULL COMMENT \'活动id\'',
	'prom_type' => 'TINYINT(2) NULL COMMENT \'订单类型：0普通订单4预售订单5虚拟订单6拼团订单\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('m_id','{{%order}}','m_id',0);
$this->createIndex('order_no','{{%order}}','order_no',0);
$this->createIndex('shop_id','{{%order}}','shop_id',0);
$this->createIndex('status','{{%order}}','status',0);
$this->createIndex('is_shop_checkout','{{%order}}','is_shop_checkout',0);
$this->createIndex('addtime','{{%order}}','create_time',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_collection_doc', $ta)){
 
$this->createTable('{{%order_collection_doc}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'收款记录表\'',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单id\'',
	'm_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'用户ID\'',
	'amount' => 'DECIMAL(10,2) NOT NULL COMMENT \'金额单位分\'',
	'addtime' => 'INT(11) NOT NULL COMMENT \'时间\'',
	'payment_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'支付方式ID\'',
	'note' => 'TEXT NULL COMMENT \'收款备注\'',
	'admin_user' => 'VARCHAR(50) NULL COMMENT \'管理员id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_delivery_doc', $ta)){
 
$this->createTable('{{%order_delivery_doc}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单ID\'',
	'm_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'用户ID\'',
	'shop_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'店铺ID\'',
	'addtime' => 'INT(11) NOT NULL COMMENT \'创建时间\'',
	'delivery_code' => 'VARCHAR(255) NOT NULL COMMENT \'物流单号\'',
	'express_company_id' => 'INT(11) NOT NULL COMMENT \'物流方式\'',
	'note' => 'TEXT NULL COMMENT \'备注信息\'',
	'admin_user' => 'VARCHAR(50) NULL COMMENT \'管理员id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('order_id','{{%order_delivery_doc}}','order_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_log', $ta)){
 
$this->createTable('{{%order_log}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单id\'',
	'action_user' => 'INT(11) NULL COMMENT \'操作人\'',
	'order_status' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'订单状态 \'',
	'shipping_status' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'配送状态\'',
	'pay_status' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'支付状态 \'',
	'action_note' => 'VARCHAR(255) NOT NULL COMMENT \'操作备注\'',
	'create_time' => 'INT(11) NULL COMMENT \'记录时间\'',
	'status_desc' => 'VARCHAR(45) NULL COMMENT \'状态描述\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('order_id','{{%order_log}}','order_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_refund_doc', $ta)){
 
$this->createTable('{{%order_refund_doc}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单id\'',
	'm_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'用户ID\'',
	'goods_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'要退款的商品\'',
	'sku_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'要退款的货品\'',
	'note' => 'TEXT NULL COMMENT \'退款理由\'',
	'addtime' => 'INT(11) NULL COMMENT \'时间\'',
	'status' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'退款状态，0:申请退款 1:退款失败 2:退款成功\'',
	'dispose_time' => 'INT(11) NULL COMMENT \'处理时间\'',
	'admin_user' => 'VARCHAR(50) NULL COMMENT \'处理管理员id\'',
	'shop_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'店铺ID\'',
	'amount' => 'DECIMAL(10,2) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_refund_doc_log', $ta)){
 
$this->createTable('{{%order_refund_doc_log}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'doc_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'退款单id\'',
	'desc' => 'TEXT NOT NULL COMMENT \'内容\'',
	'addtime' => 'INT(11) NULL COMMENT \'添加时间\'',
	'admin_user' => 'VARCHAR(50) NULL COMMENT \'管理员id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('doc_id','{{%order_refund_doc_log}}','doc_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_order_sku', $ta)){
 
$this->createTable('{{%order_sku}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'order_id' => 'INT(11) UNSIGNED NOT NULL COMMENT \'订单id\'',
	'order_no' => 'VARCHAR(100) NOT NULL COMMENT \'订单编号\'',
	'goods_id' => 'INT(11) NOT NULL COMMENT \'商品id\'',
	'goods_name' => 'VARCHAR(100) NOT NULL COMMENT \'商品名称\'',
	'sku_id' => 'VARCHAR(50) NOT NULL COMMENT \'skuid\'',
	'sku_no' => 'VARCHAR(50) NULL COMMENT \'sku编码\'',
	'sku_image' => 'VARCHAR(255) NULL COMMENT \'商品图片\'',
	'sku_thumbImg' => 'VARCHAR(150) NULL',
	'num' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'商品数量\'',
	'sku_market_price' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'市场价格单位分\'',
	'sku_sell_price_real' => 'DECIMAL(10,2) NOT NULL DEFAULT \'0.00\' COMMENT \'支付价格单位分\'',
	'sku_weight' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'商品重量\'',
	'sku_value' => 'TEXT NULL COMMENT \'规格属性数组\'',
	'is_send' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否已发货 0、未发货;1、已发货;\'',
	'is_comment' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'是否评价，0否 1是\'',
	'is_refund' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'是否退款0.为正常,1.退款中,2.退款完成\'',
	'shop_id' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'店铺id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('order_id','{{%order_sku}}','order_id',0);
$this->createIndex('goods_id','{{%order_sku}}','goods_id',0);
$this->createIndex('shop_id','{{%order_sku}}','shop_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_page', $ta)){
 
$this->createTable('{{%page}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'use_layout' => 'TINYINT(1) NOT NULL DEFAULT \'1\' COMMENT \'0:不使用1:使用\'',
	'content' => 'TEXT NOT NULL COMMENT \'内容\'',
	'title' => 'VARCHAR(50) NOT NULL COMMENT \'标题\'',
	'slug' => 'VARCHAR(50) NOT NULL',
	'markdown' => 'SMALLINT(1) NULL DEFAULT \'0\' COMMENT \'是否markdown格式\'',
	'created_at' => 'INT(10) NULL',
	'updated_at' => 'INT(10) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_payment', $ta)){
 
$this->createTable('{{%payment}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NOT NULL COMMENT \'支付名称\'',
	'logo' => 'VARCHAR(255) NULL COMMENT \'logo\'',
	'type' => 'TINYINT(1) NOT NULL DEFAULT \'1\' COMMENT \'1:线上、2:线下\'',
	'class_name' => 'VARCHAR(50) NOT NULL COMMENT \'支付类名称\'',
	'desc' => 'TEXT NULL COMMENT \'描述\'',
	'status' => 'TINYINT(1) NOT NULL DEFAULT \'1\' COMMENT \'安装状态 0启用 1禁用\'',
	'sortnum' => 'SMALLINT(5) NOT NULL DEFAULT \'0\' COMMENT \'排序\'',
	'config' => 'TEXT NULL COMMENT \'配置参数,json数据对象\'',
	'client_type' => 'TINYINT(1) NOT NULL DEFAULT \'1\' COMMENT \'1:PC端 2:移动端 3:通用\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_plugin', $ta)){
 
$this->createTable('{{%plugin}}', [
	'id' => 'VARCHAR(50) NOT NULL COMMENT \'标识\'',
	'name' => 'VARCHAR(50) NOT NULL',
	'description' => 'VARCHAR(128) NULL',
	'status' => 'SMALLINT(1) NOT NULL',
	'config' => 'TEXT NULL COMMENT \'配置\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'type' => 'VARCHAR(50) NULL',
	'scene' => 'INT(11) NULL',
	'code' => 'VARCHAR(50) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_product', $ta)){
 
$this->createTable('{{%product}}', [
	'product_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'product_sn' => 'VARCHAR(50) NULL',
	'name' => 'VARCHAR(100) NULL COMMENT \'商品名称\'',
	'model_id' => 'INT(11) NULL COMMENT \'模型id\'',
	'cat_id' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'店铺分类id\'',
	'type_id' => 'INT(10) NULL DEFAULT \'1\' COMMENT \'类目id（product_type）\'',
	'brand_id' => 'INT(11) NULL COMMENT \'品牌id\'',
	'up_time' => 'INT(11) NULL COMMENT \'上架时间\'',
	'down_time' => 'INT(11) NULL COMMENT \'下架时间\'',
	'create_at' => 'INT(11) NULL COMMENT \'添加时间\'',
	'update_at' => 'INT(11) NULL COMMENT \'最后编辑时间\'',
	'image' => 'TEXT NULL COMMENT \'图片列表信息json对象\'',
	'unit' => 'VARCHAR(10) NULL COMMENT \'单位\'',
	'status' => 'TINYINT(1) NULL DEFAULT \'1\' COMMENT \'商品状态 0 已删除1正常 2下架 3申请上架4拒绝\'',
	'visit' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'浏览次数\'',
	'favorite' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'收藏次数\'',
	'sortnum' => 'SMALLINT(5) NULL DEFAULT \'0\' COMMENT \'排序\'',
	'comments' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'评论次数\'',
	'sale' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'销量\'',
	'shop_id' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'商家id\'',
	'max_price' => 'DECIMAL(10,2) NULL COMMENT \'最高价格\'',
	'min_price' => 'DECIMAL(10,2) NULL COMMENT \'最低价格\'',
	'stock' => 'INT(11) NULL COMMENT \'总库存\'',
	'stock_type' => 'TINYINT(3) NULL COMMENT \'库存增减方式1下单减库存2支付减库存\'',
	'hot' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'是否编辑为热销，0否，1是\'',
	'self_lift' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'支持自提0否1是\'',
	'express' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'支持快递0否1是\'',
	'city_wide' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'支持同城送0否1是\'',
	'content' => 'TEXT NULL COMMENT \'详情\'',
	'markdown' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否是markdown类型0否1是\'',
	'is_free' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否免邮0否1是\'',
	'shipping_id' => 'INT(11) NULL COMMENT \'运费模板id\'',
	'author' => 'VARCHAR(255) NULL COMMENT \'作品作者\'',
	'prom_type' => 'TINYINT(3) NULL COMMENT \'0默认1抢购2团购3优惠促销4预售5虚拟(5其实没用)6拼团\'',
	'prom_id' => 'INT(11) NULL',
	'PRIMARY KEY (`product_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('model_id','{{%product}}','model_id',0);
$this->createIndex('cat_id','{{%product}}','cat_id',0);
$this->createIndex('band_id','{{%product}}','brand_id',0);
$this->createIndex('shop_id','{{%product}}','shop_id',0);
$this->createIndex('shop_cat_id','{{%product}}','type_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_product_category', $ta)){
 
$this->createTable('{{%product_category}}', [
	'category_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'shop_id' => 'INT(11) NULL',
	'cat_name' => 'VARCHAR(50) NULL COMMENT \'店铺分类名称\'',
	'parent_id' => 'INT(11) NULL DEFAULT \'0\'',
	'sort' => 'INT(11) NULL DEFAULT \'0\'',
	'image' => 'VARCHAR(255) NULL',
	'status' => 'TINYINT(3) NULL DEFAULT \'1\' COMMENT \'0禁用1启用\'',
	'is_system' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否系统默认\'',
	'PRIMARY KEY (`category_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_product_comment', $ta)){
 
$this->createTable('{{%product_comment}}', [
	'comment_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'member_id' => 'INT(11) NULL COMMENT \'发表的用户id\'',
	'goods_id' => 'INT(11) NOT NULL COMMENT \'商品id\'',
	'content' => 'TEXT NULL COMMENT \'评价内容\'',
	'pid' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'父级评价id\'',
	'reply_member_id' => 'INT(11) NULL COMMENT \'被回复用户id\'',
	'status' => 'TINYINT(4) NULL DEFAULT \'1\' COMMENT \'0隐藏1显示\'',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'image' => 'TEXT NULL COMMENT \'图片json形式，类似product的imagejson\'',
	'order_sku_id' => 'INT(11) NULL',
	'order_no' => 'VARCHAR(20) NULL',
	'total_stars' => 'INT(11) NULL DEFAULT \'5\' COMMENT \'总评\'',
	'des_stars' => 'INT(11) NULL DEFAULT \'5\' COMMENT \'描述相符\'',
	'delivery_stars' => 'INT(11) NULL DEFAULT \'5\' COMMENT \'物流评分\'',
	'service_stars' => 'INT(11) NULL DEFAULT \'5\' COMMENT \'服务评分\'',
	'appraise' => 'TINYINT(3) NULL DEFAULT \'3\' COMMENT \'1差评2中评3好评\'',
	'reply_status' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'0未回复1已回复\'',
	'user_id' => 'INT(11) NULL COMMENT \'管理员id，回复的管理员id\'',
	'is_nickname' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'匿名显示0否1是\'',
	'PRIMARY KEY (`comment_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_product_model_attr', $ta)){
 
$this->createTable('{{%product_model_attr}}', [
	'product_model_attr_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'model_id' => 'INT(11) NULL',
	'model_attr_id' => 'INT(11) NULL',
	'product_id' => 'INT(11) NULL',
	'attr_value' => 'VARCHAR(45) NULL',
	'model_attr_value_id' => 'INT(11) NULL',
	'PRIMARY KEY (`product_model_attr_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_product_type', $ta)){
 
$this->createTable('{{%product_type}}', [
	'type_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'type_name' => 'VARCHAR(50) NULL COMMENT \'类型名称\'',
	'parent_id' => 'INT(11) NULL DEFAULT \'0\' COMMENT \'父级id\'',
	'shop_id' => 'INT(11) NULL COMMENT \'店铺id
\'',
	'remark' => 'VARCHAR(200) NULL COMMENT \'备注\'',
	'sort' => 'INT(11) NULL DEFAULT \'0\'',
	'is_system' => 'INT(11) NULL COMMENT \'是否系统内置1：是0否\'',
	'keyword' => 'VARCHAR(255) NULL COMMENT \'SEO关键词\'',
	'discription' => 'VARCHAR(255) NULL COMMENT \'描述\'',
	'seo_content' => 'VARCHAR(255) NULL COMMENT \'SEO内容\'',
	'image' => 'VARCHAR(255) NULL',
	'PRIMARY KEY (`type_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_promotion', $ta)){
 
$this->createTable('{{%promotion}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(100) NOT NULL COMMENT \'名称\'',
	'use_price' => 'DECIMAL(10,2) UNSIGNED NOT NULL DEFAULT \'0.00\' COMMENT \'起用金额单位分\'',
	'user_group' => 'VARCHAR(255) NULL COMMENT \'用户组\'',
	'type' => 'TINYINT(2) NOT NULL DEFAULT \'0\' COMMENT \'规则类型\'',
	'type_value' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'赠送值或者优惠券活动id\'',
	'start_time' => 'INT(11) NOT NULL COMMENT \'开始时间\'',
	'end_time' => 'INT(11) NOT NULL COMMENT \'结束时间\'',
	'desc' => 'VARCHAR(255) NULL COMMENT \'简介\'',
	'shop_id' => 'INT(11) NOT NULL COMMENT \'店铺id\'',
	'status' => 'TINYINT(1) NOT NULL DEFAULT \'0\' COMMENT \'0为正常1为禁用2为删除\'',
	'addtime' => 'INT(11) NOT NULL COMMENT \'添加时间\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('start_time','{{%promotion}}','start_time',0);
$this->createIndex('end_time','{{%promotion}}','end_time',0);
$this->createIndex('shop_id','{{%promotion}}','shop_id',0);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_region', $ta)){
 
$this->createTable('{{%region}}', [
	'id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT \'表id\'',
	'name' => 'VARCHAR(32) NULL COMMENT \'地区名称\'',
	'level' => 'TINYINT(4) NULL DEFAULT \'0\' COMMENT \'地区等级 分省市县区\'',
	'parent_id' => 'INT(10) NULL COMMENT \'父id\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_reward', $ta)){
 
$this->createTable('{{%reward}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'article_id' => 'INT(11) NOT NULL',
	'user_id' => 'INT(11) NOT NULL',
	'money' => 'INT(11) NOT NULL',
	'comment' => 'VARCHAR(50) NULL COMMENT \'留言\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_share_info', $ta)){
 
$this->createTable('{{%share_info}}', [
	'mid' => 'INT(11) NOT NULL',
	'share_mid' => 'INT(11) NULL',
	'product_id' => 'INT(11) NOT NULL',
	'created_at' => 'INT(11) NULL',
	'updated_at' => 'INT(11) NULL',
	'PRIMARY KEY (`mid`,`product_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping', $ta)){
 
$this->createTable('{{%shipping}}', [
	'shipping_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NULL COMMENT \'模板名称\'',
	'desc' => 'VARCHAR(100) NULL COMMENT \'模板描述\'',
	'type' => 'TINYINT(1) NULL DEFAULT \'1\' COMMENT \'计费方式 0 按重量计费 1 按件计费\'',
	'shop_id' => 'INT(11) NULL',
	'sort' => 'INT(11) NULL',
	'status' => 'TINYINT(1) NULL COMMENT \'该配送方式是否被禁用，1，可用；0，禁用\'',
	'free_condition' => 'TINYINT(1) NULL DEFAULT \'0\' COMMENT \'是否指定包邮条件：1是0否\'',
	'is_free' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否包邮：0自定义运费1卖家承担运费\'',
	'is_system' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否是系统默认0否1是\'',
	'PRIMARY KEY (`shipping_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping_company', $ta)){
 
$this->createTable('{{%shipping_company}}', [
	'shiping_company_id' => 'INT(11) UNSIGNED NOT NULL AUTO_INCREMENT',
	'company_name' => 'VARCHAR(255) NULL',
	'sort' => 'INT(11) NULL',
	'yj_shipping_companycol' => 'VARCHAR(45) NULL',
	'PRIMARY KEY (`shiping_company_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping_free', $ta)){
 
$this->createTable('{{%shipping_free}}', [
	'free_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'shipping_id' => 'INT(11) NULL',
	'free_type' => 'TINYINT(4) NULL COMMENT \'免邮条件类型：1，件数 2，金额，3，件数+金额\'',
	'free_amount' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'免邮金额\'',
	'free_count' => 'DECIMAL(10,2) NULL DEFAULT \'0.00\' COMMENT \'免邮件数\'',
	'delivery_type_id' => 'INT(11) NULL DEFAULT \'1\' COMMENT \'1,快递2，ems，3，顺丰4，平邮\'',
	'regions' => 'TEXT NULL COMMENT \'地区列表,分格\'',
	'regions_str' => 'VARCHAR(255) NULL COMMENT \'地区展示\'',
	'PRIMARY KEY (`free_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping_free_regions', $ta)){
 
$this->createTable('{{%shipping_free_regions}}', [
	'shipping_id' => 'INT(11) NOT NULL',
	'free_id' => 'INT(11) NOT NULL',
	'region_id' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`free_id`,`region_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping_specify_region_item', $ta)){
 
$this->createTable('{{%shipping_specify_region_item}}', [
	'item_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'start_num' => 'DECIMAL(10,2) NULL COMMENT \'收件\'',
	'start_price' => 'DECIMAL(10,2) NULL COMMENT \'首费\'',
	'add_num' => 'DECIMAL(10,2) NULL COMMENT \' 续件\'',
	'add_price' => 'DECIMAL(10,2) NULL COMMENT \'续费\'',
	'is_default' => 'TINYINT(3) NULL DEFAULT \'0\' COMMENT \'是否默认\'',
	'delivery_type_id' => 'TINYINT(3) NULL DEFAULT \'1\' COMMENT \'1,快递2，ems，3，顺丰4，平邮\'',
	'regions' => 'TEXT NULL COMMENT \'地区列表,分格\'',
	'regions_str' => 'VARCHAR(255) NULL COMMENT \'地区列表展示\'',
	'shipping_id' => 'INT(11) NULL',
	'can_merge' => 'TINYINT(3) NULL DEFAULT \'1\' COMMENT \'是否允许其他商品重量/件数合并统计运费（保留字段）\'',
	'PRIMARY KEY (`item_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shipping_specify_regions', $ta)){
 
$this->createTable('{{%shipping_specify_regions}}', [
	'shipping_id' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'item_id' => 'INT(11) NOT NULL',
	'region_id' => 'INT(11) NOT NULL',
	'PRIMARY KEY (`item_id`,`region_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_shop', $ta)){
 
$this->createTable('{{%shop}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(255) NULL',
	'member_id' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_sign', $ta)){
 
$this->createTable('{{%sign}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NOT NULL',
	'sign_at' => 'INT(10) NOT NULL COMMENT \'签到时间\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_sign_info', $ta)){
 
$this->createTable('{{%sign_info}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'user_id' => 'INT(11) NOT NULL',
	'last_sign_at' => 'INT(10) NOT NULL COMMENT \'上次签到时间\'',
	'times' => 'INT(11) NOT NULL COMMENT \'总签到次数\'',
	'continue_times' => 'INT(11) NOT NULL COMMENT \'连续签到次数\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_sku_item', $ta)){
 
$this->createTable('{{%sku_item}}', [
	'sku_id' => 'VARCHAR(100) NOT NULL',
	'attribute_id' => 'INT(11) NOT NULL',
	'value_id' => 'INT(11) NULL',
	'PRIMARY KEY (`sku_id`,`attribute_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_skus', $ta)){
 
$this->createTable('{{%skus}}', [
	'sku_id' => 'VARCHAR(100) NOT NULL COMMENT \'格式product_attributevalue_...atributevalue\'',
	'product_id' => 'INT(11) NULL',
	'weight' => 'DECIMAL(10,2) NULL COMMENT \'重量\'',
	'stock' => 'INT(10) NULL COMMENT \'库存\'',
	'market_price' => 'DECIMAL(10,2) NULL COMMENT \'市场价\'',
	'sale_price' => 'DECIMAL(10,2) NULL COMMENT \'售价\'',
	'sku_num' => 'VARCHAR(45) NULL COMMENT \'编码（条形码）\'',
	'image' => 'VARCHAR(255) NULL',
	'thumbImg' => 'VARCHAR(255) NULL',
	'sku_values' => 'VARCHAR(255) NULL COMMENT \'sku规格值字符串\'',
	'PRIMARY KEY (`sku_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_spider', $ta)){
 
$this->createTable('{{%spider}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(20) NOT NULL COMMENT \'标识\'',
	'title' => 'VARCHAR(100) NOT NULL COMMENT \'名称\'',
	'domain' => 'VARCHAR(255) NOT NULL COMMENT \'域名\'',
	'page_dom' => 'VARCHAR(255) NOT NULL COMMENT \'分页链接元素\'',
	'list_dom' => 'VARCHAR(255) NOT NULL COMMENT \'列表链接元素\'',
	'time_dom' => 'VARCHAR(255) NULL COMMENT \'内容页时间元素\'',
	'content_dom' => 'VARCHAR(255) NOT NULL COMMENT \'内容页内容元素\'',
	'title_dom' => 'VARCHAR(255) NOT NULL COMMENT \'内容页标题元素\'',
	'target_category' => 'VARCHAR(255) NOT NULL COMMENT \'目标分类\'',
	'target_category_url' => 'VARCHAR(255) NOT NULL COMMENT \'目标分类地址\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_suggest', $ta)){
 
$this->createTable('{{%suggest}}', [
	'title' => 'VARCHAR(128) NOT NULL',
	'content' => 'VARCHAR(1000) NOT NULL',
	'created_at' => 'INT(11) NOT NULL',
	'user_id' => 'INT(11) NOT NULL',
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_system_log', $ta)){
 
$this->createTable('{{%system_log}}', [
	'id' => 'BIGINT(20) NOT NULL AUTO_INCREMENT',
	'level' => 'INT(11) NULL',
	'category' => 'VARCHAR(255) NULL',
	'log_time' => 'DOUBLE NULL',
	'prefix' => 'TEXT NULL',
	'message' => 'TEXT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_tag', $ta)){
 
$this->createTable('{{%tag}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(100) NOT NULL',
	'article' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'有该标签的文章数\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_url_rule', $ta)){
 
$this->createTable('{{%url_rule}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'name' => 'VARCHAR(50) NULL',
	'pattern' => 'VARCHAR(255) NOT NULL',
	'host' => 'VARCHAR(255) NULL',
	'route' => 'VARCHAR(255) NOT NULL',
	'defaults' => 'VARCHAR(255) NULL',
	'suffix' => 'VARCHAR(255) NULL',
	'verb' => 'VARCHAR(255) NULL',
	'mode' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'encodeParams' => 'TINYINT(1) NOT NULL DEFAULT \'1\'',
	'status' => 'SMALLINT(1) NULL DEFAULT \'1\'',
	'sort' => 'SMALLINT(1) NULL DEFAULT \'1\' COMMENT \'排序\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_user', $ta)){
 
$this->createTable('{{%user}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'username' => 'VARCHAR(255) NOT NULL',
	'auth_key' => 'VARCHAR(32) NOT NULL',
	'password_hash' => 'VARCHAR(255) NOT NULL',
	'password_reset_token' => 'VARCHAR(255) NULL',
	'email' => 'VARCHAR(255) NOT NULL',
	'created_at' => 'INT(11) NOT NULL',
	'updated_at' => 'INT(11) NOT NULL',
	'login_at' => 'INT(11) NULL',
	'blocked_at' => 'INT(11) NULL',
	'confirmed_at' => 'INT(11) NULL',
	'access_token' => 'VARCHAR(50) NULL',
	'expired_at' => 'INT(11) NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('username','{{%user}}','username',1);
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_user_behavior_log', $ta)){
 
$this->createTable('{{%user_behavior_log}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'behavior_name' => 'VARCHAR(30) NULL COMMENT \'用户行为名\'',
	'user_id' => 'INT(11) NULL',
	'content' => 'TEXT NULL COMMENT \'行为日志\'',
	'created_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_user_profile', $ta)){
 
$this->createTable('{{%user_profile}}', [
	'user_id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'money' => 'INT(11) NOT NULL DEFAULT \'0\'',
	'avatar' => 'VARCHAR(255) NOT NULL',
	'signature' => 'VARCHAR(100) NOT NULL',
	'gender' => 'TINYINT(1) NOT NULL DEFAULT \'0\'',
	'qq' => 'VARCHAR(20) NULL',
	'phone' => 'VARCHAR(20) NULL',
	'province' => 'SMALLINT(4) NULL',
	'city' => 'SMALLINT(4) NULL',
	'area' => 'SMALLINT(4) NULL',
	'locale' => 'VARCHAR(32) NOT NULL DEFAULT \'zh-CN\'',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'PRIMARY KEY (`user_id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_vote', $ta)){
 
$this->createTable('{{%vote}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'entity' => 'VARCHAR(80) NOT NULL',
	'entity_id' => 'INT(11) NOT NULL',
	'user_id' => 'INT(11) NOT NULL',
	'created_at' => 'INT(10) NOT NULL',
	'updated_at' => 'INT(10) NOT NULL',
	'action' => 'VARCHAR(20) NOT NULL DEFAULT \'up\' COMMENT \'up or down\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
}
 
if(!\common\helpers\Util::deep_in_array('yj_vote_info', $ta)){
 
$this->createTable('{{%vote_info}}', [
	'id' => 'INT(11) NOT NULL AUTO_INCREMENT',
	'entity' => 'VARCHAR(80) NOT NULL',
	'entity_id' => 'INT(11) NOT NULL',
	'up' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'顶数\'',
	'down' => 'INT(11) NOT NULL DEFAULT \'0\' COMMENT \'踩数\'',
	'PRIMARY KEY (`id`)'
], "CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB");
 
 
$this->createIndex('entity','{{%vote_info}}','entity, entity_id',0);
 
}
 
 
/* Table yj_access_token */
if(!\common\helpers\Util::deep_in_array('yj_access_token', $ta)){
 
$this->batchInsert('{{%access_token}}',['id','uid','token','expire_in'],[]);}
 
/* Table yj_address */
if(!\common\helpers\Util::deep_in_array('yj_address', $ta)){
 
$this->batchInsert('{{%address}}',['id','uid','userName','postalCode','provinceName','cityName','countyName','detailInfo','nationalCode','telNumber','status','sort','is_default','is_pickup'],[]);}
 
/* Table yj_area */
if(!\common\helpers\Util::deep_in_array('yj_area', $ta)){
 
$this->batchInsert('{{%area}}',['area_id','title','slug','description','blocks'],[['1','首页头部','index-header','default','a:2:{i:0;s:1:"7";i:1;s:1:"9";}'],
['2','首页侧边栏','site-index-sidebar','首页侧边栏',''],
['3','文章列表侧边栏','article-index-sidebar','文章列表侧边栏',''],
]);}
 
/* Table yj_area_block */
if(!\common\helpers\Util::deep_in_array('yj_area_block', $ta)){
 
$this->batchInsert('{{%area_block}}',['block_id','title','type','widget','slug','config','template','cache','used'],[['7','公告','text','common\modules\area\widgets\TextWidget','gong-gao','','s:22:"<p>这里是公告</p>";','0','1'],
['9','区域测试','text','common\modules\area\widgets\TextWidget','qu-yu-ce-shi','','s:52:"<p>这里是侧边栏的区域中的一个区块</p>";','0','1'],
]);}
     }

    public function down()
    {
    
    			echo "m181121_065505_migration cannot be reverted.\n";
		    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
