<?php

use yii\db\Migration;

/**
 * Handles the creation for table `{{%config}}`.
 */
class m160728_025305_create_config_table extends Migration
{
    /**
     * @inheritdoc
     */
    public function up()
    {
        $tableOptions = null;
        if ($this->db->driverName === 'mysql') {
            $tableOptions = 'CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE=InnoDB';
        }
        // config
        $this->createTable('{{%config}}', [
            'id' => $this->primaryKey(),
            'name' => $this->string(50)->notNull()->comment('配置名'),
            'value' => $this->text()->comment('配置值'),
            'extra' => $this->text(),
            'description' => $this->string(255)->comment('配置描述'),
            'type' => $this->string(30)->defaultValue('text')->comment('配置类型'),
            'created_at' => $this->integer(10)->notNull(),
            'updated_at' => $this->integer(10)->notNull(),
            'group' => $this->string(30)->defaultValue('system')->comment('配置分组')
        ], $tableOptions);
        $this->execute(<<<SQL
INSERT INTO {{%config}} VALUES (1,'config_type_list','text=>字符\r\narray=>数组\r\npassword=>密码\r\nimage=>图片\r\ntextarea=>多行字符\r\nselect=>下拉框\r\nradio=>单选框\r\ncheckbox=>多选框\r\neditor=>富文本编辑器','','配置类型列表','array',0,1461937892,'system'),(2,'config_group','site=>网站\r\nsystem=>系统','','配置分组','array',1468405444,1468421137,'system'),(3,'site_name','几何线','','网站名称','text',0,1581558784,'site'),(4,'site_icp','','','域名备案号','text',0,1556007673,'site'),(5,'site_logo','','','网站LOGO','image',0,1581754247,'site'),(6,'seo_site_description','一家有温度的生鲜社区电商','','网站描述','text',0,1573554940,'site'),(7,'seo_site_keywords','几何线','','网站关键词','text',0,1573554940,'site'),(8,'theme_name','advance','','主题名','text',0,1531993540,'site'),(9,'backend_skin','skin-black','skin-black=>skin-black\r\nskin-black-light=>skin-black-light\r\nskin-blue=>skin-blue\r\nskin-blue-light=>skin-blue-light\r\nskin-green=>skin-green\r\nskin-green-light=>skin-green-light\r\nskin-purple=>skin-purple\r\nskin-pruple-light=>skin-purple-light\r\nskin-red=>skin-red\r\nskin-red-light=>skin-red-light\r\nskin-yellow=>skin-yellow\r\nskin-yellow-light=>skin-yellow-light','后台皮肤','select',1461931367,1461937892,'system'),(10,'editor_type_list','ueditor=>ueditor\r\nmarkdown=>markdown\r\nredactor=>redactor','','支持的编辑器类型','array',0,1468406411,'system'),(11,'article_editor_type','ueditor','editor_type_list','文章编辑器','select',0,1468406411,'system'),(12,'page_editor_type','ueditor','editor_type_list','单页编辑器','select',0,1468406411,'system'),(13,'version','2.00','','版本号','text',1468405444,1537199580,'system'),(14,'site_credits_exchange','200','','积分兑换比例(1￥=?积分)','text',1468405444,1540864002,'site'),(17,'pay_time','16','','下单多少小时后自动取消订单','text',1556525275,1583714548,'site'),(18,'distribute_open','1','1=>开启\r\n0=>关闭','是否开启分销','select',1468405444,1468405444,'site'),(19,'sms_base','{\"accessKeyId\":\"\",\"accessKeySecret\":\"\",\"mobile\":\"\"}',NULL,'短信基础配置','text',1468405444,1573554978,'sms'),(20,'sms_commom_tmp','{\"commonTemplateCode\":\"\",\"signName\":\"几何线\"}',NULL,'短信公共模板','text',1468405444,1563156051,'sms'),(21,'sms_order_tmp','{\"commonTemplateCode\":\"SMS_169505\",\"signName\":\"几何线\"}',NULL,' 短下单配置','text',1468405444,1563158648,'sms'),(22,'sms_delivery_tmp',NULL,NULL,' 短信发货配置','text',1468405444,1468405444,'sms'),(23,'wx_config','{\"appid\":\"\",\"appSecret\":\"\",\"token\":\"jihexian\",\"encodingAesKey\":\"\"}',NULL,'微信设置','text',1468405444,1562118153,'wx'),(24,'xcx_config','{\"appid\":\"\",\"appSecret\":\"4a\"}',NULL,'小程序设计','text',1468405444,1560131668,'xcx'),(25,'distribut','50%-30%-20%','','分销比例(一级百分比-二级百分比)(格式：50%-50%)','text',1556525275,1573554940,'site'),(26,'qrcode','','','微信二维码','image',1576481964,1576481987,'site');
SQL
);
    }

    /**
     * @inheritdoc
     */
    public function down()
    {
        $this->dropTable('{{%config}}');
    }
}
