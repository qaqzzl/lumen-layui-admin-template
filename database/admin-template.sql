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
    `identity_type` char(20) not null comment '类型,wechat_applet,qq,wb,phone,account_number,email',
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
-- 公共模块
-- ****************************

-- 短信验证码表
create table if not exists `sms_verify_code`(
    `id` int unsigned auto_increment primary key,
    `phone` varchar(20) not null comment '手机号',
    `verify_code` varchar(10) not null comment '验证码',
    `verify_type` char(10) not null default 'login' comment '验证类型',
    `verify_time` int not null default 0 comment '验证时间',
    `other_id` int not null default 0 comment '其他ID',
    `created_at` int not null default 0 comment '添加时间',
    KEY `phone` (`phone`)
)engine=innodb default charset=utf8 comment '短信验证码表';

-- 文件资源表

-- ****************************
-- 公共模块END
-- ****************************