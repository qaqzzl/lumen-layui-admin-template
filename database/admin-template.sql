create database if not exists `admin_template`;

use `admin_template`;


-- ****************************
-- 后台用户模块
-- ****************************
-- ----------------------------
-- Table structure for admin_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_menu`;
CREATE TABLE `admin_menu` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `parent_id` int(11) NOT NULL DEFAULT '0',
  `order` int(11) NOT NULL DEFAULT '0',
  `title` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `icon` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `uri` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT '',
  `permission` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '菜单';

-- ----------------------------
-- Records of admin_menu
-- ----------------------------
INSERT INTO `admin_menu` VALUES ('1', '0', '1', 'Index', 'layui-icon-home', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('2', '0', '2', 'Admin', 'layui-icon-set', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('3', '2', '3', 'Users', 'layui-icon-set', 'auth/user', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('4', '2', '4', 'Roles', 'layui-icon-set', 'auth/role', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('5', '2', '5', 'Permission', 'layui-icon-set', 'auth/permission', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('6', '2', '6', 'Menu', 'fa-bars', 'auth/menu', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('7', '2', '7', 'Operation log', 'fa-history', 'auth/logs', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('8', '0', '8', '目录深度测试', 'layui-icon-senior', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('9', '8', '9', '目录深度测试-1', 'layui-icon-senior', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('10', '8', '10', '目录深度测试-1', 'layui-icon-senior', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('11', '9', '11', '目录深度测试-2', 'layui-icon-senior', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('11', '8', '12', '目录深度测试-2', 'layui-icon-senior', '', '', 0, 0);
INSERT INTO `admin_menu` VALUES ('12', '11', '13', '目录深度测试-3', 'layui-icon-senior', '', '', 0, 0);

-- ----------------------------
-- Table structure for admin_operation_log
-- ----------------------------
DROP TABLE IF EXISTS `admin_operation_log`;
CREATE TABLE `admin_operation_log` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `method` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ip` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `input` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  KEY `admin_operation_log_user_id_index` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=311 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '请求日志';

