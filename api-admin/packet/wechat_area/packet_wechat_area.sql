/*
Navicat MySQL Data Transfer

Target Server Type    : MYSQL
Target Server Version : 50540
File Encoding         : 65001

Date: 2016-10-18 17:45:31
*/

SET
FOREIGN_KEY_CHECKS=0;

-- ----------------------------
-- Table structure for `oc_packet_wechat_area`
-- ----------------------------
DROP TABLE IF EXISTS `oc_packet_wechat_area`;
CREATE TABLE `oc_packet_wechat_area`
(
    `id`       int(11) unsigned NOT NULL AUTO_INCREMENT,
    `country`  varchar(32) NOT NULL DEFAULT '' COMMENT '国家名称',
    `province` varchar(32) NOT NULL DEFAULT '' COMMENT '省份名称',
    `city`     varchar(32) NOT NULL DEFAULT '' COMMENT '城市名称',
    PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=482 DEFAULT CHARSET=utf8 COMMENT='地区信息表';

-- ----------------------------
-- Records of oc_packet_wechat_area
-- ----------------------------
INSERT INTO `oc_packet_wechat_area`
VALUES ('1', '中国', '四川', '凉山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('2', '中国', '四川', '资阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('3', '中国', '四川', '成都');
INSERT INTO `oc_packet_wechat_area`
VALUES ('4', '中国', '四川', '自贡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('5', '中国', '四川', '泸州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('6', '中国', '四川', '攀枝花');
INSERT INTO `oc_packet_wechat_area`
VALUES ('7', '中国', '四川', '绵阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('8', '中国', '四川', '德阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('9', '中国', '四川', '遂宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('10', '中国', '四川', '广元');
INSERT INTO `oc_packet_wechat_area`
VALUES ('11', '中国', '四川', '乐山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('12', '中国', '四川', '内江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('13', '中国', '四川', '南充');
INSERT INTO `oc_packet_wechat_area`
VALUES ('14', '中国', '四川', '宜宾');
INSERT INTO `oc_packet_wechat_area`
VALUES ('15', '中国', '四川', '眉山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('16', '中国', '四川', '达州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('17', '中国', '四川', '广安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('18', '中国', '四川', '巴中');
INSERT INTO `oc_packet_wechat_area`
VALUES ('19', '中国', '四川', '雅安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('20', '中国', '四川', '甘孜');
INSERT INTO `oc_packet_wechat_area`
VALUES ('21', '中国', '四川', '阿坝');
INSERT INTO `oc_packet_wechat_area`
VALUES ('22', '中国', '重庆', '酉阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('23', '中国', '重庆', '彭水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('24', '中国', '重庆', '合川');
INSERT INTO `oc_packet_wechat_area`
VALUES ('25', '中国', '重庆', '永川');
INSERT INTO `oc_packet_wechat_area`
VALUES ('26', '中国', '重庆', '江津');
INSERT INTO `oc_packet_wechat_area`
VALUES ('27', '中国', '重庆', '南川');
INSERT INTO `oc_packet_wechat_area`
VALUES ('28', '中国', '重庆', '铜梁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('29', '中国', '重庆', '大足');
INSERT INTO `oc_packet_wechat_area`
VALUES ('30', '中国', '重庆', '荣昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('31', '中国', '重庆', '璧山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('32', '中国', '重庆', '长寿');
INSERT INTO `oc_packet_wechat_area`
VALUES ('33', '中国', '重庆', '綦江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('34', '中国', '重庆', '潼南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('35', '中国', '重庆', '梁平');
INSERT INTO `oc_packet_wechat_area`
VALUES ('36', '中国', '重庆', '城口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('37', '中国', '重庆', '石柱');
INSERT INTO `oc_packet_wechat_area`
VALUES ('38', '中国', '重庆', '秀山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('39', '中国', '重庆', '万州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('40', '中国', '重庆', '渝中');
INSERT INTO `oc_packet_wechat_area`
VALUES ('41', '中国', '重庆', '涪陵');
INSERT INTO `oc_packet_wechat_area`
VALUES ('42', '中国', '重庆', '江北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('43', '中国', '重庆', '大渡口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('44', '中国', '重庆', '九龙坡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('45', '中国', '重庆', '沙坪坝');
INSERT INTO `oc_packet_wechat_area`
VALUES ('46', '中国', '重庆', '北碚');
INSERT INTO `oc_packet_wechat_area`
VALUES ('47', '中国', '重庆', '南岸');
INSERT INTO `oc_packet_wechat_area`
VALUES ('48', '中国', '重庆', '黔江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('49', '中国', '重庆', '巫溪');
INSERT INTO `oc_packet_wechat_area`
VALUES ('50', '中国', '重庆', '双桥');
INSERT INTO `oc_packet_wechat_area`
VALUES ('51', '中国', '重庆', '万盛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('52', '中国', '重庆', '巴南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('53', '中国', '重庆', '渝北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('54', '中国', '重庆', '忠县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('55', '中国', '重庆', '武隆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('56', '中国', '重庆', '垫江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('57', '中国', '重庆', '丰都');
INSERT INTO `oc_packet_wechat_area`
VALUES ('58', '中国', '重庆', '巫山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('59', '中国', '重庆', '奉节');
INSERT INTO `oc_packet_wechat_area`
VALUES ('60', '中国', '重庆', '云阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('61', '中国', '重庆', '开县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('62', '中国', '陕西', '商洛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('63', '中国', '陕西', '西安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('64', '中国', '陕西', '宝鸡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('65', '中国', '陕西', '铜川');
INSERT INTO `oc_packet_wechat_area`
VALUES ('66', '中国', '陕西', '渭南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('67', '中国', '陕西', '咸阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('68', '中国', '陕西', '汉中');
INSERT INTO `oc_packet_wechat_area`
VALUES ('69', '中国', '陕西', '延安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('70', '中国', '陕西', '安康');
INSERT INTO `oc_packet_wechat_area`
VALUES ('71', '中国', '陕西', '榆林');
INSERT INTO `oc_packet_wechat_area`
VALUES ('72', '中国', '甘肃', '定西');
INSERT INTO `oc_packet_wechat_area`
VALUES ('73', '中国', '甘肃', '庆阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('74', '中国', '甘肃', '陇南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('75', '中国', '甘肃', '甘南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('76', '中国', '甘肃', '临夏');
INSERT INTO `oc_packet_wechat_area`
VALUES ('77', '中国', '甘肃', '兰州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('78', '中国', '甘肃', '金昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('79', '中国', '甘肃', '嘉峪关');
INSERT INTO `oc_packet_wechat_area`
VALUES ('80', '中国', '甘肃', '天水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('81', '中国', '甘肃', '白银');
INSERT INTO `oc_packet_wechat_area`
VALUES ('82', '中国', '甘肃', '张掖');
INSERT INTO `oc_packet_wechat_area`
VALUES ('83', '中国', '甘肃', '武威');
INSERT INTO `oc_packet_wechat_area`
VALUES ('84', '中国', '甘肃', '酒泉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('85', '中国', '甘肃', '平凉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('86', '中国', '青海', '海南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('87', '中国', '青海', '果洛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('88', '中国', '青海', '玉树');
INSERT INTO `oc_packet_wechat_area`
VALUES ('89', '中国', '青海', '海东');
INSERT INTO `oc_packet_wechat_area`
VALUES ('90', '中国', '青海', '海北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('91', '中国', '青海', '黄南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('92', '中国', '青海', '海西');
INSERT INTO `oc_packet_wechat_area`
VALUES ('93', '中国', '青海', '西宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('94', '中国', '宁夏', '银川');
INSERT INTO `oc_packet_wechat_area`
VALUES ('95', '中国', '宁夏', '吴忠');
INSERT INTO `oc_packet_wechat_area`
VALUES ('96', '中国', '宁夏', '石嘴山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('97', '中国', '宁夏', '中卫');
INSERT INTO `oc_packet_wechat_area`
VALUES ('98', '中国', '宁夏', '固原');
INSERT INTO `oc_packet_wechat_area`
VALUES ('99', '中国', '云南', '红河');
INSERT INTO `oc_packet_wechat_area`
VALUES ('100', '中国', '云南', '文山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('101', '中国', '云南', '楚雄');
INSERT INTO `oc_packet_wechat_area`
VALUES ('102', '中国', '云南', '怒江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('103', '中国', '云南', '德宏');
INSERT INTO `oc_packet_wechat_area`
VALUES ('104', '中国', '云南', '西双版纳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('105', '中国', '云南', '大理');
INSERT INTO `oc_packet_wechat_area`
VALUES ('106', '中国', '云南', '迪庆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('107', '中国', '云南', '昆明');
INSERT INTO `oc_packet_wechat_area`
VALUES ('108', '中国', '云南', '曲靖');
INSERT INTO `oc_packet_wechat_area`
VALUES ('109', '中国', '云南', '保山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('110', '中国', '云南', '玉溪');
INSERT INTO `oc_packet_wechat_area`
VALUES ('111', '中国', '云南', '丽江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('112', '中国', '云南', '昭通');
INSERT INTO `oc_packet_wechat_area`
VALUES ('113', '中国', '云南', '临沧');
INSERT INTO `oc_packet_wechat_area`
VALUES ('114', '中国', '云南', '普洱');
INSERT INTO `oc_packet_wechat_area`
VALUES ('115', '中国', '澳门', '');
INSERT INTO `oc_packet_wechat_area`
VALUES ('116', '中国', '香港', '');
INSERT INTO `oc_packet_wechat_area`
VALUES ('117', '中国', '贵州', '毕节');
INSERT INTO `oc_packet_wechat_area`
VALUES ('118', '中国', '贵州', '黔东南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('119', '中国', '贵州', '黔南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('120', '中国', '贵州', '铜仁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('121', '中国', '贵州', '黔西南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('122', '中国', '贵州', '贵阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('123', '中国', '贵州', '遵义');
INSERT INTO `oc_packet_wechat_area`
VALUES ('124', '中国', '贵州', '六盘水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('125', '中国', '贵州', '安顺');
INSERT INTO `oc_packet_wechat_area`
VALUES ('126', '中国', '辽宁', '盘锦');
INSERT INTO `oc_packet_wechat_area`
VALUES ('127', '中国', '辽宁', '辽阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('128', '中国', '辽宁', '朝阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('129', '中国', '辽宁', '铁岭');
INSERT INTO `oc_packet_wechat_area`
VALUES ('130', '中国', '辽宁', '葫芦岛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('131', '中国', '辽宁', '沈阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('132', '中国', '辽宁', '鞍山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('133', '中国', '辽宁', '大连');
INSERT INTO `oc_packet_wechat_area`
VALUES ('134', '中国', '辽宁', '本溪');
INSERT INTO `oc_packet_wechat_area`
VALUES ('135', '中国', '辽宁', '抚顺');
INSERT INTO `oc_packet_wechat_area`
VALUES ('136', '中国', '辽宁', '锦州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('137', '中国', '辽宁', '丹东');
INSERT INTO `oc_packet_wechat_area`
VALUES ('138', '中国', '辽宁', '阜新');
INSERT INTO `oc_packet_wechat_area`
VALUES ('139', '中国', '辽宁', '营口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('140', '中国', '吉林', '延边');
INSERT INTO `oc_packet_wechat_area`
VALUES ('141', '中国', '吉林', '长春');
INSERT INTO `oc_packet_wechat_area`
VALUES ('142', '中国', '吉林', '四平');
INSERT INTO `oc_packet_wechat_area`
VALUES ('143', '中国', '吉林', '吉林');
INSERT INTO `oc_packet_wechat_area`
VALUES ('144', '中国', '吉林', '通化');
INSERT INTO `oc_packet_wechat_area`
VALUES ('145', '中国', '吉林', '辽源');
INSERT INTO `oc_packet_wechat_area`
VALUES ('146', '中国', '吉林', '松原');
INSERT INTO `oc_packet_wechat_area`
VALUES ('147', '中国', '吉林', '白山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('148', '中国', '吉林', '白城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('149', '中国', '黑龙江', '黑河');
INSERT INTO `oc_packet_wechat_area`
VALUES ('150', '中国', '黑龙江', '牡丹江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('151', '中国', '黑龙江', ' 绥化');
INSERT INTO `oc_packet_wechat_area`
VALUES ('152', '中国', '黑龙江', '哈尔滨');
INSERT INTO `oc_packet_wechat_area`
VALUES ('153', '中国', '黑龙江', '大兴安岭');
INSERT INTO `oc_packet_wechat_area`
VALUES ('154', '中国', '黑龙江', '鸡西');
INSERT INTO `oc_packet_wechat_area`
VALUES ('155', '中国', '黑龙江', '齐齐哈尔');
INSERT INTO `oc_packet_wechat_area`
VALUES ('156', '中国', '黑龙江', '双鸭山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('157', '中国', '黑龙江', '鹤岗');
INSERT INTO `oc_packet_wechat_area`
VALUES ('158', '中国', '黑龙江', '伊春');
INSERT INTO `oc_packet_wechat_area`
VALUES ('159', '中国', '黑龙江', '大庆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('160', '中国', '黑龙江', '七台河');
INSERT INTO `oc_packet_wechat_area`
VALUES ('161', '中国', '黑龙江', '佳木斯');
INSERT INTO `oc_packet_wechat_area`
VALUES ('162', '中国', '海南', '乐东');
INSERT INTO `oc_packet_wechat_area`
VALUES ('163', '中国', '海南', '昌江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('164', '中国', '海南', '白沙');
INSERT INTO `oc_packet_wechat_area`
VALUES ('165', '中国', '海南', '西沙');
INSERT INTO `oc_packet_wechat_area`
VALUES ('166', '中国', '海南', '琼中');
INSERT INTO `oc_packet_wechat_area`
VALUES ('167', '中国', '海南', '保亭');
INSERT INTO `oc_packet_wechat_area`
VALUES ('168', '中国', '海南', '陵水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('169', '中国', '海南', '中沙');
INSERT INTO `oc_packet_wechat_area`
VALUES ('170', '中国', '海南', '南沙');
INSERT INTO `oc_packet_wechat_area`
VALUES ('171', '中国', '海南', '海口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('172', '中国', '海南', '三亚');
INSERT INTO `oc_packet_wechat_area`
VALUES ('173', '中国', '海南', '五指山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('174', '中国', '海南', '儋州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('175', '中国', '海南', '琼海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('176', '中国', '海南', '文昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('177', '中国', '海南', '东方');
INSERT INTO `oc_packet_wechat_area`
VALUES ('178', '中国', '海南', '万宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('179', '中国', '海南', '定安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('180', '中国', '海南', '屯昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('181', '中国', '海南', '澄迈');
INSERT INTO `oc_packet_wechat_area`
VALUES ('182', '中国', '海南', '临高');
INSERT INTO `oc_packet_wechat_area`
VALUES ('183', '中国', '广东', '揭阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('184', '中国', '广东', '中山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('185', '中国', '广东', '广州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('186', '中国', '广东', '深圳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('187', '中国', '广东', '韶关');
INSERT INTO `oc_packet_wechat_area`
VALUES ('188', '中国', '广东', '汕头');
INSERT INTO `oc_packet_wechat_area`
VALUES ('189', '中国', '广东', '珠海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('190', '中国', '广东', '江门');
INSERT INTO `oc_packet_wechat_area`
VALUES ('191', '中国', '广东', '佛山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('192', '中国', '广东', '茂名');
INSERT INTO `oc_packet_wechat_area`
VALUES ('193', '中国', '广东', '湛江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('194', '中国', '广东', '惠州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('195', '中国', '广东', '肇庆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('196', '中国', '广东', '汕尾');
INSERT INTO `oc_packet_wechat_area`
VALUES ('197', '中国', '广东', '梅州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('198', '中国', '广东', '阳江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('199', '中国', '广东', '河源');
INSERT INTO `oc_packet_wechat_area`
VALUES ('200', '中国', '广东', '东莞');
INSERT INTO `oc_packet_wechat_area`
VALUES ('201', '中国', '广东', '清远');
INSERT INTO `oc_packet_wechat_area`
VALUES ('202', '中国', '广东', '潮州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('203', '中国', '广东', '云浮');
INSERT INTO `oc_packet_wechat_area`
VALUES ('204', '中国', '广西', '贺州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('205', '中国', '广西', '百色');
INSERT INTO `oc_packet_wechat_area`
VALUES ('206', '中国', '广西', '来宾');
INSERT INTO `oc_packet_wechat_area`
VALUES ('207', '中国', '广西', '河池');
INSERT INTO `oc_packet_wechat_area`
VALUES ('208', '中国', '广西', '崇左');
INSERT INTO `oc_packet_wechat_area`
VALUES ('209', '中国', '广西', '南宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('210', '中国', '广西', '桂林');
INSERT INTO `oc_packet_wechat_area`
VALUES ('211', '中国', '广西', '柳州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('212', '中国', '广西', '北海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('213', '中国', '广西', '梧州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('214', '中国', '广西', '钦州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('215', '中国', '广西', '防城港');
INSERT INTO `oc_packet_wechat_area`
VALUES ('216', '中国', '广西', '玉林');
INSERT INTO `oc_packet_wechat_area`
VALUES ('217', '中国', '广西', '贵港');
INSERT INTO `oc_packet_wechat_area`
VALUES ('218', '中国', '湖北', '黄冈');
INSERT INTO `oc_packet_wechat_area`
VALUES ('219', '中国', '湖北', '荆州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('220', '中国', '湖北', '随州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('221', '中国', '湖北', '咸宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('222', '中国', '湖北', '神农架');
INSERT INTO `oc_packet_wechat_area`
VALUES ('223', '中国', '湖北', '恩施');
INSERT INTO `oc_packet_wechat_area`
VALUES ('224', '中国', '湖北', '武汉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('225', '中国', '湖北', '十堰');
INSERT INTO `oc_packet_wechat_area`
VALUES ('226', '中国', '湖北', '黄石');
INSERT INTO `oc_packet_wechat_area`
VALUES ('227', '中国', '湖北', '宜昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('228', '中国', '湖北', '鄂州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('229', '中国', '湖北', '襄樊');
INSERT INTO `oc_packet_wechat_area`
VALUES ('230', '中国', '湖北', '孝感');
INSERT INTO `oc_packet_wechat_area`
VALUES ('231', '中国', '湖北', '荆门');
INSERT INTO `oc_packet_wechat_area`
VALUES ('232', '中国', '湖北', '潜江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('233', '中国', '湖北', '仙桃');
INSERT INTO `oc_packet_wechat_area`
VALUES ('234', '中国', '湖北', '天门');
INSERT INTO `oc_packet_wechat_area`
VALUES ('235', '中国', '湖南', '永州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('236', '中国', '湖南', '郴州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('237', '中国', '湖南', '娄底');
INSERT INTO `oc_packet_wechat_area`
VALUES ('238', '中国', '湖南', '怀化');
INSERT INTO `oc_packet_wechat_area`
VALUES ('239', '中国', '湖南', '湘西');
INSERT INTO `oc_packet_wechat_area`
VALUES ('240', '中国', '湖南', '长沙');
INSERT INTO `oc_packet_wechat_area`
VALUES ('241', '中国', '湖南', '湘潭');
INSERT INTO `oc_packet_wechat_area`
VALUES ('242', '中国', '湖南', '株洲');
INSERT INTO `oc_packet_wechat_area`
VALUES ('243', '中国', '湖南', '邵阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('244', '中国', '湖南', '衡阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('245', '中国', '湖南', '常德');
INSERT INTO `oc_packet_wechat_area`
VALUES ('246', '中国', '湖南', '岳阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('247', '中国', '湖南', '益阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('248', '中国', '湖南', '张家界');
INSERT INTO `oc_packet_wechat_area`
VALUES ('249', '中国', '河南', '漯河');
INSERT INTO `oc_packet_wechat_area`
VALUES ('250', '中国', '河南', '许昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('251', '中国', '河南', '南阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('252', '中国', '河南', '三门峡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('253', '中国', '河南', '信阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('254', '中国', '河南', '商丘');
INSERT INTO `oc_packet_wechat_area`
VALUES ('255', '中国', '河南', '驻马店');
INSERT INTO `oc_packet_wechat_area`
VALUES ('256', '中国', '河南', '周口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('257', '中国', '河南', '济源');
INSERT INTO `oc_packet_wechat_area`
VALUES ('258', '中国', '河南', '郑州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('259', '中国', '河南', '洛阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('260', '中国', '河南', '开封');
INSERT INTO `oc_packet_wechat_area`
VALUES ('261', '中国', '河南', '安阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('262', '中国', '河南', '平顶山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('263', '中国', '河南', '新乡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('264', '中国', '河南', '鹤壁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('265', '中国', '河南', '濮阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('266', '中国', '河南', '焦作');
INSERT INTO `oc_packet_wechat_area`
VALUES ('267', '中国', '台湾', '屏东县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('268', '中国', '台湾', '澎湖县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('269', '中国', '台湾', '台东县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('270', '中国', '台湾', '花莲县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('271', '中国', '台湾', '台北市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('272', '中国', '台湾', '基隆市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('273', '中国', '台湾', '高雄市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('274', '中国', '台湾', '台南市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('275', '中国', '台湾', '台中市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('276', '中国', '台湾', '嘉义市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('277', '中国', '台湾', '新竹市');
INSERT INTO `oc_packet_wechat_area`
VALUES ('278', '中国', '台湾', '宜兰县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('279', '中国', '台湾', '台北县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('280', '中国', '台湾', '新竹县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('281', '中国', '台湾', '桃园县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('282', '中国', '台湾', '台中县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('283', '中国', '台湾', '苗栗县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('284', '中国', '台湾', '南投县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('285', '中国', '台湾', '彰化县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('286', '中国', '台湾', '嘉义县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('287', '中国', '台湾', '云林县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('288', '中国', '台湾', '高雄县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('289', '中国', '台湾', '台南县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('290', '中国', '北京', '房山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('291', '中国', '北京', '大兴');
INSERT INTO `oc_packet_wechat_area`
VALUES ('292', '中国', '北京', '顺义');
INSERT INTO `oc_packet_wechat_area`
VALUES ('293', '中国', '北京', '通州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('294', '中国', '北京', '昌平');
INSERT INTO `oc_packet_wechat_area`
VALUES ('295', '中国', '北京', '密云');
INSERT INTO `oc_packet_wechat_area`
VALUES ('296', '中国', '北京', '平谷');
INSERT INTO `oc_packet_wechat_area`
VALUES ('297', '中国', '北京', '延庆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('298', '中国', '北京', '东城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('299', '中国', '北京', '怀柔');
INSERT INTO `oc_packet_wechat_area`
VALUES ('300', '中国', '北京', '崇文');
INSERT INTO `oc_packet_wechat_area`
VALUES ('301', '中国', '北京', '西城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('302', '中国', '北京', '朝阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('303', '中国', '北京', '宣武');
INSERT INTO `oc_packet_wechat_area`
VALUES ('304', '中国', '北京', '石景山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('305', '中国', '北京', '丰台');
INSERT INTO `oc_packet_wechat_area`
VALUES ('306', '中国', '北京', '门头沟');
INSERT INTO `oc_packet_wechat_area`
VALUES ('307', '中国', '北京', '海淀');
INSERT INTO `oc_packet_wechat_area`
VALUES ('308', '中国', '河北', '衡水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('309', '中国', '河北', '廊坊');
INSERT INTO `oc_packet_wechat_area`
VALUES ('310', '中国', '河北', '石家庄');
INSERT INTO `oc_packet_wechat_area`
VALUES ('311', '中国', '河北', '秦皇岛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('312', '中国', '河北', '唐山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('313', '中国', '河北', '邢台');
INSERT INTO `oc_packet_wechat_area`
VALUES ('314', '中国', '河北', '邯郸');
INSERT INTO `oc_packet_wechat_area`
VALUES ('315', '中国', '河北', '张家口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('316', '中国', '河北', '保定');
INSERT INTO `oc_packet_wechat_area`
VALUES ('317', '中国', '河北', '沧州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('318', '中国', '河北', '承德');
INSERT INTO `oc_packet_wechat_area`
VALUES ('319', '中国', '天津', '西青');
INSERT INTO `oc_packet_wechat_area`
VALUES ('320', '中国', '天津', '东丽');
INSERT INTO `oc_packet_wechat_area`
VALUES ('321', '中国', '天津', '北辰');
INSERT INTO `oc_packet_wechat_area`
VALUES ('322', '中国', '天津', '津南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('323', '中国', '天津', '宁河');
INSERT INTO `oc_packet_wechat_area`
VALUES ('324', '中国', '天津', '武清');
INSERT INTO `oc_packet_wechat_area`
VALUES ('325', '中国', '天津', '静海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('326', '中国', '天津', '宝坻');
INSERT INTO `oc_packet_wechat_area`
VALUES ('327', '中国', '天津', '和平');
INSERT INTO `oc_packet_wechat_area`
VALUES ('328', '中国', '天津', '河西');
INSERT INTO `oc_packet_wechat_area`
VALUES ('329', '中国', '天津', '河东');
INSERT INTO `oc_packet_wechat_area`
VALUES ('330', '中国', '天津', '河北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('331', '中国', '天津', '南开');
INSERT INTO `oc_packet_wechat_area`
VALUES ('332', '中国', '天津', '塘沽');
INSERT INTO `oc_packet_wechat_area`
VALUES ('333', '中国', '天津', '红桥');
INSERT INTO `oc_packet_wechat_area`
VALUES ('334', '中国', '天津', '大港');
INSERT INTO `oc_packet_wechat_area`
VALUES ('335', '中国', '天津', '汉沽');
INSERT INTO `oc_packet_wechat_area`
VALUES ('336', '中国', '天津', '蓟县');
INSERT INTO `oc_packet_wechat_area`
VALUES ('337', '中国', '内蒙古', '锡林郭勒');
INSERT INTO `oc_packet_wechat_area`
VALUES ('338', '中国', '内蒙古', '兴安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('339', '中国', '内蒙古', '阿拉善');
INSERT INTO `oc_packet_wechat_area`
VALUES ('340', '中国', '内蒙古', '呼和浩特');
INSERT INTO `oc_packet_wechat_area`
VALUES ('341', '中国', '内蒙古', '乌海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('342', '中国', '内蒙古', '包头');
INSERT INTO `oc_packet_wechat_area`
VALUES ('343', '中国', '内蒙古', '通辽');
INSERT INTO `oc_packet_wechat_area`
VALUES ('344', '中国', '内蒙古', '赤峰');
INSERT INTO `oc_packet_wechat_area`
VALUES ('345', '中国', '内蒙古', '呼伦贝尔');
INSERT INTO `oc_packet_wechat_area`
VALUES ('346', '中国', '内蒙古', '鄂尔多斯');
INSERT INTO `oc_packet_wechat_area`
VALUES ('347', '中国', '内蒙古', '乌兰察布');
INSERT INTO `oc_packet_wechat_area`
VALUES ('348', '中国', '内蒙古', '巴彦淖尔');
INSERT INTO `oc_packet_wechat_area`
VALUES ('349', '中国', '山西', '吕梁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('350', '中国', '山西', '临汾');
INSERT INTO `oc_packet_wechat_area`
VALUES ('351', '中国', '山西', '太原');
INSERT INTO `oc_packet_wechat_area`
VALUES ('352', '中国', '山西', '阳泉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('353', '中国', '山西', '大同');
INSERT INTO `oc_packet_wechat_area`
VALUES ('354', '中国', '山西', '晋城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('355', '中国', '山西', '长治');
INSERT INTO `oc_packet_wechat_area`
VALUES ('356', '中国', '山西', '晋中');
INSERT INTO `oc_packet_wechat_area`
VALUES ('357', '中国', '山西', '朔州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('358', '中国', '山西', '忻州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('359', '中国', '山西', '运城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('360', '中国', '浙江', '丽水');
INSERT INTO `oc_packet_wechat_area`
VALUES ('361', '中国', '浙江', '台州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('362', '中国', '浙江', '杭州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('363', '中国', '浙江', '温州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('364', '中国', '浙江', '宁波');
INSERT INTO `oc_packet_wechat_area`
VALUES ('365', '中国', '浙江', '湖州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('366', '中国', '浙江', '嘉兴');
INSERT INTO `oc_packet_wechat_area`
VALUES ('367', '中国', '浙江', '金华');
INSERT INTO `oc_packet_wechat_area`
VALUES ('368', '中国', '浙江', '绍兴');
INSERT INTO `oc_packet_wechat_area`
VALUES ('369', '中国', '浙江', '舟山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('370', '中国', '浙江', '衢州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('371', '中国', '江苏', '镇江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('372', '中国', '江苏', '扬州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('373', '中国', '江苏', '宿迁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('374', '中国', '江苏', '泰州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('375', '中国', '江苏', '南京');
INSERT INTO `oc_packet_wechat_area`
VALUES ('376', '中国', '江苏', '徐州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('377', '中国', '江苏', '无锡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('378', '中国', '江苏', '苏州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('379', '中国', '江苏', '常州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('380', '中国', '江苏', '连云港');
INSERT INTO `oc_packet_wechat_area`
VALUES ('381', '中国', '江苏', '南通');
INSERT INTO `oc_packet_wechat_area`
VALUES ('382', '中国', '江苏', '盐城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('383', '中国', '江苏', '淮安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('384', '中国', '上海', '杨浦');
INSERT INTO `oc_packet_wechat_area`
VALUES ('385', '中国', '上海', '南汇');
INSERT INTO `oc_packet_wechat_area`
VALUES ('386', '中国', '上海', '宝山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('387', '中国', '上海', '闵行');
INSERT INTO `oc_packet_wechat_area`
VALUES ('388', '中国', '上海', '浦东新');
INSERT INTO `oc_packet_wechat_area`
VALUES ('389', '中国', '上海', '嘉定');
INSERT INTO `oc_packet_wechat_area`
VALUES ('390', '中国', '上海', '松江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('391', '中国', '上海', '金山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('392', '中国', '上海', '崇明');
INSERT INTO `oc_packet_wechat_area`
VALUES ('393', '中国', '上海', '奉贤');
INSERT INTO `oc_packet_wechat_area`
VALUES ('394', '中国', '上海', '青浦');
INSERT INTO `oc_packet_wechat_area`
VALUES ('395', '中国', '上海', '黄浦');
INSERT INTO `oc_packet_wechat_area`
VALUES ('396', '中国', '上海', '卢湾');
INSERT INTO `oc_packet_wechat_area`
VALUES ('397', '中国', '上海', '长宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('398', '中国', '上海', '徐汇');
INSERT INTO `oc_packet_wechat_area`
VALUES ('399', '中国', '上海', '普陀');
INSERT INTO `oc_packet_wechat_area`
VALUES ('400', '中国', '上海', '静安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('401', '中国', '上海', '虹口');
INSERT INTO `oc_packet_wechat_area`
VALUES ('402', '中国', '上海', '闸北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('403', '中国', '山东', '日照');
INSERT INTO `oc_packet_wechat_area`
VALUES ('404', '中国', '山东', '威海');
INSERT INTO `oc_packet_wechat_area`
VALUES ('405', '中国', '山东', '临沂');
INSERT INTO `oc_packet_wechat_area`
VALUES ('406', '中国', '山东', '莱芜');
INSERT INTO `oc_packet_wechat_area`
VALUES ('407', '中国', '山东', '聊城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('408', '中国', '山东', '德州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('409', '中国', '山东', '菏泽');
INSERT INTO `oc_packet_wechat_area`
VALUES ('410', '中国', '山东', '滨州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('411', '中国', '山东', '济南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('412', '中国', '山东', '淄博');
INSERT INTO `oc_packet_wechat_area`
VALUES ('413', '中国', '山东', '青岛');
INSERT INTO `oc_packet_wechat_area`
VALUES ('414', '中国', '山东', '东营');
INSERT INTO `oc_packet_wechat_area`
VALUES ('415', '中国', '山东', '枣庄');
INSERT INTO `oc_packet_wechat_area`
VALUES ('416', '中国', '山东', '潍坊');
INSERT INTO `oc_packet_wechat_area`
VALUES ('417', '中国', '山东', '烟台');
INSERT INTO `oc_packet_wechat_area`
VALUES ('418', '中国', '山东', '泰安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('419', '中国', '山东', '济宁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('420', '中国', '江西', '上饶');
INSERT INTO `oc_packet_wechat_area`
VALUES ('421', '中国', '江西', '抚州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('422', '中国', '江西', '南昌');
INSERT INTO `oc_packet_wechat_area`
VALUES ('423', '中国', '江西', '萍乡');
INSERT INTO `oc_packet_wechat_area`
VALUES ('424', '中国', '江西', '景德镇');
INSERT INTO `oc_packet_wechat_area`
VALUES ('425', '中国', '江西', '新余');
INSERT INTO `oc_packet_wechat_area`
VALUES ('426', '中国', '江西', '九江');
INSERT INTO `oc_packet_wechat_area`
VALUES ('427', '中国', '江西', '赣州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('428', '中国', '江西', '鹰潭');
INSERT INTO `oc_packet_wechat_area`
VALUES ('429', '中国', '江西', '宜春');
INSERT INTO `oc_packet_wechat_area`
VALUES ('430', '中国', '江西', '吉安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('431', '中国', '福建', '福州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('432', '中国', '福建', '莆田');
INSERT INTO `oc_packet_wechat_area`
VALUES ('433', '中国', '福建', '厦门');
INSERT INTO `oc_packet_wechat_area`
VALUES ('434', '中国', '福建', '泉州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('435', '中国', '福建', '三明');
INSERT INTO `oc_packet_wechat_area`
VALUES ('436', '中国', '福建', '南平');
INSERT INTO `oc_packet_wechat_area`
VALUES ('437', '中国', '福建', '漳州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('438', '中国', '福建', '宁德');
INSERT INTO `oc_packet_wechat_area`
VALUES ('439', '中国', '福建', '龙岩');
INSERT INTO `oc_packet_wechat_area`
VALUES ('440', '中国', '安徽', '滁州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('441', '中国', '安徽', '黄山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('442', '中国', '安徽', '宿州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('443', '中国', '安徽', '阜阳');
INSERT INTO `oc_packet_wechat_area`
VALUES ('444', '中国', '安徽', '六安');
INSERT INTO `oc_packet_wechat_area`
VALUES ('445', '中国', '安徽', '巢湖');
INSERT INTO `oc_packet_wechat_area`
VALUES ('446', '中国', '安徽', '池州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('447', '中国', '安徽', '亳州');
INSERT INTO `oc_packet_wechat_area`
VALUES ('448', '中国', '安徽', '宣城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('449', '中国', '安徽', '合肥');
INSERT INTO `oc_packet_wechat_area`
VALUES ('450', '中国', '安徽', '蚌埠');
INSERT INTO `oc_packet_wechat_area`
VALUES ('451', '中国', '安徽', '芜湖');
INSERT INTO `oc_packet_wechat_area`
VALUES ('452', '中国', '安徽', '马鞍山');
INSERT INTO `oc_packet_wechat_area`
VALUES ('453', '中国', '安徽', '淮南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('454', '中国', '安徽', '铜陵');
INSERT INTO `oc_packet_wechat_area`
VALUES ('455', '中国', '安徽', '淮北');
INSERT INTO `oc_packet_wechat_area`
VALUES ('456', '中国', '安徽', '安庆');
INSERT INTO `oc_packet_wechat_area`
VALUES ('457', '中国', '西藏', '那曲');
INSERT INTO `oc_packet_wechat_area`
VALUES ('458', '中国', '西藏', '阿里');
INSERT INTO `oc_packet_wechat_area`
VALUES ('459', '中国', '西藏', '林芝');
INSERT INTO `oc_packet_wechat_area`
VALUES ('460', '中国', '西藏', '昌都');
INSERT INTO `oc_packet_wechat_area`
VALUES ('461', '中国', '西藏', '山南');
INSERT INTO `oc_packet_wechat_area`
VALUES ('462', '中国', '西藏', '日喀则');
INSERT INTO `oc_packet_wechat_area`
VALUES ('463', '中国', '西藏', '拉萨');
INSERT INTO `oc_packet_wechat_area`
VALUES ('464', '中国', '新疆', '博尔塔拉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('465', '中国', '新疆', '吐鲁番');
INSERT INTO `oc_packet_wechat_area`
VALUES ('466', '中国', '新疆', '哈密');
INSERT INTO `oc_packet_wechat_area`
VALUES ('467', '中国', '新疆', '昌吉');
INSERT INTO `oc_packet_wechat_area`
VALUES ('468', '中国', '新疆', '和田');
INSERT INTO `oc_packet_wechat_area`
VALUES ('469', '中国', '新疆', '喀什');
INSERT INTO `oc_packet_wechat_area`
VALUES ('470', '中国', '新疆', '克孜勒苏');
INSERT INTO `oc_packet_wechat_area`
VALUES ('471', '中国', '新疆', '巴音郭楞');
INSERT INTO `oc_packet_wechat_area`
VALUES ('472', '中国', '新疆', '阿克苏');
INSERT INTO `oc_packet_wechat_area`
VALUES ('473', '中国', '新疆', '伊犁');
INSERT INTO `oc_packet_wechat_area`
VALUES ('474', '中国', '新疆', '塔城');
INSERT INTO `oc_packet_wechat_area`
VALUES ('475', '中国', '新疆', '乌鲁木齐');
INSERT INTO `oc_packet_wechat_area`
VALUES ('476', '中国', '新疆', '阿勒泰');
INSERT INTO `oc_packet_wechat_area`
VALUES ('477', '中国', '新疆', '克拉玛依');
INSERT INTO `oc_packet_wechat_area`
VALUES ('478', '中国', '新疆', '石河子');
INSERT INTO `oc_packet_wechat_area`
VALUES ('479', '中国', '新疆', '图木舒克');
INSERT INTO `oc_packet_wechat_area`
VALUES ('480', '中国', '新疆', '阿拉尔');
INSERT INTO `oc_packet_wechat_area`
VALUES ('481', '中国', '新疆', '五家渠');
