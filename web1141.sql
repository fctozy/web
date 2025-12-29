-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： localhost
-- 產生時間： 2025 年 12 月 16 日 06:42
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `web1141`
--

-- --------------------------------------------------------

--
-- 資料表結構 `expense`
--

CREATE TABLE `expense` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `expense`
--

INSERT INTO `expense` (`id`, `name`, `category`, `amount`, `date`, `description`) VALUES
(1, '場地租借', '場地費用', 8000.00, '2025-09-10', '迎新晚會場地費'),
(2, '餐飲採購', '餐飲費用', 12000.00, '2025-09-14', '迎新晚會餐點'),
(3, '宣傳物料', '行政支出', 3500.00, '2025-09-05', '海報、傳單印刷'),
(4, '器材租借', '設備費用', 5000.00, '2025-09-12', '音響燈光設備'),
(5, '交通費用', '場地費用', 2500.00, '2025-10-14', '春季郊遊遊覽車'),
(6, '保險費用', '行政支出', 1800.00, '2025-10-13', '活動保險'),
(7, '獎品採購', '物品採購', 4500.00, '2025-10-08', '系運動會獎品'),
(8, '場地布置', '場地費用', 3000.00, '2025-11-18', '聖誕晚會裝飾'),
(9, '表演費用', '設備費用', 15000.00, '2025-11-03', '才藝表演嘉賓費'),
(10, '攝影費用', '設備費用', 6000.00, '2025-11-19', '活動攝影師'),
(11, '印刷費用', '行政支出', 2000.00, '2025-09-28', '社團資料印刷'),
(12, '網站維護', '行政支出', 3600.00, '2025-10-01', '年度網站維護費'),
(13, '清潔費用', '場地費用', 1500.00, '2025-11-21', '場地清潔'),
(14, '禮品採購', '物品採購', 8500.00, '2025-11-27', '校慶紀念品'),
(15, '飲料採購', '餐飲費用', 4200.00, '2025-06-14', '畢業晚會飲料'),
(16, '舞台搭建', '場地費用', 9000.00, '2025-11-10', '大型舞台搭建'),
(17, '便當訂購', '餐飲費用', 6500.00, '2025-10-20', '工作人員餐點'),
(18, '音響設備', '設備費用', 7500.00, '2025-09-20', '專業音響租借'),
(19, '文具用品', '物品採購', 1200.00, '2025-09-15', '辦公文具'),
(20, '郵寄費用', '行政支出', 800.00, '2025-10-05', '通知信郵寄'),
(21, '宿營', '場地費用', 10000.00, '2025-12-16', '紫森林破場地');

-- --------------------------------------------------------

--
-- 資料表結構 `income`
--

CREATE TABLE `income` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `category` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `date` date NOT NULL,
  `description` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `income`
--

INSERT INTO `income` (`id`, `name`, `category`, `amount`, `date`, `description`) VALUES
(1, '迎新晚會', '活動收入', 15000.00, '2025-09-15', '門票收入'),
(2, '學期會費', '會費', 10000.00, '2025-09-01', '上學期會費'),
(3, '系運動會', '活動收入', 8000.00, '2025-10-10', '報名費'),
(4, '企業贊助', '贊助', 20000.00, '2025-09-20', 'ABC企業贊助'),
(5, '社團博覽會', '活動收入', 6000.00, '2025-09-25', '攤位收入'),
(6, '春季郊遊', '活動收入', 12000.00, '2025-10-15', '活動費用'),
(7, '下學期會費', '會費', 9500.00, '2025-10-01', '第二學期會費'),
(8, 'XYZ公司贊助', '贊助', 15000.00, '2025-10-20', 'XYZ公司贊助'),
(9, '才藝表演', '活動收入', 7500.00, '2025-11-05', '票務收入'),
(10, '年度會費', '會費', 11000.00, '2025-11-01', '年度會員費'),
(11, '聖誕晚會', '活動收入', 18000.00, '2025-11-20', '門票及贊助'),
(12, '校慶活動', '活動收入', 13000.00, '2025-11-28', '校慶攤位收入'),
(13, '新春聯歡', '活動收入', 9000.00, '2025-02-10', '新年活動收入'),
(14, '畢業晚會', '活動收入', 22000.00, '2025-06-15', '畢業典禮收入'),
(15, '暑期會費', '會費', 8500.00, '2025-07-01', '暑期班會費');

-- --------------------------------------------------------

--
-- 資料表結構 `payments`
--

CREATE TABLE `payments` (
  `payment_id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `name` varchar(100) DEFAULT NULL,
  `activity` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `status` enum('已繳','未繳','部分結清') NOT NULL,
  `payment_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `payments`
--

INSERT INTO `payments` (`payment_id`, `student_id`, `name`, `activity`, `amount`, `status`, `payment_date`) VALUES
(1, '414401111', '陳小明', '上學期會費', 500.00, '已繳', '2025-09-02'),
(2, '413402222', '林美玲', '迎新晚會', 100.00, '未繳', '2025-09-03'),
(3, '412401333', '張大偉', '會費上學期', 500.00, '已繳', '2025-09-01'),
(4, '414402444', '王小華', '迎新晚會', 100.00, '已繳', '2025-09-05'),
(5, '413401555', '劉建國', '上學期會費', 500.00, '部分結清', '2025-09-04'),
(6, '414402444', '王小華', '制服趴', 90.00, '已繳', '2025-12-12'),
(7, '413401999', '黃小臻', '制服趴', 90.00, '未繳', '2025-12-12');

-- --------------------------------------------------------

--
-- 資料表結構 `user`
--

CREATE TABLE `user` (
  `user_id` int(11) NOT NULL,
  `student_id` varchar(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `grade` varchar(100) NOT NULL,
  `member_status` enum('會員','非會員') NOT NULL,
  `payment_count` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `user`
--

INSERT INTO `user` (`user_id`, `student_id`, `name`, `grade`, `member_status`, `payment_count`) VALUES
(1, '414401111', '陳小明', '一甲', '會員', 2),
(2, '413402222', '林美玲', '二乙', '非會員', 2),
(3, '412401333', '張大偉', '三甲', '會員', 1),
(4, '414402444', '王小華', '一乙', '非會員', 1),
(5, '413401555', '劉建國', '二甲', '會員', 2),
(6, '413401999', '黃小臻', '二甲', '非會員', 3),
(8, '411401888', '吳佩佩', '四乙', '非會員', 3);

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `expense`
--
ALTER TABLE `expense`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `income`
--
ALTER TABLE `income`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `payments`
--
ALTER TABLE `payments`
  ADD PRIMARY KEY (`payment_id`),
  ADD KEY `student_id` (`student_id`);

--
-- 資料表索引 `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `student_id` (`student_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `expense`
--
ALTER TABLE `expense`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `income`
--
ALTER TABLE `income`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `payments`
--
ALTER TABLE `payments`
  MODIFY `payment_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `user`
--
ALTER TABLE `user`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- 已傾印資料表的限制式
--

--
-- 資料表的限制式 `payments`
--
ALTER TABLE `payments`
  ADD CONSTRAINT `payments_ibfk_1` FOREIGN KEY (`student_id`) REFERENCES `user` (`student_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