-- ----------------------------
-- Table structure for admin_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_permissions`;
CREATE TABLE `admin_permissions` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `http_method` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `http_path` text COLLATE utf8mb4_unicode_ci,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_permissions_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '权限';

-- ----------------------------
-- Records of admin_permissions
-- ----------------------------
INSERT INTO `admin_permissions` VALUES ('1', 'All permission', '*', '', '*', 0, 0);

-- ----------------------------
-- Table structure for admin_role_menu
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_menu`;
CREATE TABLE `admin_role_menu` (
  `role_id` int(11) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  KEY `admin_role_menu_role_id_menu_id_index` (`role_id`,`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '角色菜单';

-- ----------------------------
-- Table structure for admin_role_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_role_permissions`;
CREATE TABLE `admin_role_permissions` (
  `role_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  KEY `admin_role_permissions_role_id_permission_id_index` (`role_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '角色权限';

-- ----------------------------
-- Records of admin_role_permissions
-- ----------------------------
INSERT INTO `admin_role_permissions` VALUES ('1', '1', 0, 0);


-- ----------------------------
-- Table structure for admin_role_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_roles`;
CREATE TABLE `admin_user_roles` (
  `role_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  KEY `admin_role_users_role_id_user_id_index` (`role_id`,`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '角色用户';

-- ----------------------------
-- Records of admin_role_users
-- ----------------------------
INSERT INTO `admin_user_roles` VALUES ('1', '1', 0, 0);


-- ----------------------------
-- Table structure for admin_roles
-- ----------------------------
DROP TABLE IF EXISTS `admin_roles`;
CREATE TABLE `admin_roles` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `slug` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_roles_name_unique` (`name`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '角色';

-- ----------------------------
-- Records of admin_roles
-- ----------------------------
INSERT INTO `admin_roles` VALUES ('1', 'Administrator', 'administrator', 0, 0);

-- ----------------------------
-- Table structure for admin_user_permissions
-- ----------------------------
DROP TABLE IF EXISTS `admin_user_permissions`;
CREATE TABLE `admin_user_permissions` (
  `user_id` int(11) NOT NULL,
  `permission_id` int(11) NOT NULL,
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  KEY `admin_user_permissions_user_id_permission_id_index` (`user_id`,`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '用户权限';

-- ----------------------------
-- Table structure for admin_users
-- ----------------------------
DROP TABLE IF EXISTS `admin_users`;
CREATE TABLE `admin_users` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `account` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(60) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nickname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `avatar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `access_token` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '',
  `created_at` int NOT NULL DEFAULT 0,
  `updated_at` int NOT NULL DEFAULT 0,
  PRIMARY KEY (`id`),
  UNIQUE KEY `admin_users_account_unique` (`account`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci comment '用户';

-- ----------------------------
-- Records of admin_users
-- ----------------------------
INSERT INTO `admin_users` VALUES ('1', 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'Administrator', '', '', 0, 0);

-- ****************************
-- 后台用户模块END
-- ****************************



-- ****************************
-- 系统公共模块
-- ****************************

-- 系统配置表
create table if not exists `system_config`(
    `key` varchar(20) primary key comment 'KEY',
    `value` char(255) not null default 'wz' comment '配置值',
    `name` varchar(255) not null default 0 comment '配置名称',
    `explain` varchar(255) not null default '' comment '说明, 注释',
    `created_at` int not null default 0 comment '添加时间',
    `updated_at` int not null default 0 comment '修改时间',
    `deleted_at` int not null default 0 comment '删除时间',
    UNIQUE KEY `name` (`name`)
)engine=innodb default charset=utf8mb4 comment '系统配置表';


-- ****************************
-- 系统公共模块END
-- ****************************



-- ****************************
-- 用户模块
-- ****************************

-- 用户会员
create table if not exists `user_member`(
    `member_id` int unsigned auto_increment primary key,
    `nickname` varchar(50) not null default '' comment '用户昵称',
    `gender` char(5) not null default 'wz' comment 'wz-未知, w-女, m-男, z-中性',
    `birthdate` int not null default 0 comment '出生日期',
    `avatar` varchar(255) not null default '' comment '头像',
    `signature` varchar(64) not null default '' comment '个性签名',
    `city` char(50) not null default '' comment '城市',
    `province` char(50) not null default '' comment '省份',
    `created_at` int not null default 0 comment '添加时间',
    `updated_at` int not null default 0 comment '修改时间',
    `deleted_at` int not null default 0 comment '删除时间',
    UNIQUE KEY `nickname` (`nickname`)
)engine=innodb default charset=utf8mb4 comment '用户会员';

-- 会员授权账号表
create table if not exists `user_auths`(
    `id` int unsigned auto_increment primary key,
    `member_id` int not null comment '会员ID',
    `identity_type` char(20) not null comment '类型,wechat_applet,qq,wb,phone,number,email',
    `identifier` varchar(64) not null default '' comment '微信,QQ,微博opendid | 手机号,邮箱,账号',
    `credential` varchar(64) not null default '' comment '密码凭证（站内的保存密码，站外的不保存或保存access_token）',
    KEY `member_id` (`member_id`),
    UNIQUE KEY `identity_type_identifier` (`identity_type`,`identifier`) USING BTREE
)engine=innodb default charset=utf8 comment '会员授权账号表';

-- 用户授权 token 表 ,这个表用redis比较好 , 也可以使用JWS
create table if not exists `user_auths_token`(
    `id` int unsigned auto_increment primary key,
    `member_id` int not null comment '会员ID',
    `token` varchar(255) not null default '' comment 'token',
    `client` char(20) not null comment 'app,web,wechat_applet',
    `last_time` int not null comment '上次刷新时间',
    `status` tinyint(1) not null default 0 comment '1-其他设备强制下线',
    `created_at` int not null default 0 comment '添加时间',
    UNIQUE KEY `token` (`token`)
)engine=innodb default charset=utf8 comment '用户授权 token 表';


-- ****************************
-- 用户模块END
-- ****************************



-- ****************************
-- 商城模块
-- ****************************

-- ----------------------------
-- Table structure for m_shop_goods
-- 商品表
-- ----------------------------
create table if not exists `shop_goods`(
    `goods_id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品id',
    `category_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类id',
    `category_path` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '分类路径',
    `goods_sn` varchar(60) NOT NULL DEFAULT '' COMMENT '商品编号',
    `goods_name` varchar(120) NOT NULL DEFAULT '' COMMENT '商品名称',
    `pv_count` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击数',
    `brand_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '品牌id',
    `store_count` smallint(5) unsigned NOT NULL DEFAULT '10' COMMENT '库存数量',
    `comment_count` smallint(5) DEFAULT '0' COMMENT '商品评论数',
    `weight` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '商品重量克为单位',
    `volume` double(10,4) unsigned NOT NULL DEFAULT '0.0000' COMMENT '商品体积。单位立方米',
    `market_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '市场价',
    `shop_price` decimal(10,2) unsigned NOT NULL DEFAULT '0.00' COMMENT '本店价',
    `cost_price` decimal(10,2) DEFAULT '0.00' COMMENT '商品成本价',
    `price_ladder` text COMMENT '价格阶梯',
    `keywords` varchar(255) NOT NULL DEFAULT '' COMMENT '商品关键词',
    `goods_remark` varchar(255) NOT NULL DEFAULT '' COMMENT '商品简单描述',
    `goods_content` text COMMENT '商品详细描述',
    `mobile_content` text COMMENT '手机端商品详情',
    `original_img` varchar(255) NOT NULL DEFAULT '' COMMENT '商品上传原始图',
    `is_virtual` tinyint(3) unsigned NOT NULL DEFAULT '0' COMMENT '是否为虚拟商品 0为实物 1电子卡券 2预约 3外卖',
    `virtual_indate` int(11) DEFAULT '0' COMMENT '虚拟商品有效期',
    `virtual_limit` smallint(6) DEFAULT '0' COMMENT '虚拟商品购买上限',
    `virtual_sales_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟销售量',
    `virtual_collect_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '虚拟收藏量',
    `virtual_comment_count` int(11) unsigned DEFAULT '0' COMMENT '虚拟商品评论数',
    `collect_sum` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '收藏量',
    `is_on_sale` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否上架',
    `is_free_shipping` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否包邮0否1是',
    `sort` smallint(4) unsigned NOT NULL DEFAULT '50' COMMENT '商品排序',
    `is_recommend` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否推荐',
    `is_new` tinyint(1) unsigned NOT NULL DEFAULT '0' COMMENT '是否新品',
    `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否热卖',
    `give_integral` mediumint(8) unsigned NOT NULL DEFAULT '0' COMMENT '购买商品赠送积分',
    `exchange_integral` int(10) NOT NULL DEFAULT '0' COMMENT '积分兑换：0不参与积分兑换，积分和现金的兑换比例见后台配置',
    `suppliers_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '供货商ID',
    `sales_sum` int(11) DEFAULT '0' COMMENT '商品销量',
    `prom_type` tinyint(1) DEFAULT '0' COMMENT '0默认1抢购2团购3优惠促销4预售5虚拟(5其实没用)6拼团7搭配购8砍价',
    `prom_id` int(11) NOT NULL DEFAULT '0' COMMENT '优惠活动id',
    `commission` decimal(10,2) DEFAULT '0.00' COMMENT '佣金用于分销分成金额',
    `spu` varchar(128) DEFAULT '' COMMENT 'SPU',
    `sku` varchar(128) DEFAULT '' COMMENT 'SKU',
    `template_id` int(11) unsigned DEFAULT '0' COMMENT '运费模板ID',
    `video` varchar(255) DEFAULT '' COMMENT '视频',
    `label_id` varchar(255) DEFAULT NULL COMMENT '商品标签',
    `audit` tinyint(1) DEFAULT '0' COMMENT '0审核通过，1待审核，2未通过',
    `created_at` int not null default 0 comment '创建时间',
	`updated_at` int not null default 0 comment '更新时间',
    `deleted_at` int not null default 0 comment '删除时间',
    PRIMARY KEY (`goods_id`),
    KEY `goods_sn` (`goods_sn`),
    KEY `category_id` (`category_id`),
    KEY `category_path` (`category_path`),
    KEY `brand_id` (`brand_id`),
    KEY `store_count` (`store_count`),
    KEY `goods_weight` (`weight`),
    KEY `sort_order` (`sort`)
)engine=innodb default charset=utf8mb4;

-- ----------------------------
-- 商品分类
-- ----------------------------
CREATE TABLE `shop_goods_category` (
  `id` smallint(5) unsigned NOT NULL AUTO_INCREMENT COMMENT '商品分类id',
  `name` varchar(90) NOT NULL DEFAULT '' COMMENT '商品分类名称',
  `mobile_name` varchar(64) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '手机端显示的商品分类名',
  `parent_id` smallint(5) unsigned NOT NULL DEFAULT '0' COMMENT '父id',
  `parent_id_path` varchar(128) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '家族图谱',
  `level` tinyint(1) DEFAULT '0' COMMENT '等级',
  `sort_order` tinyint(1) unsigned NOT NULL DEFAULT '50' COMMENT '顺序排序',
  `is_show` tinyint(1) unsigned NOT NULL DEFAULT '1' COMMENT '是否显示',
  `image` varchar(512) CHARACTER SET utf8 COLLATE utf8_bin DEFAULT '' COMMENT '分类图片',
  `is_hot` tinyint(1) DEFAULT '0' COMMENT '是否推荐为热门分类',
  `cat_group` tinyint(1) DEFAULT '0' COMMENT '分类分组默认0',
  PRIMARY KEY (`id`),
  KEY `parent_id` (`parent_id`)
) ENGINE=MyISAM AUTO_INCREMENT=587 DEFAULT CHARSET=utf8;

-- ----------------------------
-- 批发商充值列表
-- ----------------------------
create table if not exists `shop_wholesaler_recharge`(
	`recharge_id` int unsigned auto_increment primary key,
	`price` decimal(10,2) not null default 0.00 comment '价格',
	`privileges` text NOT NULL COMMENT '充值特权',
    `created_at` int not null default 0 comment '创建时间',
	`updated_at` int not null default 0 comment '更新时间'
)engine=innodb default charset=utf8mb4;

-- ----------------------------
-- 批发商充值订单
-- ----------------------------

-- ----------------------------
-- 批发商分销关系
-- ----------------------------
create table if not exists `shop_wholesaler_distribution`(
	`distributio_id` int unsigned auto_increment primary key,
	`user_id` int not null comment '用户ID',
	`parent_user_id` int not null comment '上级用户ID',
	`path` varchar(255) not null default '' comment '父级路径',
	`created_at` int not null default 0 comment '创建时间',
	UNIQUE KEY `user_id` (`user_id`),
	KEY `parent_user_id` (`parent_user_id`),
	KEY `path` (`path`)
)engine=innodb default charset=utf8;


-- ----------------------------
-- 批发商收货地址
-- ----------------------------


-- ****************************
-- 商城模块END
-- ****************************





