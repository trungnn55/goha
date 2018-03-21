-- phpMyAdmin SQL Dump
-- version 4.7.7
-- https://www.phpmyadmin.net/
--
-- Máy chủ: localhost:3306
-- Thời gian đã tạo: Th3 20, 2018 lúc 12:06 AM
-- Phiên bản máy phục vụ: 5.6.39-log
-- Phiên bản PHP: 5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gh120401_ghprod`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cscart_users`
--

CREATE TABLE `cscart_users` (
  `user_id` mediumint(8) UNSIGNED NOT NULL,
  `status` char(1) NOT NULL DEFAULT 'A',
  `user_type` char(1) NOT NULL DEFAULT 'C',
  `user_login` varchar(255) NOT NULL DEFAULT '',
  `referer` varchar(255) NOT NULL DEFAULT '',
  `is_root` char(1) NOT NULL DEFAULT 'N',
  `company_id` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `last_login` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `timestamp` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `password` varchar(32) NOT NULL DEFAULT '',
  `salt` varchar(10) NOT NULL DEFAULT '',
  `firstname` varchar(128) NOT NULL DEFAULT '',
  `lastname` varchar(128) NOT NULL DEFAULT '',
  `company` varchar(255) NOT NULL DEFAULT '',
  `email` varchar(128) NOT NULL DEFAULT '',
  `phone` varchar(32) NOT NULL DEFAULT '',
  `fax` varchar(32) NOT NULL DEFAULT '',
  `url` varchar(128) NOT NULL DEFAULT '',
  `tax_exempt` char(1) NOT NULL DEFAULT 'N',
  `lang_code` char(2) NOT NULL DEFAULT '',
  `birthday` int(11) NOT NULL DEFAULT '0',
  `purchase_timestamp_from` int(11) NOT NULL DEFAULT '0',
  `purchase_timestamp_to` int(11) NOT NULL DEFAULT '0',
  `responsible_email` varchar(80) NOT NULL DEFAULT '',
  `last_passwords` varchar(255) NOT NULL DEFAULT '',
  `password_change_timestamp` int(11) UNSIGNED NOT NULL DEFAULT '0',
  `api_key` varchar(32) NOT NULL DEFAULT ''
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

--
-- Đang đổ dữ liệu cho bảng `cscart_users`
--

INSERT INTO `cscart_users` (`user_id`, `status`, `user_type`, `user_login`, `referer`, `is_root`, `company_id`, `last_login`, `timestamp`, `password`, `salt`, `firstname`, `lastname`, `company`, `email`, `phone`, `fax`, `url`, `tax_exempt`, `lang_code`, `birthday`, `purchase_timestamp_from`, `purchase_timestamp_to`, `responsible_email`, `last_passwords`, `password_change_timestamp`, `api_key`) VALUES
(7, 'A', 'V', 'info.lucky.style@gmail.com', '', 'Y', 6, 1521463530, 1512405781, '72aec61b4cabce714e27b7ef04c903d0', '|_|*YZl{J%', '庄司', '奈々', '合同会社 LUCKY STYLE', 'time.travel.japan2018@gmail.com', '07066971245', '', '', 'N', 'ja', 0, 0, 0, '', '321be6e460e6f2e9e44d03f518b188e2', 1521385992, ''),
(1, 'A', 'A', 'admin', '', 'Y', 0, 1521440539, 1512349028, '58cb20c65ec65553e62e37bb61164eb8', 'RZ4X&?ZG<j', '草野', '清', 'Your company', 'info.ecin.2769@gmail.com', '090-9238-6706', '', '', 'N', 'ja', 0, 0, 0, '', '', 0, ''),
(6, 'A', 'V', 'otemoto9@gmail.com', '', 'Y', 2, 1514006735, 1512375370, 'b6d4ea49c36dac6f93f3eb86638a68e8', 'B7`@=#pY[\"', '伊藤', '健太郎', 'ecosura', 'info.frmdrop@gmail.com', '07014811129', '', '', 'N', 'ja', 0, 0, 0, '', 'aa40c5a3c9bddf2a43dc78dad1ecc7d6', 1512406841, ''),
(8, 'A', 'V', 'kitakaze1045@gmail.com', '', 'Y', 7, 1521378435, 1512405972, 'ba0d056a129101bd11bc932dc71a6109', 'w@P&co24/4', '石田', '真大', 'Four and half', 'kitakaze1045@gmail.com', '05035690999', '', '', 'N', 'ja', 0, 0, 0, '', 'cde886cf65ed30d538b8c2f081768204', 1512407245, ''),
(9, 'A', 'V', 'g.a.n.lesseps@gmail.com', '', 'Y', 5, 1512407341, 1512405972, 'b6fbee6e3a5493f02a6d89c628a158be', '*#/Mo::|no', '藤本', '裕紀', 'LESSEPS', 'g.a.n.lesseps@gmail.com', '09085290998', '', '', 'N', 'ja', 0, 0, 0, '', '4bf0fdf066b1baff57e7a360f051aa6f', 1512407282, ''),
(10, 'A', 'V', 'guririn823@gmail.com', '', 'Y', 4, 1521464480, 1512405972, '01c7436d5e129e759cf2f08c3ec31d0b', 'pFwXs(QL>q', '笹栗', '啓太', 'しーど', 'guririn823@gmail.com', '09089039431', '', '', 'N', 'ja', 0, 0, 0, '', '326943744d81ac9eab0b6ee580d3a538', 1512477718, ''),
(11, 'A', 'C', 'user_11', '', 'N', 0, 1520525845, 1512408161, '62f1e071f2a9ee0308a9368a30a70e53', 'mPNl0*k&Jx', '草野', '清', '', 'farid.kk.1@icloud.com', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(12, 'A', 'V', 'c33.maniatis@gmail.com', '', 'Y', 8, 1512916816, 1512461376, 'f75ff6d5733f904f201659771c338deb', 'ql+)t\',*CL', '宮内', '則之', 'maniatis', 'c33.maniatis@gmail.com', '09053004166', '', '', 'N', 'ja', 0, 0, 0, '', 'ab4b8b20caf6f6ab2cbc15b79780a776', 1512548083, ''),
(13, 'A', 'V', 'lllmskzlll@gmail.com', '', 'Y', 9, 1512574440, 1512461381, 'fa18e8451cfb8f2ac8c8f84d847293dd', 'bgc=.zM.cw', '後藤', '雅和', 'MGグローバル', 'lllmskzlll@gmail.com', '07056491869', '', '', 'N', 'ja', 0, 0, 0, '', 'a6c544d29d1811a25ad6c7c54e83b624', 1512548715, ''),
(14, 'A', 'V', 'siva3367@yahoo.co.jp', '', 'Y', 10, 0, 1512461385, '7515903e8176dddc0ddc67d29d541b4f', '\\=j>dgW1X8', '栗山', '直矢', '栗山 直矢', 'siva3367@yahoo.co.jp', '08024337429', '', '', 'N', 'ja', 0, 0, 0, '', '0950e7f497d128314a32517fd70436f2', 1512548286, ''),
(15, 'A', 'V', 'shinmmm3939@ezweb.ne.jp', '', 'Y', 11, 1512683232, 1512461390, '33cf84fa3b9774e70e3d37de1fd08734', 'b/gx1P_CjI', '岩元', '真一郎', '株式会社ryフタール', 'shinmmm3939@ezweb.ne.jp', '09059034951', '', '', 'N', 'ja', 0, 0, 0, '', '23953122e40fa6a20240a77a63f8a23c', 1512548435, ''),
(17, 'A', 'V', 'dragstar0570@gmail.com', '', 'Y', 12, 1512969810, 1512547283, '993d212301e4ceb4e2710eb46c07ca11', 'd^T\'Uo7tMU', '平田', '光', 'mu-mu-inport', 'dragstar0570@gmail.com', '09052356070', '', '', 'N', 'ja', 0, 0, 0, '', 'debe14e9bf963160cfadde06ec3ebfee', 1512547869, ''),
(19, 'A', 'V', 'payapaya02@gmail.com', '', 'Y', 15, 1513227963, 1512828174, '5fbe470c5b878fa5db81674ad2bc83b2', '/*V^yMesWI', '飯村', '隼人', '銀座シュシュ', 'payapaya02@gmail.com', '09090136372', '', '', 'N', 'ja', 0, 0, 0, '', '26dabf76eb2719453d933ee431595623', 1512828304, ''),
(20, 'A', 'V', 'mari-mari-mari@hotmail.co.jp', '', 'Y', 16, 1517124264, 1512828605, '5ca23ec5de3bb5e50741493cc10f5bf8', 'R:$..5e$|m', '平山', 'マリコ', 'marion', 'mari-mari-mari@hotmail.co.jp', '08036056025', '', '', 'N', 'ja', 0, 0, 0, '', '526ed91248e7af1128e7262011c58b0f', 1513131678, ''),
(41, 'A', 'C', 'user_41', '', 'N', 0, 1518691214, 1518691214, 'b1f0ed0587b5df900b550c5e023e1dd7', 'SR9,JBq*KP', '', '', '', 'soedakenya@gmail.com', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(21, 'A', 'V', 'sag.frontal.1207@gmail.com', '', 'Y', 14, 1513770997, 1512828865, 'a1c22dca5256bedad0cac24818210484', '-Z~&<dZTI+', '佐口', '朋彦', 'シンタケシ', 'sag.frontal.1207@gmail.com', '09035175252', '', '', 'N', 'ja', 0, 0, 0, '', '3c92f456cdff53b9146357bee44ce73a', 1512957866, ''),
(42, 'A', 'A', 'user_42', '', 'N', 0, 1521251833, 1519175720, '59f34f55f03fee5908904e268df890eb', 'GEe6Mz[&E.', 'デザイン', 'クラウド', '', 'zackyvalley@gmail.com', '000000', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(23, 'A', 'V', 'kakugari.com@gmail.com', '', 'Y', 19, 1519275883, 1512990372, '0f9aaa4ccc58b817cedbb4fdbe3101ac', 'tM0Rw2;<D5', '須藤', '勇貴', 'Gumer', 'kakugari.com@gmail.com', '09087221854', '', '', 'N', 'ja', 0, 0, 0, '', '842f1e8592854fbc0b815870a88786c2', 1512990510, ''),
(24, 'A', 'V', 'nogic.582@gmail.com', '', 'Y', 21, 1521461129, 1513131572, '96ee2c1c6aaf7c3d96978de55b6f7293', '0uAZh_f/|\'', '中野', '太貴', 'free', 'nogic.582@gmail.com', '080-8581-6169', '', '', 'N', 'ja', 0, 0, 0, '', '2ab0f42849b6c3794bfd896066380267', 1513137158, ''),
(25, 'A', 'V', 'niji.iro.sky.market@gmail.com', '', 'Y', 22, 0, 1513131581, 'f845063a27a3b8faef95a44eb55c2591', 's^;8K\'~R%E', '小林', '虹州', '虹いろスカイマーケット(nsm)', 'niji.iro.sky.market@gmail.com', '09045280979', '', '', 'N', 'ja', 0, 0, 0, '', '', 0, ''),
(26, 'A', 'V', 'user_26', '', 'N', 23, 1521185467, 1513240136, '0f880348e298a63e7bdfb33f3dbd4b07', 'L=%#*~Da#K', '新明', '研人', '', 'skgp123456@gmail.com', '09095402103', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(27, 'A', 'V', 'kubo.toiawase@gmail.com', '', 'Y', 20, 1514116024, 1513302984, 'bd736f2ddb293b70d344f3194fb73cb8', 'A(>I@)&.VV', '久保', '祥太', 'Hoooo！', 'kubo.toiawase@gmail.com', '08067701723', '', '', 'N', 'ja', 0, 0, 0, '', 'd75cb7910f7e0d0ca81d266490b0b968', 1513303970, ''),
(28, 'A', 'V', 'mack401419@gmail.com', '', 'Y', 24, 1515254464, 1513302999, '77c81e5f23a16448fd2dafb9c9961266', '@wY_f}epT!', '加嶋', '亮平', 'カシオメンショップ', 'mack401419@gmail.com', '09023754088', '', '', 'N', 'ja', 0, 0, 0, '', '0c3bf4494a044006a9fdb732b2f60def', 1513303334, ''),
(29, 'A', 'C', 'user_29', '', 'N', 0, 1513652911, 1513652911, '68237373cb6c8872851fbaf88c1c97e6', 'iAQ44ARQAJ', 'テスト', '太郎', '', 'tommy@cs-cart.jp', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(30, 'A', 'C', 'user_30', '', 'N', 0, 1513653302, 1513653302, '12f471fde5e5638f38ad0f917c58dd02', 'S,2o@5PVHV', 'テスト', '次郎', '', 'test@cs-cart.jp', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(31, 'A', 'C', 'user_31', '', 'N', 0, 1513653400, 1513653400, 'a8c83d390243fcd89058c98d9494a43a', ';\\/MH2C=xI', 'テスト', 'テスト', '', 'takahashi1@cs-cart.jp', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(32, 'A', 'A', 'user_32', '', 'N', 0, 1521361546, 1513666085, 'c741a62aca297d6d374928141c9b54dd', 'vKsJk~&x?M', 'クラウド', 'ソーシング', '', 'okb.ecin.2769@gmail.com', '00000000000', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, '3416MX498BjJVa94z2OBw3YWXi0f0V72'),
(33, 'A', 'C', 'user_33', '', 'N', 0, 1513819209, 1513819209, '885acf0ddadfe3296c41e0b770102ac3', 'EvfThQA;6:', 'CS', 'CART', '', 'yoshiura@frogman.co.jp', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(43, 'A', 'C', 'user_43', '', 'N', 0, 1521362368, 1519829105, '0e54ec6c622077a6eed0e627d5cdace9', 'g%*b>clVn.', 'dsfafda', 'Anh', '', 'anhnc92@gmail.com', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(44, 'A', 'V', 'fujinami@ryohin-kikaku.jp', '', 'Y', 32, 0, 1520321263, '9303a54838bd84f18d7fd6c17c133475', '.X\\>;=e+[/', '藤波', '智之', 'R_Planning', 'fujinami@ryohin-kikaku.jp', '050-5534-1705', '', '', 'N', 'ja', 0, 0, 0, '', '', 0, ''),
(35, 'A', 'V', 'user_35', '', 'N', 26, 1516022810, 1514067664, '21f3c1f4db3c2cb33b6cd051e5e9fa26', ')vDO;#P8>A', '', '', '', 'yoshi03970@gmail.com', '', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(36, 'A', 'C', 'user_36', '', 'N', 0, 1520522386, 1514088750, '4e78e53d2845835d94c2374696852d1d', '9-q~\\|_F4k', 'aa', 'a', '', 'tccsoft.vn@gmail.com', '22222', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(37, 'A', 'V', 'tcc@gmail.com', '', 'Y', 29, 1521303192, 1514742208, '20a5985259d1b60328658d6ad1b2198f', 'g*u7`pnJ=r', 'トラン', 'トラン', 'tcc', 'transontungtdtk@gmail.com', '08000000000', '', '', 'N', 'ja', 0, 0, 0, '', '8b245d642c58b46c51903c433a812c46', 1514742377, ''),
(38, 'A', 'V', 'user_38', '', 'Y', 31, 1520082632, 1515804929, 'd15010e5f5dc70e5ac2a790d851018b0', '[4TDr>QB!d', '関根', '秀則', '', 'kohide555@gmail.com', '09025570688', '', '', 'N', 'ja', 0, 0, 0, '', '', 1519004491, ''),
(39, 'A', 'V', 'fp_ootani@yahoo.co.jp', '', 'Y', 30, 1517883326, 1516275328, '98abb84c7f6b7e5c638804575f4017ed', 'h;=3}#p5i+', '大谷', '剛史', 'TOショップ', 'fp_ootani@yahoo.co.jp', '0747223696', '0747223695', '', 'N', 'ja', 0, 0, 0, '', '5e6138b0aa9ede2cff1087cfe3bb58b5', 1516275408, ''),
(40, 'A', 'C', 'user_40', '', 'N', 0, 1519100431, 1518080301, 'de43bbbc7836cb24748a84e6ee6de669', 'TyU;x^U]qR', '伊藤', '健太郎', '', 'otemoto9@gmail.com', '07014811129', '', '', 'N', 'ja', 0, 0, 0, '', '', 1, ''),
(45, 'A', 'V', 'kamata_jr@yahoo.co.jp', '', 'Y', 33, 0, 1520321283, 'b0ce9756b69b4f8bcc36d989ff273b98', '43T2H8ps0v', '坂井', '智剛', 'Eternal Flame', 'kamata_jr@yahoo.co.jp', '090-3015-8316', '', '', 'N', 'ja', 0, 0, 0, '', '', 0, ''),
(46, 'A', 'C', 'user_46', '', 'N', 0, 1520345048, 1520345048, 'b09532142e0d213ae3d197903a1580dc', 'a{da%P%GIk', '', '', '', 'sontunghedspi2802@gmail.com', '', '', '', 'N', 'ja', 574009200, 0, 0, '', '', 1, ''),
(47, 'A', 'C', 'user_47', '', 'N', 0, 1521471395, 1521471395, '2706d31ffe8648932b91c3eafc092971', 'D@P$Xl-g$y', '', '', '', 'trung.nn.92@gmail.com', '', '', '', 'N', 'ja', 1118242800, 0, 0, '', '', 1, '');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `cscart_users`
--
ALTER TABLE `cscart_users`
  ADD PRIMARY KEY (`user_id`),
  ADD KEY `user_login` (`user_login`),
  ADD KEY `uname` (`firstname`,`lastname`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `cscart_users`
--
ALTER TABLE `cscart_users`
  MODIFY `user_id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
