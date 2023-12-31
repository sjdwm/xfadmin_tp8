-- -----------------------------
-- Think MySQL Data Transfer 
-- 
-- Host     : 127.0.0.1
-- Port     : 3306
-- Database : xfadmin
-- 
-- Part : #1
-- Date : 2023-11-06 20:44:47
-- -----------------------------

SET FOREIGN_KEY_CHECKS = 0;


-- -----------------------------
-- Table structure for `xf_article`
-- -----------------------------
DROP TABLE IF EXISTS `xf_article`;
CREATE TABLE `xf_article` (
  `aid` int(11) NOT NULL AUTO_INCREMENT,
  `sid` int(11) NOT NULL COMMENT '分类id',
  `title` varchar(255) NOT NULL COMMENT '标题',
  `seotitle` varchar(255) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL COMMENT '关键词',
  `description` varchar(255) NOT NULL COMMENT '摘要',
  `thumbnail` varchar(255) NOT NULL COMMENT '缩略图',
  `content` text NOT NULL COMMENT '内容',
  `t` int(10) unsigned NOT NULL COMMENT '时间',
  `n` int(10) unsigned NOT NULL DEFAULT '0' COMMENT '点击',
  PRIMARY KEY (`aid`),
  KEY `sid` (`sid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------
-- Table structure for `xf_auth_group`
-- -----------------------------
DROP TABLE IF EXISTS `xf_auth_group`;
CREATE TABLE `xf_auth_group` (
  `id` mediumint(8) unsigned NOT NULL AUTO_INCREMENT,
  `title` char(100) NOT NULL DEFAULT '',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `rules` varchar(255) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_auth_group`
-- -----------------------------
INSERT INTO `xf_auth_group` VALUES ('1', '超级管理员', '1', '1,2,58,65,59,60,61,62,3,56,4,6,5,7,8,9,10,51,52,53,57,11,54,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,29,30,31,32,33,34,36,37,38,39,40,41,42,43,44,45,46,47,63,48,49,50,55');
INSERT INTO `xf_auth_group` VALUES ('2', '管理员', '1', '13,14,23,22,21,20,19,18,17,16,15,24,36,34,33,32,31,30,29,27,26,25,1');
INSERT INTO `xf_auth_group` VALUES ('3', '普通用户', '1', '1');
INSERT INTO `xf_auth_group` VALUES ('6', '333', '0', '1,2');

-- -----------------------------
-- Table structure for `xf_auth_group_access`
-- -----------------------------
DROP TABLE IF EXISTS `xf_auth_group_access`;
CREATE TABLE `xf_auth_group_access` (
  `uid` mediumint(8) unsigned NOT NULL,
  `group_id` mediumint(8) unsigned NOT NULL,
  UNIQUE KEY `uid_group_id` (`uid`,`group_id`),
  KEY `uid` (`uid`),
  KEY `group_id` (`group_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_auth_group_access`
-- -----------------------------
INSERT INTO `xf_auth_group_access` VALUES ('1', '1');

-- -----------------------------
-- Table structure for `xf_auth_rule`
-- -----------------------------
DROP TABLE IF EXISTS `xf_auth_rule`;
CREATE TABLE `xf_auth_rule` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pid` int(11) NOT NULL,
  `name` char(80) NOT NULL DEFAULT '',
  `title` char(20) NOT NULL DEFAULT '',
  `icon` varchar(255) NOT NULL,
  `type` tinyint(1) NOT NULL DEFAULT '1',
  `status` tinyint(1) NOT NULL DEFAULT '1',
  `condition` char(100) NOT NULL DEFAULT '',
  `islink` tinyint(1) NOT NULL DEFAULT '1',
  `o` int(11) NOT NULL COMMENT '排序',
  `tips` text NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=66 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_auth_rule`
-- -----------------------------
INSERT INTO `xf_auth_rule` VALUES ('1', '0', 'Index/index', '控制台', 'menu-icon fa fa-tachometer', '1', '1', '', '1', '1', '友情提示：经常查看操作日志，发现异常以便及时追查原因。');
INSERT INTO `xf_auth_rule` VALUES ('2', '0', '', '系统设置', 'menu-icon fa fa-cog', '1', '1', '', '1', '2', '');
INSERT INTO `xf_auth_rule` VALUES ('3', '2', 'Setting/setting', '网站设置', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '3', '这是网站设置的提示。');
INSERT INTO `xf_auth_rule` VALUES ('4', '2', 'Menu/index', '后台菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '4', '');
INSERT INTO `xf_auth_rule` VALUES ('5', '2', 'Menu/add', '新增菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '5', '');
INSERT INTO `xf_auth_rule` VALUES ('6', '4', 'Menu/edit', '编辑菜单', '', '1', '1', '', '0', '6', '');
INSERT INTO `xf_auth_rule` VALUES ('7', '2', 'Menu/update', '保存菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '7', '');
INSERT INTO `xf_auth_rule` VALUES ('8', '2', 'Menu/del', '删除菜单', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '8', '');
INSERT INTO `xf_auth_rule` VALUES ('9', '2', 'Database/backup', '数据库备份', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '9', '');
INSERT INTO `xf_auth_rule` VALUES ('10', '9', 'Database/recovery', '数据库还原', '', '1', '1', '', '0', '10', '');
INSERT INTO `xf_auth_rule` VALUES ('11', '2', 'Update/update', '在线升级', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '11', '');
INSERT INTO `xf_auth_rule` VALUES ('12', '2', 'Update/devlog', '开发日志', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '12', '');
INSERT INTO `xf_auth_rule` VALUES ('13', '0', '', '用户及组', 'menu-icon fa fa-users', '1', '1', '', '1', '13', '');
INSERT INTO `xf_auth_rule` VALUES ('14', '13', 'Member/index', '用户管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '14', '');
INSERT INTO `xf_auth_rule` VALUES ('15', '13', 'Member/add', '新增用户', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '15', '');
INSERT INTO `xf_auth_rule` VALUES ('16', '13', 'Member/edit', '编辑用户', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '16', '');
INSERT INTO `xf_auth_rule` VALUES ('17', '13', 'Member/update', '保存用户', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '17', '');
INSERT INTO `xf_auth_rule` VALUES ('18', '13', 'Member/del', '删除用户', '', '1', '1', '', '0', '18', '');
INSERT INTO `xf_auth_rule` VALUES ('19', '13', 'Group/index', '用户组管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '19', '');
INSERT INTO `xf_auth_rule` VALUES ('20', '13', 'Group/add', '新增用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '20', '');
INSERT INTO `xf_auth_rule` VALUES ('21', '13', 'Group/edit', '编辑用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '21', '');
INSERT INTO `xf_auth_rule` VALUES ('22', '13', 'Group/update', '保存用户组', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '22', '');
INSERT INTO `xf_auth_rule` VALUES ('23', '13', 'Group/del', '删除用户组', '', '1', '1', '', '0', '23', '');
INSERT INTO `xf_auth_rule` VALUES ('24', '0', '', '网站内容', 'menu-icon fa fa-desktop', '1', '1', '', '1', '24', '');
INSERT INTO `xf_auth_rule` VALUES ('25', '24', 'Article/index', '文章管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '25', '网站虽然重要，身体更重要，出去走走吧。');
INSERT INTO `xf_auth_rule` VALUES ('26', '24', 'Article/add', '新增文章', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '26', '');
INSERT INTO `xf_auth_rule` VALUES ('27', '24', 'Article/edit', '编辑文章', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '27', '');
INSERT INTO `xf_auth_rule` VALUES ('29', '24', 'Article/update', '保存文章', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '29', '');
INSERT INTO `xf_auth_rule` VALUES ('30', '24', 'Article/del', '删除文章', '', '1', '1', '', '0', '30', '');
INSERT INTO `xf_auth_rule` VALUES ('31', '24', 'Category/index', '分类管理', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '31', '');
INSERT INTO `xf_auth_rule` VALUES ('32', '24', 'Category/add', '新增分类', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '32', '');
INSERT INTO `xf_auth_rule` VALUES ('33', '24', 'Category/edit', '编辑分类', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '33', '');
INSERT INTO `xf_auth_rule` VALUES ('34', '24', 'Category/update', '保存分类', 'menu-icon fa fa-caret-right', '1', '1', '', '0', '34', '');
INSERT INTO `xf_auth_rule` VALUES ('36', '24', 'Category/del', '删除分类', '', '1', '1', '', '0', '36', '');
INSERT INTO `xf_auth_rule` VALUES ('37', '0', '', '其它功能', 'menu-icon fa fa-legal', '1', '1', '', '1', '37', '');
INSERT INTO `xf_auth_rule` VALUES ('38', '37', 'Link/index', '友情链接', 'menu-icon fa fa-caret-right', '1', '1', '', '1', '38', '');
INSERT INTO `xf_auth_rule` VALUES ('39', '37', 'Link/add', '增加链接', '', '1', '1', '', '1', '39', '');
INSERT INTO `xf_auth_rule` VALUES ('40', '37', 'Link/edit', '编辑链接', '', '1', '1', '', '0', '40', '');
INSERT INTO `xf_auth_rule` VALUES ('41', '37', 'Link/update', '保存链接', '', '1', '1', '', '0', '41', '');
INSERT INTO `xf_auth_rule` VALUES ('42', '37', 'Link/del', '删除链接', '', '1', '1', '', '0', '42', '');
INSERT INTO `xf_auth_rule` VALUES ('43', '37', 'Flash/index', '焦点图', 'menu-icon fa fa-desktop', '1', '1', '', '1', '43', '');
INSERT INTO `xf_auth_rule` VALUES ('44', '37', 'Flash/add', '新增焦点图', '', '1', '1', '', '1', '44', '');
INSERT INTO `xf_auth_rule` VALUES ('45', '37', 'Flash/update', '保存焦点图', '', '1', '1', '', '0', '45', '');
INSERT INTO `xf_auth_rule` VALUES ('46', '37', 'Flash/edit', '编辑焦点图', '', '1', '1', '', '0', '46', '');
INSERT INTO `xf_auth_rule` VALUES ('47', '37', 'Flash/del', '删除焦点图', '', '1', '1', '', '0', '47', '');
INSERT INTO `xf_auth_rule` VALUES ('48', '0', 'Personal/index', '个人中心', 'menu-icon fa fa-user', '1', '1', '', '1', '48', '');
INSERT INTO `xf_auth_rule` VALUES ('49', '48', 'Personal/profile', '个人资料', 'menu-icon fa fa-user', '1', '1', '', '1', '49', '');
INSERT INTO `xf_auth_rule` VALUES ('50', '48', 'Logout/index', '退出', '', '1', '1', '', '1', '50', '');
INSERT INTO `xf_auth_rule` VALUES ('51', '9', 'Database/export', '备份', '', '1', '1', '', '0', '51', '');
INSERT INTO `xf_auth_rule` VALUES ('52', '9', 'Database/optimize', '数据优化', '', '1', '1', '', '0', '52', '');
INSERT INTO `xf_auth_rule` VALUES ('53', '9', 'Database/repair', '修复表', '', '1', '1', '', '0', '53', '');
INSERT INTO `xf_auth_rule` VALUES ('54', '11', 'Update/updating', '升级安装', '', '1', '1', '', '0', '54', '');
INSERT INTO `xf_auth_rule` VALUES ('55', '48', 'Personal/update', '资料保存', '', '1', '1', '', '0', '55', '');
INSERT INTO `xf_auth_rule` VALUES ('56', '3', 'Setting/update', '设置保存', '', '1', '1', '', '0', '56', '');
INSERT INTO `xf_auth_rule` VALUES ('57', '9', 'Database/del', '备份删除', '', '1', '1', '', '0', '57', '');
INSERT INTO `xf_auth_rule` VALUES ('58', '2', 'variable/index', '自定义变量', '', '1', '1', '', '1', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('59', '58', 'variable/add', '新增变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('60', '58', 'variable/edit', '编辑变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('61', '58', 'variable/update', '保存变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('62', '58', 'variable/del', '删除变量', '', '1', '1', '', '0', '0', '');
INSERT INTO `xf_auth_rule` VALUES ('63', '37', 'Facebook/add', '用户反馈', '', '1', '1', '', '1', '63', '');

-- -----------------------------
-- Table structure for `xf_category`
-- -----------------------------
DROP TABLE IF EXISTS `xf_category`;
CREATE TABLE `xf_category` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type` tinyint(1) NOT NULL COMMENT '0正常，1单页，2外链',
  `pid` int(11) NOT NULL COMMENT '父ID',
  `name` varchar(100) NOT NULL COMMENT '分类名称',
  `dir` varchar(100) NOT NULL COMMENT '目录名称',
  `seotitle` varchar(200) DEFAULT NULL COMMENT 'SEO标题',
  `keywords` varchar(255) NOT NULL,
  `description` varchar(255) NOT NULL,
  `content` text NOT NULL,
  `url` varchar(255) NOT NULL,
  `cattemplate` varchar(100) NOT NULL,
  `contemplate` varchar(100) NOT NULL,
  `o` int(11) NOT NULL COMMENT '排序',
  PRIMARY KEY (`id`),
  KEY `fsid` (`pid`)
) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8;


-- -----------------------------
-- Table structure for `xf_devlog`
-- -----------------------------
DROP TABLE IF EXISTS `xf_devlog`;
CREATE TABLE `xf_devlog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `v` varchar(225) NOT NULL COMMENT '版本号',
  `y` int(4) NOT NULL COMMENT '年分',
  `t` int(10) NOT NULL COMMENT '发布日期',
  `log` text NOT NULL COMMENT '更新日志',
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_devlog`
-- -----------------------------
INSERT INTO `xf_devlog` VALUES ('1', '1.0.0', '2016', '1440259200', 'xfADMIN第一个版本发布。');
INSERT INTO `xf_devlog` VALUES ('2', '1.0.1', '2016', '1440259200', '修改cookie过于简单的安全风险。');

-- -----------------------------
-- Table structure for `xf_flash`
-- -----------------------------
DROP TABLE IF EXISTS `xf_flash`;
CREATE TABLE `xf_flash` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `pic` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------
-- Table structure for `xf_links`
-- -----------------------------
DROP TABLE IF EXISTS `xf_links`;
CREATE TABLE `xf_links` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(255) NOT NULL,
  `url` varchar(255) NOT NULL,
  `logo` varchar(255) NOT NULL,
  `o` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `o` (`o`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


-- -----------------------------
-- Table structure for `xf_log`
-- -----------------------------
DROP TABLE IF EXISTS `xf_log`;
CREATE TABLE `xf_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(100) NOT NULL,
  `t` int(10) NOT NULL,
  `ip` varchar(16) NOT NULL,
  `log` varchar(255) NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_log`
-- -----------------------------
INSERT INTO `xf_log` VALUES ('1', 'admin', '1699274240', '127.0.0.1', '备份完成！');

-- -----------------------------
-- Table structure for `xf_member`
-- -----------------------------
DROP TABLE IF EXISTS `xf_member`;
CREATE TABLE `xf_member` (
  `uid` int(11) NOT NULL AUTO_INCREMENT,
  `user` varchar(225) NOT NULL,
  `head` varchar(255) NOT NULL COMMENT '头像',
  `sex` tinyint(1) NOT NULL COMMENT '0保密1男，2女',
  `birthday` int(10) NOT NULL COMMENT '生日',
  `phone` varchar(20) NOT NULL COMMENT '电话',
  `qq` varchar(20) NOT NULL COMMENT 'QQ',
  `email` varchar(255) NOT NULL COMMENT '邮箱',
  `password` varchar(32) NOT NULL,
  `t` int(10) unsigned NOT NULL COMMENT '注册时间',
  PRIMARY KEY (`uid`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_member`
-- -----------------------------
INSERT INTO `xf_member` VALUES ('1', 'admin', '/Public/attached/201601/1453389194.png', '1', '1462377600', '13800138000', '125745176', 'admin@hotxf.com', '4f95513c686a7c891392becdf407ba19', '1462377600');

-- -----------------------------
-- Table structure for `xf_setting`
-- -----------------------------
DROP TABLE IF EXISTS `xf_setting`;
CREATE TABLE `xf_setting` (
  `k` varchar(100) NOT NULL COMMENT '变量',
  `v` varchar(255) NOT NULL COMMENT '值',
  `type` tinyint(1) NOT NULL COMMENT '0系统，1自定义',
  `name` varchar(255) NOT NULL COMMENT '说明',
  PRIMARY KEY (`k`),
  KEY `k` (`k`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- -----------------------------
-- Records of `xf_setting`
-- -----------------------------
INSERT INTO `xf_setting` VALUES ('description', '网站描述', '0', '');
INSERT INTO `xf_setting` VALUES ('footer', '2017©小风网络', '0', '');
INSERT INTO `xf_setting` VALUES ('keywords', '关键词', '0', '');
INSERT INTO `xf_setting` VALUES ('sitename', '小风博客', '0', '');
INSERT INTO `xf_setting` VALUES ('test', '测试', '1', '测试变量');
INSERT INTO `xf_setting` VALUES ('title', '小风博客', '0', '');
