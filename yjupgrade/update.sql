ALTER TABLE `yjshop`.`yj_comment`
  CHANGE COLUMN `user_id` `member_id` int(11) NOT NULL DEFAULT 0,
  CHANGE COLUMN `user_ip` `user_ip` varchar(20) COLLATE utf8_general_ci NULL DEFAULT '' COMMENT '用户ip' AFTER `member_id`,
  CHANGE COLUMN `entity` `entity` varchar(80) COLLATE utf8_general_ci NOT NULL,
  CHANGE COLUMN `content` `content` text COLLATE utf8_general_ci NOT NULL COMMENT '内容',
  CHANGE COLUMN `reply_uid` `reply_member_id` int(11) NULL DEFAULT 0 COMMENT '被回复的用户id，即回复的贴主id',
  ADD COLUMN `user_id` int(11) NULL DEFAULT NULL COMMENT '后台管理员id',
 COLLATE utf8_general_ci;

ALTER TABLE `yjshop`.`yj_product_comment`
  CHANGE COLUMN `service_stars` `service_stars` int(11) NULL DEFAULT 5 COMMENT '服务评分';
ALTER TABLE `yjshop`.`yj_product_comment`
  CHANGE COLUMN `delivery_stars` `delivery_stars` int(11) NULL DEFAULT 5 COMMENT '物流评分';
ALTER TABLE `yjshop`.`yj_product_comment`
  CHANGE COLUMN `des_stars` `des_stars` int(11) NULL DEFAULT 5 COMMENT '描述相符';
ALTER TABLE `yjshop`.`yj_product_comment`
  CHANGE COLUMN `total_stars` `total_stars` int(11) NULL DEFAULT 5 COMMENT '总评';

ALTER TABLE `yjshop`.`yj_product`
  ADD COLUMN `prom_type` tinyint(3) NULL DEFAULT NULL COMMENT '0默认1抢购2团购3优惠促销4预售5虚拟(5其实没用)6拼团' AFTER `author`,
  ADD COLUMN `prom_id` int(11) NULL DEFAULT NULL;

  ALTER TABLE `yjshop`.`yj_flash_sale`
  ADD COLUMN `version` bigint(20) NULL DEFAULT 0 COMMENT '版本号';

ALTER TABLE `yj_order`
DROP COLUMN `promotion_price`,
CHANGE COLUMN `sku_price` `sku_price` DECIMAL(10,2) NOT NULL COMMENT '商品市场总价单位' ,
CHANGE COLUMN `sku_price_real` `sku_price_real` DECIMAL(10,2) NOT NULL COMMENT '商品销售价格单位' ,
CHANGE COLUMN `delivery_price` `delivery_price` DECIMAL(10,2) NOT NULL COMMENT '物流原价单位' ,
CHANGE COLUMN `delivery_price_real` `delivery_price_real` DECIMAL(10,2) NOT NULL COMMENT '物流支付价格单位' ,
CHANGE COLUMN `discount_price` `discount_price` DECIMAL(10,2) NOT NULL COMMENT '改价金额单位' ,
CHANGE COLUMN `order_price` `order_price` DECIMAL(10,2) NOT NULL COMMENT '订单总金额单位' ;
ALTER TABLE `yj_order`
ADD COLUMN `pay_amount` DECIMAL(10,2) NOT NULL COMMENT '应付总价，订单总价+邮费价格+改价金额+活动减价+积分抵扣' AFTER `order_price`,
ADD COLUMN `integral` INT NULL COMMENT '使用积分' AFTER `paytime`,
ADD COLUMN `integral_money` DECIMAL(10,2) NULL COMMENT '积分抵扣金额' AFTER `integral`,
ADD COLUMN `prom_id` INT NULL COMMENT '活动id' AFTER `update_time`,
ADD COLUMN `prom_type` TINYINT(2) NULL COMMENT '订单类型：0普通订单4预售订单5虚拟订单6拼团订单' AFTER `prom_id`,
ADD COLUMN `order_prom_id` INT NULL COMMENT '订单活动（如满减活动）' AFTER `prom_type`,
ADD COLUMN `order_prom_money` DECIMAL(10,2) NULL COMMENT '订单活动扣除金额' AFTER `order_prom_id`,
ADD COLUMN `invoice_title` VARCHAR(145) NULL COMMENT '发票抬头' AFTER `is_del`,
ADD COLUMN `taxpayer` VARCHAR(45) NULL COMMENT '税务识别号' AFTER `invoice_title`,
ADD COLUMN `is_distribut` TINYINT(1) NULL COMMENT '是否已分成' AFTER `taxpayer`,
ADD COLUMN `paid_money` DECIMAL(10,2) NULL COMMENT '订金' AFTER `is_distribut`;
ALTER TABLE `yj_order`
CHANGE COLUMN `coupons_id` `coupons_id` INT(11) NULL COMMENT '优惠券id',
CHANGE COLUMN `coupons_price` `coupons_price` DECIMAL(10,2)  NULL COMMENT '优惠券金额' AFTER `coupons_id`,
CHANGE COLUMN `order_prom_id` `order_prom_id` INT(11) NULL DEFAULT NULL COMMENT '订单活动（如满减活动）' AFTER `coupons_price`,
CHANGE COLUMN `order_prom_money` `order_prom_money` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '订单活动扣除金额' AFTER `order_prom_id`,
CHANGE COLUMN `integral` `integral` INT(11) NULL DEFAULT NULL COMMENT '使用积分' AFTER `order_prom_money`,
CHANGE COLUMN `integral_money` `integral_money` DECIMAL(10,2) NULL DEFAULT NULL COMMENT '积分抵扣金额' AFTER `integral`,
/*CHANGE COLUMN `prom_id` `prom_id` INT(11) NULL DEFAULT NULL COMMENT '活动id' AFTER `taxpayer`,
CHANGE COLUMN `prom_type` `prom_type` TINYINT(2) NULL DEFAULT NULL COMMENT '订单类型：0普通订单4预售订单5虚拟订单6拼团订单' AFTER `prom_id`,*/
CHANGE COLUMN `pay_amount` `pay_amount` DECIMAL(10,2) NOT NULL COMMENT '应付总价，订单总价order_price+邮费价格deliver_price+改价金额+活动减价+积分抵扣-用户使用余额' ,
ADD COLUMN `payment_name` VARCHAR(45)  NULL COMMENT '支付方式名称' AFTER `payment_id`,
ADD COLUMN `delivery_name` VARCHAR(45) NULL COMMENT '快递名称'  AFTER `delivery_id`,
ADD COLUMN `user_money` DECIMAL(10,2) NULL COMMENT '用户使用余额' AFTER `integral_money`,
ADD COLUMN `parent_sn` VARCHAR(80) NULL COMMENT '父单单号' AFTER `update_time`;

