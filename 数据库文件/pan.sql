/*
 Navicat Premium Data Transfer

 Source Server         : MySql
 Source Server Type    : MySQL
 Source Server Version : 50553
 Source Host           : localhost:3306
 Source Schema         : pan

 Target Server Type    : MySQL
 Target Server Version : 50553
 File Encoding         : 65001

 Date: 23/12/2018 22:53:19
*/

SET NAMES utf8mb4;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
-- Table structure for pan_download
-- ----------------------------
DROP TABLE IF EXISTS `pan_download`;
CREATE TABLE `pan_download`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT 'id记录值',
  `fid` int(11) UNSIGNED NOT NULL COMMENT '文件ID',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '下载时间',
  `cip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '下载IP',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_fid`(`fid`) USING BTREE,
  CONSTRAINT `foreign_key_down_file_fid` FOREIGN KEY (`fid`) REFERENCES `pan_fileinfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 1 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pan_fileinfo
-- ----------------------------
DROP TABLE IF EXISTS `pan_fileinfo`;
CREATE TABLE `pan_fileinfo`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '文件ID',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件名',
  `path` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '路径',
  `type` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '文件类型-1文件 0 文件夹',
  `size` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '文件大小M',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '文件地址',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '上传时间',
  `cip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '上传ip',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_uid`(`uid`) USING BTREE,
  CONSTRAINT `foreign_key_file_user_uid` FOREIGN KEY (`uid`) REFERENCES `pan_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 47 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pan_fileinfo
