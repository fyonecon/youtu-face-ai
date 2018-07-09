/*
Navicat MySQL Data Transfer

Source Server         : 负载主库数据
Source Server Version : 50640
Source Host           : 47.106.203.74:3306
Source Database       : tpai

Target Server Type    : MYSQL
Target Server Version : 50640
File Encoding         : 65001

Date: 2018-07-07 17:22:13
*/

SET FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for ai_error_log
-- ----------------------------
DROP TABLE IF EXISTS `ai_error_log`;
CREATE TABLE `ai_error_log` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `content` varchar(255) DEFAULT NULL,
  `msg` varchar(2550) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ai_error_log
-- ----------------------------
INSERT INTO `ai_error_log` VALUES ('1', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('2', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('3', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('4', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('5', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('6', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('7', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('8', 'post数据', '{\"name\":\"luwenjing\",\"age\":\"22\"}');
INSERT INTO `ai_error_log` VALUES ('9', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('10', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('11', 'post数据', '{\"yes\":\"1\",\"no\":\"2\"}');
INSERT INTO `ai_error_log` VALUES ('12', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('13', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('14', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('15', 'post数据', '{\"name\":\"luwenjing\",\"age\":\"22\"}');
INSERT INTO `ai_error_log` VALUES ('16', 'post数据', '{\"base64_img\":\"2\"}');
INSERT INTO `ai_error_log` VALUES ('17', 'post数据', '{\"------WebKitFormBoundary8qs4uELQUHzzvI5i\\r\\nContent-Disposition:_form-data;_name\":\"\\\"base64_img\\\"\\r\\n\\r\\n1\\r\\n------WebKitFormBoundary8qs4uELQUHzzvI5i\\r\\nContent-Disposition: form-data; name=\\\"id\\\"\\r\\n\\r\\n1\\r\\n------WebKitFormBoundary8qs4uELQUHzzvI5i--\\r\\n\"}');
INSERT INTO `ai_error_log` VALUES ('18', 'post数据', '[]');
INSERT INTO `ai_error_log` VALUES ('19', 'post数据', '{\"{\\\"base64_img\\\":2}\":\"\"}');
INSERT INTO `ai_error_log` VALUES ('20', 'post数据', '{\"{\\\"base64_img\\\":\\\"http:\\/\\/td_t0fdt_cn\\/tpai\\/public\\/?s\":\"\\/face\\/face_date\\/face_api\\\"}\"}');
INSERT INTO `ai_error_log` VALUES ('21', 'post数据', null);
INSERT INTO `ai_error_log` VALUES ('22', 'post数据', 'array');

-- ----------------------------
-- Table structure for ai_face_date
-- ----------------------------
DROP TABLE IF EXISTS `ai_face_date`;
CREATE TABLE `ai_face_date` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `original_img` varchar(100) DEFAULT NULL COMMENT '原始图片',
  `face_date` varchar(2000) DEFAULT NULL COMMENT '脸部最终坐标数据',
  `only_id` varchar(80) DEFAULT NULL COMMENT '唯一随机id',
  `create_time` int(20) NOT NULL,
  `pv` int(50) DEFAULT '1' COMMENT '图片PV',
  `status` int(5) DEFAULT '1' COMMENT '图片状态，1可用，0不可用',
  `update_time` int(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=19 DEFAULT CHARSET=utf8mb4;

-- ----------------------------
-- Records of ai_face_date
-- ----------------------------
INSERT INTO `ai_face_date` VALUES ('1', 'k07_2018-07-07_14-10-50_5b40596a92bcd.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-10-50_5b40596a92bcd.jpg\"}}', '1530943852onlysyv4gtfmri49fg6cp2nuy75f6ary', '1530943852', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('2', 'k07_2018-07-07_14-14-51_5b405a5b6f235.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-14-51_5b405a5b6f235.jpg\"}}', '1530944092onlygto5cpj5hwhu0lzlohmx0pw8hh8ks', '1530944092', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('3', 'k07_2018-07-07_14-16-26_5b405aba488df.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-16-26_5b405aba488df.jpg\"}}', '1530944187onlykmztqib295itnj7mg5p1', '1530944187', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('4', 'k07_2018-07-07_14-17-34_5b405afee1290.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-17-34_5b405afee1290.jpg\"}}', '1530944255only7j9csuzc6h21j5wl0l9t46p', '1530944255', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('5', 'k07_2018-07-07_14-18-27_5b405b335f2b4.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-18-27_5b405b335f2b4.jpg\"}}', '1530944308onlyevy76azcxfz0qdci6chq', '1530944308', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('6', 'k07_2018-07-07_14-19-58_5b405b8eec622.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-19-58_5b405b8eec622.jpg\"}}', '1530944399onlyi8xd7ljptt2enzwsk37p8hd14xw9', '1530944399', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('7', 'k07_2018-07-07_14-20-37_5b405bb5cdc10.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-20-37_5b405bb5cdc10.jpg\"}}', '1530944438onlyuus8m8tovrkuakh8df7c7jlu', '1530944438', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('8', 'k07_2018-07-07_14-21-16_5b405bdc9e1ac.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-21-16_5b405bdc9e1ac.jpg\"}}', '1530944477onlyzr7p9bvv8r0u87wxgfd08xul', '1530944477', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('9', 'k07_2018-07-07_14-29-43_5b405dd7e025e.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-29-43_5b405dd7e025e.jpg\"}}', '1530944984onlyxiosc5pxmgbudqcunfms61', '1530944984', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('10', 'k07_2018-07-07_14-44-53_5b40616533597.jpg', null, '1530945894only4uj8l5fu1uokmywu7xgl6z', '1530945894', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('11', 'k07_2018-07-07_14-45-38_5b40619296391.jpg', null, '1530945939onlyohqbwmh8n1dn04e6icx3u6hdj0', '1530945939', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('12', 'k07_2018-07-07_14-51-17_5b4062e5148cf.jpg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":600,\"img_h\":600,\"face_w\":185,\"face_h\":170,\"x\":200,\"y\":206,\"x_offset\":8,\"y_offset\":9,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_14-51-17_5b4062e5148cf.jpg\"},\"only_num\":\"1530946278only5at59m94yxj0r1xrboymhur\"}', '1530946278only5at59m94yxj0r1xrboymhur', '1530946278', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('13', 'k07_2018-07-07_15-44-04_5b406f447364f.jpeg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":400,\"img_h\":299,\"face_w\":215,\"face_h\":205,\"x\":177,\"y\":18,\"x_offset\":9,\"y_offset\":10,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_15-44-04_5b406f447364f.jpeg\"},\"only_num\":\"1530949445onlyuolmf6u00bjcou0t7nt4r\"}', '1530949445onlyuolmf6u00bjcou0t7nt4r', '1530949445', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('14', 'k07_2018-07-07_15-46-16_5b406fc884798.jpeg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":508,\"img_h\":735,\"face_w\":35,\"face_h\":40,\"x\":239,\"y\":315,\"x_offset\":1,\"y_offset\":1,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_15-46-16_5b406fc884798.jpeg\"},\"only_num\":\"1530949577onlykfubze2dggvhmg6i2dluulio6m4\"}', '1530949577onlykfubze2dggvhmg6i2dluulio6m4', '1530949577', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('15', 'k07_2018-07-07_15-46-29_5b406fd525723.png', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":390,\"img_h\":662,\"face_w\":199,\"face_h\":181,\"x\":95,\"y\":406,\"x_offset\":9,\"y_offset\":10,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_15-46-29_5b406fd525723.png\"},\"only_num\":\"1530949592onlyb4bmzhol4jxovog32n0pgpvr0is0\"}', '1530949592onlyb4bmzhol4jxovog32n0pgpvr0is0', '1530949592', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('16', 'k07_2018-07-07_15-47-20_5b4070080eb46.jpeg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":400,\"img_h\":299,\"face_w\":215,\"face_h\":205,\"x\":177,\"y\":18,\"x_offset\":9,\"y_offset\":10,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_15-47-20_5b4070080eb46.jpeg\"},\"only_num\":\"1530949641onlyhjixhruhdystfhkiz9996rh\"}', '1530949641onlyhjixhruhdystfhkiz9996rh', '1530949641', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('17', 'k07_2018-07-07_17-17-15_5b40851b81f12.jpeg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":400,\"img_h\":299,\"face_w\":215,\"face_h\":205,\"x\":177,\"y\":18,\"x_offset\":9,\"y_offset\":10,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_17-17-15_5b40851b81f12.jpeg\"},\"only_num\":\"1530955036onlyfosd7ek6zuda53gisy327ij\"}', '1530955036onlyfosd7ek6zuda53gisy327ij', '1530955036', '1', '1', null);
INSERT INTO `ai_face_date` VALUES ('18', 'k07_2018-07-07_17-19-43_5b4085afb82c2.jpeg', '{\"status\":1,\"msg\":\"当前图片中人五官的黄金坐标值\",\"img_set\":{\"img_w\":400,\"img_h\":299,\"face_w\":215,\"face_h\":205,\"x\":177,\"y\":18,\"x_offset\":9,\"y_offset\":10,\"img_url\":\"http:\\/\\/qiniu.mukzz.pw\\/k07_2018-07-07_17-19-43_5b4085afb82c2.jpeg\"},\"only_num\":\"1530955184only1tstpppazicrt7s2o7wkorv82yas\"}', '1530955184only1tstpppazicrt7s2o7wkorv82yas', '1530955184', '1', '1', null);