ALTER TABLE `yj_member`
CHANGE COLUMN `oauth_id` `oauth_id` VARCHAR(128) NOT NULL COMMENT '第三方平台授权id' ,
ADD COLUMN `mobile_validated` TINYINT(2) NULL DEFAULT 0 COMMENT '手机是否验证' AFTER `mobile`,
ADD COLUMN `email_validated` INT NULL DEFAULT 0 COMMENT '邮箱验证' AFTER `email`,
ADD COLUMN `pay_pwd` VARCHAR(250) NULL COMMENT '支付密码' AFTER `flag`,
ADD COLUMN `user_money` DECIMAL(10,2) NULL COMMENT '用户余额' AFTER `pay_pwd`,
ADD COLUMN `frozen_money` DECIMAL(10,2) NULL COMMENT '冻结资金' AFTER `user_money`,
ADD COLUMN `distribut_money` DECIMAL(10,2) NULL COMMENT '累积分佣金额' AFTER `frozen_money`,
ADD COLUMN `underling_number` VARCHAR(45) NULL COMMENT '分销下线人数' AFTER `distribut_money`,
ADD COLUMN `total_amount` DECIMAL(10,2) NULL COMMENT '累积消费金额' AFTER `underling_number`,
ADD COLUMN `is_distribut` INT NULL COMMENT '是否是分销商' AFTER `total_amount`,
ADD COLUMN `distribut_level` INT NULL COMMENT '分销等级' AFTER `is_distribut`,
ADD COLUMN `first_leader` INT NULL COMMENT '一级' AFTER `distribut_level`,
ADD COLUMN `second_leader` INT NULL COMMENT '二级' AFTER `first_leader`,
ADD COLUMN `third_leader` INT NULL COMMENT '三级' AFTER `second_leader`,
ADD COLUMN `message_mask` VARCHAR(100) NULL COMMENT '消息掩码' AFTER `third_leader`,
ADD COLUMN `push_id` VARCHAR(45) NULL COMMENT '推送id ' AFTER `message_mask`,
ADD COLUMN `is_vip` INT NULL COMMENT '是否是vip' AFTER `push_id`;

ALTER TABLE `yj_order_sku`
ADD COLUMN `prom_id` INT NULL AFTER `shop_id`,
ADD COLUMN `prom_type` INT NULL AFTER `prom_id`,
CHANGE COLUMN `shop_id` `shop_id` INT(11) NULL DEFAULT '1' COMMENT '店铺id' ;

alter table yj_product ADD COLUMN `prom_id` INT NULL AFTER `shop_id`,
ADD COLUMN `prom_type` INT NULL AFTER `prom_id`;