-- ----------------------------
INSERT INTO `pan_fileinfo` VALUES (8, 1, '8.txt', '/', '1', '1', '2', '2018-12-16 22:28:21', '2');
INSERT INTO `pan_fileinfo` VALUES (9, 1, '9.txt', '/', '1', '1', '2', '2018-12-16 22:28:24', '2');
INSERT INTO `pan_fileinfo` VALUES (10, 1, '10.txt', '/', '1', '1', '2', '2018-12-16 22:28:27', '2');
INSERT INTO `pan_fileinfo` VALUES (11, 1, '11.txt', '/', '1', '1', '2', '2018-12-16 22:28:29', '2');
INSERT INTO `pan_fileinfo` VALUES (13, 1, '13.txt', '/', '1', '1', '2', '2018-12-16 22:28:35', '2');
INSERT INTO `pan_fileinfo` VALUES (14, 1, '14.txt', '/', '1', '1', '2', '2018-12-16 22:28:37', '2');
INSERT INTO `pan_fileinfo` VALUES (15, 1, '15.txt', '/', '1', '1', '2', '2018-12-16 22:28:40', '2');
INSERT INTO `pan_fileinfo` VALUES (16, 1, '文件夹', '/', '0', '0', NULL, '2018-12-17 12:54:30', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (17, 1, '17.txt', '/', '1', '1', '2', '2018-12-16 22:28:43', '2');
INSERT INTO `pan_fileinfo` VALUES (18, 1, '18.txt', '/', '1', '1', '2', '2018-12-16 22:28:46', '2');
INSERT INTO `pan_fileinfo` VALUES (19, 1, '19.txt', '/', '1', '1', '2', '2018-12-16 22:28:49', '2');
INSERT INTO `pan_fileinfo` VALUES (22, 1, '1234', '/文件夹', '0', '0', NULL, '2018-12-17 12:39:01', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (24, 1, '12314444', '/', '0', '0', NULL, '2018-12-17 15:13:14', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (28, 1, '新建 Microsoft Word 文档.docx', '/12314444', '1', '15.05078125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/12314444/新建 Microsoft Word 文档.docx', '2018-12-18 18:10:27', '');
INSERT INTO `pan_fileinfo` VALUES (29, 1, '新建文本文档 (2).txt', '/12314444', '1', '1.3896484375', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/12314444/新建文本文档 (2).txt', '2018-12-18 18:10:29', '');
INSERT INTO `pan_fileinfo` VALUES (30, 1, '新建 Microsoft Word 文档.docx', '/文件夹/1234', '1', '15.05078125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/1234/新建 Microsoft Word 文档.docx', '2018-12-18 18:20:43', '');
INSERT INTO `pan_fileinfo` VALUES (32, 1, '新建文本文档 (3).txt', '/文件夹/1234', '1', '0.0908203125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/1234/新建文本文档 (3).txt', '2018-12-18 18:20:46', '');
INSERT INTO `pan_fileinfo` VALUES (33, 1, '123', '/文件夹', '0', '0', NULL, '2018-12-18 18:32:20', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (34, 1, '函调信.doc', '/文件夹/123', '1', '29.5', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/123/函调信.doc', '2018-12-18 18:32:32', '');
INSERT INTO `pan_fileinfo` VALUES (35, 1, '函调信-张芙蓉.doc', '/文件夹/123', '1', '31.5', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/123/函调信-张芙蓉.doc', '2018-12-18 18:32:33', '');
INSERT INTO `pan_fileinfo` VALUES (36, 1, '计划.docx', '/文件夹/123', '1', '23.603515625', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/123/计划.docx', '2018-12-18 18:32:35', '');
INSERT INTO `pan_fileinfo` VALUES (37, 1, '计算器.c', '/文件夹/123', '1', '7.7109375', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1/文件夹/123/计算器.c', '2018-12-18 18:32:37', '');
INSERT INTO `pan_fileinfo` VALUES (38, 1, '通意智能手语翻译系统的研发与应用.pptx', '/', '1', '1216.123046875', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//通意智能手语翻译系统的研发与应用.pptx', '2018-12-19 14:36:46', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (39, 1, '未命名1.cpp', '/', '1', '0.056640625', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//未命名1.cpp', '2018-12-19 14:36:48', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (40, 1, '校园宽带.exe', '/', '1', '700', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//校园宽带.exe', '2018-12-19 14:36:50', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (41, 1, '一、二、四、七.doc', '/', '1', '215.5', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//一、二、四、七.doc', '2018-12-19 14:36:52', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (42, 1, '新建 Microsoft Word 文档.docx', '/', '1', '15.05078125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//新建 Microsoft Word 文档.docx', '2018-12-21 18:39:57', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (43, 1, '新建文本文档 (2).txt', '/', '1', '1.3896484375', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//新建文本文档 (2).txt', '2018-12-21 18:39:59', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (44, 2, '2.py', '/', '1', '0.607421875', 'share-1252352574.cos.ap-chongqing.myqcloud.com/2//2.py', '2018-12-22 19:25:44', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (45, 1, 'S11.rar', '/', '1', '1639.8330078125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//S11.rar', '2018-12-23 16:50:52', '127.0.0.1');
INSERT INTO `pan_fileinfo` VALUES (46, 1, 'socket.html', '/', '1', '0.828125', 'share-1252352574.cos.ap-chongqing.myqcloud.com/1//socket.html', '2018-12-23 16:50:53', '127.0.0.1');

-- ----------------------------
-- Table structure for pan_login
-- ----------------------------
DROP TABLE IF EXISTS `pan_login`;
CREATE TABLE `pan_login`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录值',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '登录时间',
  `cip` varchar(20) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '登录ip',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_uid`(`uid`) USING BTREE,
  CONSTRAINT `foreign_key_login_user_uid` FOREIGN KEY (`uid`) REFERENCES `pan_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 79 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pan_login
-- ----------------------------
INSERT INTO `pan_login` VALUES (6, 1, '2018-12-14 12:07:19', '127.0.0.1');
INSERT INTO `pan_login` VALUES (7, 1, '2018-12-14 13:11:47', '127.0.0.1');
INSERT INTO `pan_login` VALUES (8, 1, '2018-12-14 14:18:53', '127.0.0.1');
INSERT INTO `pan_login` VALUES (9, 1, '2018-12-14 14:32:34', '127.0.0.1');
INSERT INTO `pan_login` VALUES (10, 1, '2018-12-14 15:39:12', '127.0.0.1');
INSERT INTO `pan_login` VALUES (11, 1, '2018-12-14 16:12:17', '127.0.0.1');
INSERT INTO `pan_login` VALUES (12, 1, '2018-12-16 11:16:59', '127.0.0.1');
INSERT INTO `pan_login` VALUES (13, 1, '2018-12-16 11:17:57', '127.0.0.1');
INSERT INTO `pan_login` VALUES (14, 1, '2018-12-16 11:18:59', '127.0.0.1');
INSERT INTO `pan_login` VALUES (15, 1, '2018-12-16 11:49:34', '127.0.0.1');
INSERT INTO `pan_login` VALUES (16, 1, '2018-12-16 11:50:04', '127.0.0.1');
INSERT INTO `pan_login` VALUES (17, 1, '2018-12-16 11:50:44', '127.0.0.1');
INSERT INTO `pan_login` VALUES (18, 1, '2018-12-16 11:51:21', '127.0.0.1');
INSERT INTO `pan_login` VALUES (19, 1, '2018-12-16 11:53:05', '127.0.0.1');
INSERT INTO `pan_login` VALUES (20, 1, '2018-12-16 11:55:17', '127.0.0.1');
INSERT INTO `pan_login` VALUES (21, 1, '2018-12-16 11:55:40', '127.0.0.1');
INSERT INTO `pan_login` VALUES (22, 1, '2018-12-16 11:56:15', '127.0.0.1');
INSERT INTO `pan_login` VALUES (23, 1, '2018-12-16 11:59:06', '127.0.0.1');
INSERT INTO `pan_login` VALUES (24, 1, '2018-12-16 12:01:01', '127.0.0.1');
INSERT INTO `pan_login` VALUES (25, 1, '2018-12-16 12:03:07', '127.0.0.1');
INSERT INTO `pan_login` VALUES (26, 1, '2018-12-16 12:03:14', '127.0.0.1');
INSERT INTO `pan_login` VALUES (27, 1, '2018-12-16 12:03:42', '127.0.0.1');
INSERT INTO `pan_login` VALUES (28, 1, '2018-12-16 20:31:00', '127.0.0.1');
INSERT INTO `pan_login` VALUES (29, 1, '2018-12-16 21:51:49', '127.0.0.1');
INSERT INTO `pan_login` VALUES (30, 1, '2018-12-16 22:52:54', '127.0.0.1');
INSERT INTO `pan_login` VALUES (31, 1, '2018-12-17 09:34:09', '127.0.0.1');
INSERT INTO `pan_login` VALUES (32, 1, '2018-12-17 12:11:34', '127.0.0.1');
INSERT INTO `pan_login` VALUES (33, 1, '2018-12-17 13:21:04', '127.0.0.1');
INSERT INTO `pan_login` VALUES (34, 1, '2018-12-17 14:32:30', '127.0.0.1');
INSERT INTO `pan_login` VALUES (35, 1, '2018-12-17 15:06:53', '127.0.0.1');
INSERT INTO `pan_login` VALUES (36, 1, '2018-12-17 15:53:59', '127.0.0.1');
INSERT INTO `pan_login` VALUES (37, 1, '2018-12-17 17:41:53', '127.0.0.1');
INSERT INTO `pan_login` VALUES (38, 1, '2018-12-17 21:44:06', '127.0.0.1');
INSERT INTO `pan_login` VALUES (39, 1, '2018-12-17 22:56:25', '127.0.0.1');
INSERT INTO `pan_login` VALUES (40, 1, '2018-12-18 08:22:53', '127.0.0.1');
INSERT INTO `pan_login` VALUES (41, 1, '2018-12-18 09:48:39', '127.0.0.1');
INSERT INTO `pan_login` VALUES (42, 1, '2018-12-18 10:49:31', '127.0.0.1');
INSERT INTO `pan_login` VALUES (43, 1, '2018-12-18 11:53:29', '127.0.0.1');
INSERT INTO `pan_login` VALUES (44, 1, '2018-12-18 13:34:12', '127.0.0.1');
INSERT INTO `pan_login` VALUES (45, 1, '2018-12-18 13:45:29', '127.0.0.1');
INSERT INTO `pan_login` VALUES (46, 1, '2018-12-18 15:07:22', '127.0.0.1');
INSERT INTO `pan_login` VALUES (47, 1, '2018-12-18 16:21:31', '127.0.0.1');
INSERT INTO `pan_login` VALUES (48, 1, '2018-12-18 17:21:58', '127.0.0.1');
INSERT INTO `pan_login` VALUES (49, 1, '2018-12-18 18:22:32', '127.0.0.1');
INSERT INTO `pan_login` VALUES (50, 1, '2018-12-18 19:27:26', '127.0.0.1');
INSERT INTO `pan_login` VALUES (51, 1, '2018-12-18 20:37:58', '127.0.0.1');
INSERT INTO `pan_login` VALUES (52, 1, '2018-12-19 09:51:08', '127.0.0.1');
INSERT INTO `pan_login` VALUES (53, 1, '2018-12-19 10:28:20', '127.0.0.1');
INSERT INTO `pan_login` VALUES (54, 1, '2018-12-19 10:53:25', '127.0.0.1');
INSERT INTO `pan_login` VALUES (55, 1, '2018-12-19 13:11:26', '127.0.0.1');
INSERT INTO `pan_login` VALUES (56, 1, '2018-12-19 14:12:09', '127.0.0.1');
INSERT INTO `pan_login` VALUES (57, 1, '2018-12-19 15:02:26', '127.0.0.1');
INSERT INTO `pan_login` VALUES (58, 1, '2018-12-19 15:38:39', '127.0.0.1');
INSERT INTO `pan_login` VALUES (59, 1, '2018-12-19 18:01:42', '127.0.0.1');
INSERT INTO `pan_login` VALUES (60, 1, '2018-12-19 21:19:25', '127.0.0.1');
INSERT INTO `pan_login` VALUES (61, 1, '2018-12-19 22:59:11', '127.0.0.1');
INSERT INTO `pan_login` VALUES (62, 1, '2018-12-20 12:20:44', '127.0.0.1');
INSERT INTO `pan_login` VALUES (63, 1, '2018-12-20 12:25:07', '127.0.0.1');
INSERT INTO `pan_login` VALUES (64, 1, '2018-12-20 12:27:11', '127.0.0.1');
INSERT INTO `pan_login` VALUES (65, 1, '2018-12-20 12:34:30', '127.0.0.1');
INSERT INTO `pan_login` VALUES (66, 1, '2018-12-20 22:26:18', '127.0.0.1');
INSERT INTO `pan_login` VALUES (67, 1, '2018-12-21 08:12:22', '127.0.0.1');
INSERT INTO `pan_login` VALUES (68, 1, '2018-12-21 13:09:08', '127.0.0.1');
INSERT INTO `pan_login` VALUES (69, 2, '2018-12-21 13:09:35', '127.0.0.1');
INSERT INTO `pan_login` VALUES (70, 1, '2018-12-21 13:09:44', '127.0.0.1');
INSERT INTO `pan_login` VALUES (71, 1, '2018-12-21 17:02:10', '127.0.0.1');
INSERT INTO `pan_login` VALUES (72, 1, '2018-12-21 18:39:24', '127.0.0.1');
INSERT INTO `pan_login` VALUES (73, 1, '2018-12-22 19:14:03', '127.0.0.1');
INSERT INTO `pan_login` VALUES (74, 1, '2018-12-22 19:21:59', '127.0.0.1');
INSERT INTO `pan_login` VALUES (75, 2, '2018-12-22 19:22:17', '127.0.0.1');
INSERT INTO `pan_login` VALUES (76, 1, '2018-12-22 21:28:30', '127.0.0.1');
INSERT INTO `pan_login` VALUES (77, 1, '2018-12-23 14:23:38', '127.0.0.1');
INSERT INTO `pan_login` VALUES (78, 1, '2018-12-23 16:05:31', '127.0.0.1');

-- ----------------------------
-- Table structure for pan_share
-- ----------------------------
DROP TABLE IF EXISTS `pan_share`;
CREATE TABLE `pan_share`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '记录值',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户id',
  `fid` int(11) UNSIGNED NOT NULL COMMENT '文件id',
  `url` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '分享链接',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '分享密码 空则没有密码',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '分享时间',
  `etime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '过期时间 0 为永不过期',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_uid`(`uid`) USING BTREE,
  INDEX `index_fid`(`fid`) USING BTREE,
  CONSTRAINT `foreign_key_share_user_id` FOREIGN KEY (`uid`) REFERENCES `pan_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `foregin_key_share_file_id` FOREIGN KEY (`fid`) REFERENCES `pan_fileinfo` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB AUTO_INCREMENT = 26 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pan_share
-- ----------------------------
INSERT INTO `pan_share` VALUES (19, 1, 32, '/index/share/down/3e11c29b88a80cf862d66b0481ef3367', 'ed58', '2018-12-19 13:55:44', '0000-00-00 00:00:00');
INSERT INTO `pan_share` VALUES (20, 1, 38, '/index/share/down/c2ac219e0efce755874ac787d8de5907', 'e015', '2018-12-19 14:37:01', '0000-00-00 00:00:00');
INSERT INTO `pan_share` VALUES (21, 1, 33, '/index/share/down/8588340389ac2c05528cbe196de38cec', NULL, '2018-12-19 14:47:03', '2018-12-20 14:47:03');
INSERT INTO `pan_share` VALUES (22, 1, 33, '/index/share/down/e21c9f03addb121b1d2ae9428c361870', NULL, '2018-12-19 14:53:48', '0000-00-00 00:00:00');
INSERT INTO `pan_share` VALUES (23, 1, 33, '/index/share/down/e154da195bbe140d2e88c2eef96704a7', NULL, '2018-12-19 15:38:48', '2018-12-26 15:38:48');
INSERT INTO `pan_share` VALUES (24, 1, 16, '/index/share/down/965ff6ad1bf655aac529a075fcd039d2', 'e0ec', '2018-12-21 18:39:42', '2018-12-28 18:39:42');
INSERT INTO `pan_share` VALUES (25, 1, 30, '/index/share/down/15e75589df6e57de56c28912b815fef5', '5226', '2018-12-23 17:50:45', '0000-00-00 00:00:00');

-- ----------------------------
-- Table structure for pan_star
-- ----------------------------
DROP TABLE IF EXISTS `pan_star`;
CREATE TABLE `pan_star`  (
  `id` int(11) NOT NULL COMMENT '记录值',
  `uid` int(11) UNSIGNED NOT NULL COMMENT '用户ID',
  `sid` int(11) UNSIGNED NOT NULL COMMENT '星标用户ID',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `cip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '创建IP',
  PRIMARY KEY (`id`) USING BTREE,
  INDEX `index_uid_sid`(`uid`, `sid`) USING BTREE,
  INDEX `foreign_key_star_user_sid`(`sid`) USING BTREE,
  CONSTRAINT `foreign_key_star_user_sid` FOREIGN KEY (`sid`) REFERENCES `pan_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `foreign_key_star_user_uid` FOREIGN KEY (`uid`) REFERENCES `pan_user` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE = InnoDB CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Table structure for pan_user
-- ----------------------------
DROP TABLE IF EXISTS `pan_user`;
CREATE TABLE `pan_user`  (
  `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户名',
  `password` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户密码',
  `nickname` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '用户ID-名字',
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '手机号码',
  `email` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '邮箱',
  `avator` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '头像-地址',
  `maxsize` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '最大容量',
  `usedsize` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL DEFAULT '0' COMMENT '已使用容量',
  `ctime` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP COMMENT '创建时间',
  `cip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL COMMENT '注册IP',
  `ltime` timestamp NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT '上次登陆时间',
  `lip` varchar(255) CHARACTER SET utf8 COLLATE utf8_general_ci DEFAULT NULL COMMENT '上次登陆ip',
  `vip` int(1) NOT NULL DEFAULT 0 COMMENT '是否VIP - 1 - 是 0 不是 默认 0',
  `introduce` text CHARACTER SET utf8 COLLATE utf8_general_ci COMMENT '描述- 签名',
  `admin` int(1) NOT NULL DEFAULT 0 COMMENT '是否为管理员 1 是 0 否 默认0',
  `status` int(1) NOT NULL DEFAULT 0 COMMENT '状态： 0 未激活 1 正常',
  PRIMARY KEY (`id`) USING BTREE
) ENGINE = InnoDB AUTO_INCREMENT = 4 CHARACTER SET = utf8 COLLATE = utf8_general_ci ROW_FORMAT = Compact;

-- ----------------------------
-- Records of pan_user
-- ----------------------------
INSERT INTO `pan_user` VALUES (1, '123456', '0a2b5ab5c8296e0039abf4091726bd6c33537fc396db5', 'Jst223', '18095784578', '123@qq.com', '20181216\\37842457e6e3096cf30423be071b9e7a.jpg', '123', '123', '2018-12-21 13:07:17', '123', '0000-00-00 00:00:00', '123', 0, '123', 1, 1);
INSERT INTO `pan_user` VALUES (2, '1234563', '0a2b5ab5c8296e0039abf4091726bd6c33537fc396db5', 'Jst223', '18095784578', '123@qq.com', '20181216\\37842457e6e3096cf30423be071b9e7a.jpg', '123', '123', '2018-12-21 13:09:21', '123', '0000-00-00 00:00:00', '123', 0, '123', 0, 1);
INSERT INTO `pan_user` VALUES (3, '123', 'd6b73fc5a9d7af14ef091596148d152956ec8039e96a6', '444', NULL, 'admin@oibit.cn', NULL, '0', '0', '2018-12-22 19:14:22', '127.0.0.1', '0000-00-00 00:00:00', NULL, 0, NULL, 0, 1);

SET FOREIGN_KEY_CHECKS = 1;
