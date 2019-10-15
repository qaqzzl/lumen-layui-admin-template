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
-- 用户模块
-- ****************************




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
    `goods_id` int unsigned auto_increment primary key,
	`merchant_id` int(11) NOT NULL COMMENT '商户id',
	`sku_code` varchar(64) NOT NULL DEFAULT '' COMMENT '商品sku编码',
	`spu_code` varchar(64) NOT NULL DEFAULT '' COMMENT '商品spu编码',
    `goods_name` varchar(100) not null comment '商品名称',
	`goods_img` varchar(255) not null comment '商品图片',
	`carousel_imgs` text NOT NULL COMMENT '轮播图',
	`goods_introduction` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品简介',
	`goods_doc_detail` longtext CHARACTER SET utf8mb4 not null default '' COMMENT '图文详情',
	`voide_url` varchar(255) NOT NULL DEFAULT '' COMMENT '视频链接',
	
	`class_ids` varchar(255) NOT NULL DEFAULT '' COMMENT '分类ID, 逗号分隔',
	
	`goods_origin_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '原价',
	`goods_pc_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT 'pc价格',
	`cost_price` decimal(10,2) NOT NULL DEFAULT '0.00' COMMENT '产品成本价',
	
	`is_review` tinyint(1) NOT NULL DEFAULT 0 COMMENT '0-未审核, 1-已审核',
	
	`sales_amount` decimal(10,2) NOT NULL DEFAULT '0.0' COMMENT '销售佣金',
	`important` int(10) NOT NULL DEFAULT 0 COMMENT '权重值',
	
	`goods_tag_keys` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '商品标签KEY 逗号分隔',
	`goods_tag_values` text COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT '' COMMENT '标签VALUE 逗号分隔',
    `created_at` int not null default 0 comment '创建时间',
	`updated_at` int not null default 0 comment '更新时间',
    `deleted_at` int not null default 0 comment '删除时间'
)engine=innodb default charset=utf8mb4;

-- ----------------------------
-- 厂家(出货商)
-- ----------------------------


-- ----------------------------
-- 批发商(进货商)
-- ----------------------------
create table if not exists `shop_wholesaler`(
	`wholesaler_id` int unsigned auto_increment primary key,
	`user_id` int not null comment '用户ID',
	`` decimal(10,2) not null default 0.00 comment '充值余额',
	`` decimal(10,2) not null default 0.00 comment '分销返利金额',
	`` decimal(10,2) not null default 0.00 comment '购买限制金额',
	`` decimal(10,2) not null default 0.00 comment '累计充值金额',
    `created_at` int not null default 0 comment '创建时间',
	`updated_at` int not null default 0 comment '更新时间'
)engine=innodb default charset=utf8mb4;

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