CREATE TABLE IF NOT EXISTS `yj_member_distribution` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) DEFAULT NULL COMMENT '分销会员id',
  `user_name` varchar(50) DEFAULT NULL COMMENT '会员昵称',
  `goods_id` int(11) DEFAULT NULL COMMENT '商品id',
  `goods_name` varchar(150) DEFAULT NULL COMMENT '商品名称',
  `cat_id` smallint(6) DEFAULT '0' COMMENT '商品分类ID',
  `brand_id` mediumint(8) DEFAULT '0' COMMENT '商品品牌',
  `share_num` int(10) DEFAULT '0' COMMENT '分享次数',
  `sales_num` int(11) DEFAULT '0' COMMENT '分销销量',
  `addtime` int(11) DEFAULT NULL COMMENT '加入个人分销库时间',
  PRIMARY KEY (`id`),
  KEY `goods_id` (`goods_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='用户选择分销商品表' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `yj_member_extend` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) unsigned DEFAULT '0',
  `invoice_title` varchar(200) CHARACTER SET utf8 DEFAULT NULL COMMENT '发票抬头',
  `taxpayer` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '纳税人识别号',
  `invoice_desc` varchar(50) CHARACTER SET utf8 DEFAULT NULL COMMENT '不开发票/明细',
  `realname` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '真实姓名',
  `idcard` varchar(100) CHARACTER SET utf8 DEFAULT NULL COMMENT '身份证号',
  PRIMARY KEY (`id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8  AUTO_INCREMENT=1 ;

drop table yj_order_log;
CREATE TABLE IF NOT EXISTS `yj_order_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `order_id` int(11) unsigned NOT NULL COMMENT '订单id',
  `action_user` int(11) DEFAULT NULL COMMENT '操作人',
  `order_status` int(11) NOT NULL DEFAULT '0' COMMENT '订单状态 ',
  `shipping_status` int(11) NOT NULL DEFAULT '0' COMMENT '配送状态',
  `pay_status` int(11) NOT NULL DEFAULT '0' COMMENT '支付状态 ',
  `action_note` varchar(255) NOT NULL COMMENT '操作备注',
  `create_time` int(11) DEFAULT NULL COMMENT '记录时间',
  `status_desc` varchar(45) DEFAULT NULL COMMENT '状态描述',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`) USING BTREE
) ENGINE=MyISAM DEFAULT CHARSET=utf8 COMMENT='订单日志表' AUTO_INCREMENT=1 ;

CREATE TABLE IF NOT EXISTS `yj_delivery_doc` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '发货单ID',
  `order_id` int(11) unsigned NOT NULL COMMENT '订单ID',
  `order_sn` varchar(64) NOT NULL DEFAULT '' COMMENT '订单编号',
  `user_id` int(11) unsigned NOT NULL COMMENT '用户ID',
  `admin_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '管理员ID',
  `consignee` varchar(64) NOT NULL DEFAULT '' COMMENT '收货人',
  `zipcode` varchar(6) DEFAULT NULL COMMENT '邮编',
  `mobile` varchar(20) NOT NULL DEFAULT '' COMMENT '联系手机',
  `country` int(11) unsigned NOT NULL COMMENT '国ID',
  `province` int(11) unsigned NOT NULL COMMENT '省ID',
  `city` int(11) unsigned NOT NULL COMMENT '市ID',
  `district` int(11) unsigned NOT NULL COMMENT '区ID',
  `address` varchar(255) NOT NULL DEFAULT '' COMMENT '地址',
  `shipping_code` varchar(32) DEFAULT NULL COMMENT '物流code',
  `shipping_name` varchar(64) DEFAULT NULL COMMENT '快递名称',
  `shipping_price` decimal(10,2) DEFAULT '0.00' COMMENT '运费',
  `invoice_no` varchar(255) NOT NULL DEFAULT '' COMMENT '物流单号',
  `tel` varchar(64) DEFAULT NULL COMMENT '座机电话',
  `note` text COMMENT '管理员添加的备注信息',
  `best_time` int(11) DEFAULT NULL COMMENT '友好收货时间',
  `create_time` int(11) NOT NULL COMMENT '创建时间',
  `is_del` tinyint(1) DEFAULT '0' COMMENT '是否删除',
  `send_type` tinyint(1) DEFAULT '0' COMMENT '发货方式0自填快递1在线预约2电子面单3无需物流',
  PRIMARY KEY (`id`),
  KEY `order_id` (`order_id`),
  KEY `user_id` (`user_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COMMENT='发货单' AUTO_INCREMENT=1 ;


CREATE TABLE IF NOT EXISTS `yj_flash_sale` (
  `id` bigint(10) NOT NULL AUTO_INCREMENT,
  `title` varchar(200) NOT NULL DEFAULT '' COMMENT '活动标题',
  `goods_id` int(10) NOT NULL COMMENT '参团商品ID',
  `sku_id` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL COMMENT '活动价格',
  `goods_num` int(10) DEFAULT NULL COMMENT '商品参加活动数',
  `buy_limit` int(11) DEFAULT '0' COMMENT '每人限购数',
  `buy_num` int(11) DEFAULT '0' COMMENT '已购买人数',
  `order_num` int(10) DEFAULT '0' COMMENT '已下单数',
  `description` text COMMENT '活动描述',
  `start_time` int(11) DEFAULT NULL COMMENT '开始时间',
  `end_time` int(11) DEFAULT NULL COMMENT '结束时间',
  `status` tinyint(1) DEFAULT '1' COMMENT '状态：0禁用1启用',
  `goods_name` varchar(255) DEFAULT NULL COMMENT '商品名称',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;






