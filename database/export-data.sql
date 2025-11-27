-- MySQL dump 10.13  Distrib 8.0.34, for Win64 (x86_64)
--
-- Host: localhost    Database: lensart_eyewear
-- ------------------------------------------------------
-- Server version	8.0.34

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Dumping data for table `banners`
--

INSERT INTO banners VALUES (1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508346/banner_qevb0o.png','banner_qevb0o','active');

--
-- Dumping data for table `blogs`
--

INSERT INTO blogs VALUES (1,'Bảo Vệ Đôi Mắt: Bí Quyết Chọn Kính Hoàn Hảo','Tìm hiểu cách chọn kính mắt phù hợp để bảo vệ đôi mắt và nâng tầm phong cách của bạn. Bí quyết bao gồm chọn chất liệu, kiểu dáng và loại tròng kính.','https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508358/blog3_yqg5vr.png','blog3_yqg5vr','2024-12-19 04:04:02','active');
INSERT INTO blogs VALUES (2,'Kính Râm: Hơn Cả Một Phụ Kiện Thời Trang','Khám phá lý do tại sao kính râm không chỉ giúp bạn phong cách hơn mà còn bảo vệ mắt khỏi tia UV có hại.','https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508346/blog2_ayn1vs.png','blog2_ayn1vs','2024-12-19 04:04:02','active');
INSERT INTO blogs VALUES (3,'Chăm Sóc Tròng Kính: Mẹo Bảo Quản Để Độ Rõ Nét Lâu Dài','Khám phá các mẹo thực tế để vệ sinh và bảo quản kính mắt hoặc kính áp tròng nhằm đảm bảo hiệu suất và độ bền lâu dài.','https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508345/blog1_tjzv6m.jpg','blog1_tjzv6m','2024-12-19 04:04:02','active');
INSERT INTO blogs VALUES (4,'Chào Đón Năm Mới Với Ưu Đãi Lớn: Giảm Giá Đến 50%!','Tưng bừng chào đón năm mới với những ưu đãi siêu hấp dẫn! Hãy sử dụng các mã giảm giá dưới đây để tiết kiệm hơn khi mua sắm:\n\n    COUPON30: Giảm 30% cho mọi đơn hàng.\n    COUPON35: Giảm 35% cho mọi đơn hàng.\n    COUPON40: Giảm 40% cho mọi đơn hàng.\n    COUPON50: Giảm 50% cho mọi đơn hàng.\n\n    Nhanh tay lên! Chương trình ưu đãi chỉ diễn ra đến hết ngày 31/12/2024. Mua sắm ngay để khởi đầu năm mới với những món hời tuyệt vời!','https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734517321/new-year-sale_qnlpsc.png','new-year-sale_qnlpsc','2024-12-19 04:04:02','active');

--
-- Dumping data for table `branches`
--

INSERT INTO branches VALUES (1,'Hồ Chí Minh','123 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh',2,1.00,'active');
INSERT INTO branches VALUES (2,'Đà Nẵng','456 Trần Phú, Quận Hải Châu, TP. Đà Nẵng',3,0.80,'active');
INSERT INTO branches VALUES (3,'Hà Nội','789 Đường Láng, Quận Đống Đa, TP. Hà Nội',4,0.90,'active');

--
-- Dumping data for table `brands`
--

INSERT INTO brands VALUES (1,'Bevis','active');
INSERT INTO brands VALUES (2,'Chemi','active');
INSERT INTO brands VALUES (3,'Essilor','active');
INSERT INTO brands VALUES (4,'Kodak','active');
INSERT INTO brands VALUES (5,'Zeiss','active');
INSERT INTO brands VALUES (6,'Hoya','active');

--
-- Dumping data for table `cache`
--


--
-- Dumping data for table `cache_locks`
--


--
-- Dumping data for table `cart_details`
--

INSERT INTO cart_details VALUES (2,2,2,1,'Xanh Dương',1,500000.00);
INSERT INTO cart_details VALUES (3,2,3,1,'Đỏ',2,1200000.00);
INSERT INTO cart_details VALUES (4,3,4,1,'Xanh Lá',1,650000.00);
INSERT INTO cart_details VALUES (5,3,5,1,'Nâu',1,650000.00);
INSERT INTO cart_details VALUES (6,3,6,1,'Xám',1,750000.00);
INSERT INTO cart_details VALUES (7,4,7,2,'Đen',1,880000.00);
INSERT INTO cart_details VALUES (8,5,8,2,'Xanh Dương',1,920000.00);
INSERT INTO cart_details VALUES (9,5,9,2,'Đỏ',1,1040000.00);
INSERT INTO cart_details VALUES (10,6,10,2,'Xanh Lá',1,1120000.00);
INSERT INTO cart_details VALUES (11,6,11,2,'Nâu',1,1200000.00);
INSERT INTO cart_details VALUES (12,6,12,2,'Xám',1,1240000.00);
INSERT INTO cart_details VALUES (13,7,13,3,'Đen',1,1890000.00);
INSERT INTO cart_details VALUES (14,8,14,3,'Xanh Dương',1,1890000.00);
INSERT INTO cart_details VALUES (15,8,15,3,'Đỏ',1,2070000.00);
INSERT INTO cart_details VALUES (16,9,16,3,'Xanh Lá',1,2160000.00);
INSERT INTO cart_details VALUES (17,9,17,3,'Nâu',1,2160000.00);
INSERT INTO cart_details VALUES (18,9,18,3,'Xám',1,2250000.00);
INSERT INTO cart_details VALUES (19,10,19,1,'Đen',1,500000.00);

--
-- Dumping data for table `carts`
--

INSERT INTO carts VALUES (1,5);
INSERT INTO carts VALUES (2,6);
INSERT INTO carts VALUES (3,7);
INSERT INTO carts VALUES (4,8);
INSERT INTO carts VALUES (5,9);
INSERT INTO carts VALUES (6,10);
INSERT INTO carts VALUES (7,11);
INSERT INTO carts VALUES (8,12);
INSERT INTO carts VALUES (9,13);
INSERT INTO carts VALUES (10,14);
INSERT INTO carts VALUES (11,15);
INSERT INTO carts VALUES (12,16);
INSERT INTO carts VALUES (13,17);
INSERT INTO carts VALUES (14,18);
INSERT INTO carts VALUES (15,19);
INSERT INTO carts VALUES (16,20);
INSERT INTO carts VALUES (17,21);
INSERT INTO carts VALUES (18,22);
INSERT INTO carts VALUES (19,23);
INSERT INTO carts VALUES (20,24);
INSERT INTO carts VALUES (21,25);
INSERT INTO carts VALUES (22,26);
INSERT INTO carts VALUES (23,27);
INSERT INTO carts VALUES (24,28);
INSERT INTO carts VALUES (25,29);
INSERT INTO carts VALUES (26,30);
INSERT INTO carts VALUES (27,31);
INSERT INTO carts VALUES (28,32);
INSERT INTO carts VALUES (29,33);
INSERT INTO carts VALUES (30,34);

--
-- Dumping data for table `category`
--

INSERT INTO category VALUES (1,'Gọng kính','active');
INSERT INTO category VALUES (2,'Kính mát', 'active');
INSERT INTO category VALUES (3,'Tròng kính', 'active');

--
-- Dumping data for table `coupons`
--

INSERT INTO coupons VALUES (1,'COUPON10','10% Discount',8,10000,'active');
INSERT INTO coupons VALUES (2,'COUPON15','15% Discount',6,15000,'active');
INSERT INTO coupons VALUES (3,'COUPON20','20% Discount',6,20000,'active');
INSERT INTO coupons VALUES (4,'COUPON25','25% Discount',5,25000,'active');
INSERT INTO coupons VALUES (5,'COUPON30','30% Discount',4,30000,'active');
INSERT INTO coupons VALUES (6,'COUPON35','35% Discount',3,35000,'active');
INSERT INTO coupons VALUES (7,'COUPON40','40% Discount',3,40000,'active');
INSERT INTO coupons VALUES (8,'COUPON50','50% Discount',2,50000,'active');
INSERT INTO coupons VALUES (9,'COUPON75','75% Discount',1,75000,'active');
INSERT INTO coupons VALUES (10,'COUPON100','100% Discount',1,100000,'active');

--
-- Dumping data for table `failed_jobs`
--


--
-- Dumping data for table `features`
--

INSERT INTO features VALUES (1,'Lọc ánh sáng xanh','active');
INSERT INTO features VALUES (2,'Đổi màu','active');
INSERT INTO features VALUES (3,'Râm cận','active');
INSERT INTO features VALUES (4,'Siêu mỏng','active');
INSERT INTO features VALUES (5,'Chống UV','active');
INSERT INTO features VALUES (6,'Chống hơi nước','active');

--
-- Dumping data for table `job_batches`
--


--
-- Dumping data for table `jobs`
--


--
-- Dumping data for table `materials`
--

INSERT INTO materials VALUES (1,'Tổng hợp','active');
INSERT INTO materials VALUES (2,'Acetate','active');
INSERT INTO materials VALUES (3,'Titanium','active');
INSERT INTO materials VALUES (4,'Kim loại','active');
INSERT INTO materials VALUES (5,'Nhựa','active');
INSERT INTO materials VALUES (6,'TR90','active');

--
-- Dumping data for table `order_details`
--

INSERT INTO order_details VALUES (1,1,1,'Đen',1,490000.00);
INSERT INTO order_details VALUES (2,1,2,'Xanh',1,490000.00);
INSERT INTO order_details VALUES (3,2,3,'Đỏ',2,1200000.00);
INSERT INTO order_details VALUES (4,3,4,'Xanh lá',1,635000.00);
INSERT INTO order_details VALUES (5,3,5,'Đen',1,635000.00);
INSERT INTO order_details VALUES (6,4,6,'Trắng',1,750000.00);
INSERT INTO order_details VALUES (7,5,7,'Xanh',2,2200000.00);
INSERT INTO order_details VALUES (8,6,8,'Vàng',1,1150000.00);
INSERT INTO order_details VALUES (9,7,9,'Đen',1,1300000.00);
INSERT INTO order_details VALUES (10,8,10,'Xanh lá',2,2800000.00);
INSERT INTO order_details VALUES (11,9,11,'Xanh',1,1500000.00);
INSERT INTO order_details VALUES (12,10,12,'Đỏ',1,1550000.00);
INSERT INTO order_details VALUES (13,11,13,'Vàng',1,2100000.00);
INSERT INTO order_details VALUES (14,12,14,'Đen',2,4200000.00);
INSERT INTO order_details VALUES (15,13,15,'Xanh',1,2300000.00);
INSERT INTO order_details VALUES (16,14,16,'Xanh lá',1,2400000.00);
INSERT INTO order_details VALUES (17,15,17,'Đỏ',2,4800000.00);
INSERT INTO order_details VALUES (18,16,18,'Trắng',1,2500000.00);
INSERT INTO order_details VALUES (19,17,19,'Đen',1,500000.00);
INSERT INTO order_details VALUES (20,18,20,'Xanh',1,500000.00);
INSERT INTO order_details VALUES (21,19,21,'Vàng',2,1200000.00);
INSERT INTO order_details VALUES (22,20,22,'Xanh lá',1,650000.00);
INSERT INTO order_details VALUES (23,21,23,'Đen',2,1300000.00);
INSERT INTO order_details VALUES (24,22,24,'Xanh',1,750000.00);
INSERT INTO order_details VALUES (25,23,25,'Đỏ',1,1100000.00);
INSERT INTO order_details VALUES (26,24,26,'Xanh lá',1,1150000.00);
INSERT INTO order_details VALUES (27,25,27,'Trắng',1,1300000.00);
INSERT INTO order_details VALUES (28,26,28,'Vàng',2,2800000.00);
INSERT INTO order_details VALUES (29,27,29,'Xanh',1,1500000.00);
INSERT INTO order_details VALUES (30,28,30,'Đen',1,1550000.00);
INSERT INTO order_details VALUES (31,29,31,'Xanh lá',1,2100000.00);
INSERT INTO order_details VALUES (32,30,32,'Đỏ',2,4200000.00);
INSERT INTO order_details VALUES (33,31,33,'Vàng',1,2300000.00);
INSERT INTO order_details VALUES (34,32,34,'Đen',1,2400000.00);
INSERT INTO order_details VALUES (35,40,1,'Đen',1,450000.00);
INSERT INTO order_details VALUES (36,40,2,'Xanh',1,450000.00);
INSERT INTO order_details VALUES (37,41,3,'Đỏ',2,1080000.00);
INSERT INTO order_details VALUES (38,42,4,'Xanh lá',1,585000.00);
INSERT INTO order_details VALUES (39,42,5,'Đen',1,585000.00);
INSERT INTO order_details VALUES (40,43,6,'Trắng',1,675000.00);
INSERT INTO order_details VALUES (41,44,7,'Xanh',2,1980000.00);
INSERT INTO order_details VALUES (42,45,8,'Vàng',1,1035000.00);
INSERT INTO order_details VALUES (43,46,9,'Đen',1,1170000.00);
INSERT INTO order_details VALUES (44,47,10,'Xanh lá',2,2520000.00);
INSERT INTO order_details VALUES (45,48,11,'Xanh',1,1350000.00);
INSERT INTO order_details VALUES (46,49,12,'Đỏ',1,1395000.00);
INSERT INTO order_details VALUES (47,50,13,'Vàng',1,1890000.00);
INSERT INTO order_details VALUES (48,51,14,'Đen',2,3780000.00);
INSERT INTO order_details VALUES (49,52,15,'Xanh',1,2070000.00);
INSERT INTO order_details VALUES (50,53,16,'Xanh lá',1,2160000.00);
INSERT INTO order_details VALUES (51,54,17,'Đỏ',2,4320000.00);
INSERT INTO order_details VALUES (52,55,18,'Trắng',1,2250000.00);
INSERT INTO order_details VALUES (53,56,19,'Đen',1,450000.00);
INSERT INTO order_details VALUES (54,57,20,'Xanh',1,450000.00);
INSERT INTO order_details VALUES (55,58,21,'Vàng',2,1080000.00);
INSERT INTO order_details VALUES (56,59,22,'Xanh lá',1,585000.00);
INSERT INTO order_details VALUES (60,60,1,'Đen',1,400000.00);
INSERT INTO order_details VALUES (61,60,2,'Xanh',1,400000.00);
INSERT INTO order_details VALUES (62,61,3,'Đỏ',2,960000.00);
INSERT INTO order_details VALUES (63,62,4,'Xanh lá',1,520000.00);
INSERT INTO order_details VALUES (64,62,5,'Đen',1,520000.00);
INSERT INTO order_details VALUES (65,63,6,'Trắng',1,600000.00);
INSERT INTO order_details VALUES (66,64,7,'Xanh',2,1760000.00);
INSERT INTO order_details VALUES (67,65,8,'Vàng',1,920000.00);
INSERT INTO order_details VALUES (68,66,9,'Đen',1,1040000.00);
INSERT INTO order_details VALUES (69,67,10,'Xanh lá',2,2240000.00);
INSERT INTO order_details VALUES (70,68,11,'Xanh',1,1200000.00);
INSERT INTO order_details VALUES (71,69,12,'Đỏ',1,1240000.00);
INSERT INTO order_details VALUES (72,70,13,'Vàng',1,1680000.00);
INSERT INTO order_details VALUES (73,71,14,'Đen',2,3360000.00);
INSERT INTO order_details VALUES (74,72,15,'Xanh',1,1840000.00);
INSERT INTO order_details VALUES (75,73,16,'Xanh lá',1,1920000.00);
INSERT INTO order_details VALUES (76,74,17,'Đỏ',2,3840000.00);
INSERT INTO order_details VALUES (77,75,18,'Trắng',1,2000000.00);
INSERT INTO order_details VALUES (78,76,19,'Đen',1,400000.00);
INSERT INTO order_details VALUES (79,77,20,'Xanh',1,400000.00);
INSERT INTO order_details VALUES (80,78,21,'Vàng',2,960000.00);
INSERT INTO order_details VALUES (81,79,22,'Xanh lá',1,520000.00);

--
-- Dumping data for table `orders`
--

INSERT INTO orders VALUES (1,5,'2024-12-19 04:04:02',1,'123 Nguyen Trai, HCM','Deliver fast',1,490000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (2,5,'2024-12-19 04:04:02',1,'123 Nguyen Trai, HCM','Handle with care',NULL,1500000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (3,6,'2024-12-19 04:04:02',1,'456 Le Loi, HCM','Fast delivery',2,1985000.00,'Đang xử lý','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (4,6,'2024-12-19 04:04:02',1,'456 Le Loi, HCM','Important',NULL,1200000.00,'Đã hủy','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (5,7,'2024-12-19 04:04:02',1,'789 Tran Hung Dao, HCM','Fragile',NULL,1400000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (6,8,'2024-12-19 04:04:02',1,'56 Vo Van Kiet, HCM','Gift wrap',NULL,900000.00,'Đang giao hàng','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (7,9,'2024-12-19 04:04:02',1,'35 Nguyen Hue, HCM','Careful packaging',NULL,800000.00,'Đã giao','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (8,10,'2024-12-19 04:04:02',1,'89 Dien Bien Phu, HCM','Priority delivery',NULL,1100000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (9,11,'2024-12-19 04:04:02',1,'12 Ly Chinh Thang, HCM','Express delivery',NULL,1300000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (10,12,'2024-12-19 04:04:02',1,'23 Vo Thi Sau, HCM','Special request',NULL,950000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (11,13,'2024-12-19 04:04:02',1,'45 Dinh Tien Hoang, HCM','Fragile items',NULL,1200000.00,'Đã hủy','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (12,14,'2024-12-19 04:04:02',1,'67 Le Duan, HCM','Deliver fast',NULL,1000000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (13,15,'2024-12-19 04:04:02',1,'89 Hai Ba Trung, HCM','Gift for someone',NULL,1400000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (14,16,'2024-12-19 04:04:02',1,'101 Pasteur, HCM','Handle carefully',NULL,1550000.00,'Đang xử lý','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (15,17,'2024-12-19 04:04:02',1,'34 Nguyen Oanh, HCM','Quick delivery',NULL,1450000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (16,18,'2024-12-19 04:04:02',1,'56 Tran Quang Khai, HCM','Special care',NULL,1250000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (17,19,'2024-12-19 04:04:02',1,'78 Ton Duc Thang, HCM','Urgent',NULL,950000.00,'Đang xử lý','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (18,20,'2024-12-19 04:04:02',1,'101 Nguyen Dinh Chieu, HCM','Priority',NULL,1200000.00,'Đã giao','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (19,21,'2024-12-19 04:04:02',1,'67 Cach Mang Thang Tam, HCM','Deliver now',NULL,1100000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (20,22,'2024-12-19 04:04:02',1,'34 Truong Dinh, HCM','Handle carefully',NULL,1350000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (21,23,'2024-12-19 04:04:02',1,'56 Le Van Sy, HCM','Careful packaging',NULL,1250000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (22,24,'2024-12-19 04:04:02',1,'78 Pham Van Dong, HCM','Quick service',NULL,950000.00,'Đã giao','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (23,25,'2024-12-19 04:04:02',1,'101 Dien Bien Phu, HCM','Urgent request',NULL,1450000.00,'Đang xử lý','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (24,26,'2024-12-19 04:04:02',1,'67 Nguyen Van Cu, HCM','Deliver with care',NULL,1200000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (25,27,'2024-12-19 04:04:02',1,'34 Vo Van Tan, HCM','Handle with caution',NULL,1300000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (26,28,'2024-12-19 04:04:02',1,'56 Ly Thuong Kiet, HCM','Deliver fast',NULL,1550000.00,'Đang xử lý','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (27,29,'2024-12-19 04:04:02',1,'78 Bach Dang, HCM','Gift item',NULL,1400000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (28,30,'2024-12-19 04:04:02',1,'101 Hoang Sa, HCM','Quick handling',NULL,1250000.00,'Đã giao','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (29,31,'2024-12-19 04:04:02',1,'34 Truong Chinh, HCM','Careful packaging',NULL,1450000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (30,32,'2024-12-19 04:04:02',1,'56 Nguyen Trai, HCM','Fragile item',NULL,1100000.00,'Đang xử lý','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (31,33,'2024-12-19 04:04:02',1,'78 Dong Khoi, HCM','Handle urgently',NULL,1250000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (32,34,'2024-12-19 04:04:02',1,'101 Pasteur, HCM','Deliver fast',NULL,1300000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (40,5,'2024-12-19 04:04:02',3,'123 Nguyen Trai, Ha Noi','Deliver fast',NULL,1000000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (41,6,'2024-12-19 04:04:02',3,'456 Le Loi, Ha Noi','Handle with care',NULL,1500000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (42,7,'2024-12-19 04:04:02',3,'789 Tran Hung Dao, Ha Noi','Urgent delivery',NULL,2000000.00,'Đang xử lý','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (43,8,'2024-12-19 04:04:02',3,'12 Ly Chinh Thang, Ha Noi','Gift wrap',NULL,1200000.00,'Đã hủy','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (44,9,'2024-12-19 04:04:02',3,'35 Nguyen Hue, Ha Noi','Fragile',NULL,1400000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (45,10,'2024-12-19 04:04:02',3,'89 Dien Bien Phu, Ha Noi','Priority delivery',NULL,900000.00,'Đang giao hàng','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (46,11,'2024-12-19 04:04:02',3,'23 Vo Thi Sau, Ha Noi','Special request',NULL,800000.00,'Đã giao','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (47,12,'2024-12-19 04:04:02',3,'101 Pasteur, Ha Noi','Quick service',NULL,1100000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (48,13,'2024-12-19 04:04:02',3,'45 Dinh Tien Hoang, Ha Noi','Careful packaging',NULL,1300000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (49,14,'2024-12-19 04:04:02',3,'67 Le Duan, Ha Noi','Express delivery',NULL,950000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (50,15,'2024-12-19 04:04:02',3,'89 Hai Ba Trung, Ha Noi','Urgent',NULL,1200000.00,'Đã giao','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (51,16,'2024-12-19 04:04:02',3,'101 Nguyen Dinh Chieu, Ha Noi','Gift for someone',NULL,1400000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (52,17,'2024-12-19 04:04:02',3,'34 Nguyen Oanh, Ha Noi','Handle carefully',NULL,1500000.00,'Đang xử lý','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (53,18,'2024-12-19 04:04:02',3,'56 Tran Quang Khai, Ha Noi','Deliver fast',NULL,1250000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (54,19,'2024-12-19 04:04:02',3,'78 Ton Duc Thang, Ha Noi','Special care',NULL,950000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (55,20,'2024-12-19 04:04:02',3,'67 Cach Mang Thang Tam, Ha Noi','Deliver with care',NULL,1100000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (56,21,'2024-12-19 04:04:02',3,'34 Truong Dinh, Ha Noi','Handle urgently',NULL,1450000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (57,22,'2024-12-19 04:04:02',3,'56 Le Van Sy, Ha Noi','Priority delivery',NULL,1350000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (58,23,'2024-12-19 04:04:02',3,'78 Bach Dang, Ha Noi','Urgent request',NULL,1200000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (59,24,'2024-12-19 04:04:02',3,'101 Nguyen Van Cu, Ha Noi','Quick delivery',NULL,1400000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (60,5,'2024-12-19 04:04:02',2,'123 Nguyen Trai, Da Nang','Deliver fast',NULL,800000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (61,6,'2024-12-19 04:04:02',2,'456 Le Loi, Da Nang','Handle with care',NULL,1200000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (62,7,'2024-12-19 04:04:02',2,'789 Tran Hung Dao, Da Nang','Urgent delivery',NULL,1600000.00,'Đang xử lý','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (63,8,'2024-12-19 04:04:02',2,'12 Ly Chinh Thang, Da Nang','Gift wrap',NULL,960000.00,'Đã hủy','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (64,9,'2024-12-19 04:04:02',2,'35 Nguyen Hue, Da Nang','Fragile',NULL,1120000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (65,10,'2024-12-19 04:04:02',2,'89 Dien Bien Phu, Da Nang','Priority delivery',NULL,720000.00,'Đang giao hàng','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (66,11,'2024-12-19 04:04:02',2,'23 Vo Thi Sau, Da Nang','Special request',NULL,640000.00,'Đã giao','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (67,12,'2024-12-19 04:04:02',2,'101 Pasteur, Da Nang','Quick service',NULL,880000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (68,13,'2024-12-19 04:04:02',2,'45 Dinh Tien Hoang, Da Nang','Careful packaging',NULL,1040000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (69,14,'2024-12-19 04:04:02',2,'67 Le Duan, Da Nang','Express delivery',NULL,760000.00,'Đã giao','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (70,15,'2024-12-19 04:04:02',2,'89 Hai Ba Trung, Da Nang','Urgent',NULL,960000.00,'Đã giao','Chưa thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (71,16,'2024-12-19 04:04:02',2,'101 Nguyen Dinh Chieu, Da Nang','Gift for someone',NULL,1120000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (72,17,'2024-12-19 04:04:02',2,'34 Nguyen Oanh, Da Nang','Handle carefully',NULL,1200000.00,'Đang xử lý','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (73,18,'2024-12-19 04:04:02',2,'56 Tran Quang Khai, Da Nang','Deliver fast',NULL,1000000.00,'Đã giao','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (74,19,'2024-12-19 04:04:02',2,'78 Ton Duc Thang, Da Nang','Special care',NULL,760000.00,'Đang giao hàng','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (75,20,'2024-12-19 04:04:02',2,'67 Cach Mang Thang Tam, Da Nang','Deliver with care',NULL,880000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (76,21,'2024-12-19 04:04:02',2,'34 Truong Dinh, Da Nang','Handle urgently',NULL,1160000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (77,22,'2024-12-19 04:04:02',2,'56 Le Van Sy, Da Nang','Priority delivery',NULL,1080000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');
INSERT INTO orders VALUES (78,23,'2024-12-19 04:04:02',2,'78 Bach Dang, Da Nang','Urgent request',NULL,960000.00,'Đã giao','Đã thanh toán','Tiền mặt','active');
INSERT INTO orders VALUES (79,24,'2024-12-19 04:04:02',2,'101 Nguyen Van Cu, Da Nang','Quick delivery',NULL,1120000.00,'Đang giao hàng','Đã thanh toán','Chuyển khoản','active');

--
-- Dumping data for table `otps`
--


--
-- Dumping data for table `password_reset_tokens`
--


--
-- Dumping data for table `payos_transactions`
--

INSERT INTO payos_transactions VALUES (1,100001,1,490000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (2,100002,2,1500000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (3,100003,4,1200000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (4,100004,47,1100000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (5,100005,48,1300000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (6,100006,67,900000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (7,100007,68,800000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (8,100008,70,1100000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (9,100009,72,1300000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');
INSERT INTO payos_transactions VALUES (10,100010,73,950000.00,'2024-12-19 04:04:02','2024-12-19 04:04:02');

--
-- Dumping data for table `personal_access_tokens`
--


--
-- Dumping data for table `product_details`
--

INSERT INTO product_details VALUES (1,1,'Đen',9,'active');
INSERT INTO product_details VALUES (1,2,'Đen',4,'active');
INSERT INTO product_details VALUES (1,3,'Đen',4,'active');
INSERT INTO product_details VALUES (2,1,'Xanh Dương',8,'active');
INSERT INTO product_details VALUES (2,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (2,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (3,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (3,2,'Đỏ',3,'active');
INSERT INTO product_details VALUES (3,3,'Đỏ',3,'active');
INSERT INTO product_details VALUES (4,1,'Xanh Lá',8,'active');
INSERT INTO product_details VALUES (4,2,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (4,3,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (5,1,'Nâu',7,'active');
INSERT INTO product_details VALUES (5,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (5,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (6,1,'Xám',11,'active');
INSERT INTO product_details VALUES (6,2,'Xám',5,'active');
INSERT INTO product_details VALUES (6,3,'Xám',5,'active');
INSERT INTO product_details VALUES (7,1,'Đen',10,'active');
INSERT INTO product_details VALUES (7,2,'Đen',5,'active');
INSERT INTO product_details VALUES (7,3,'Đen',5,'active');
INSERT INTO product_details VALUES (8,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (8,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (8,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (9,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (9,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (9,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (10,1,'Xanh Lá',8,'active');
INSERT INTO product_details VALUES (10,2,'Xanh Lá',3,'active');
INSERT INTO product_details VALUES (10,3,'Xanh Lá',3,'active');
INSERT INTO product_details VALUES (11,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (11,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (11,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (12,1,'Xám',10,'active');
INSERT INTO product_details VALUES (12,2,'Xám',5,'active');
INSERT INTO product_details VALUES (12,3,'Xám',5,'active');
INSERT INTO product_details VALUES (13,1,'Đen',10,'active');
INSERT INTO product_details VALUES (13,2,'Đen',5,'active');
INSERT INTO product_details VALUES (13,3,'Đen',5,'active');
INSERT INTO product_details VALUES (14,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (14,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (14,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (15,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (15,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (15,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (16,1,'Xanh Lá',9,'active');
INSERT INTO product_details VALUES (16,2,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (16,3,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (17,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (17,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (17,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (18,1,'Xám',10,'active');
INSERT INTO product_details VALUES (18,2,'Xám',5,'active');
INSERT INTO product_details VALUES (18,3,'Xám',5,'active');
INSERT INTO product_details VALUES (19,1,'Đen',9,'active');
INSERT INTO product_details VALUES (19,2,'Đen',4,'active');
INSERT INTO product_details VALUES (19,3,'Đen',4,'active');
INSERT INTO product_details VALUES (20,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (20,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (20,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (21,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (21,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (21,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (22,1,'Xanh Lá',9,'active');
INSERT INTO product_details VALUES (22,2,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (22,3,'Xanh Lá',4,'active');
INSERT INTO product_details VALUES (23,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (23,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (23,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (24,1,'Xám',10,'active');
INSERT INTO product_details VALUES (24,2,'Xám',5,'active');
INSERT INTO product_details VALUES (24,3,'Xám',5,'active');
INSERT INTO product_details VALUES (25,1,'Đen',10,'active');
INSERT INTO product_details VALUES (25,2,'Đen',5,'active');
INSERT INTO product_details VALUES (25,3,'Đen',5,'active');
INSERT INTO product_details VALUES (26,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (26,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (26,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (27,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (27,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (27,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (28,1,'Xanh Lá',10,'active');
INSERT INTO product_details VALUES (28,2,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (28,3,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (29,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (29,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (29,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (30,1,'Xám',10,'active');
INSERT INTO product_details VALUES (30,2,'Xám',5,'active');
INSERT INTO product_details VALUES (30,3,'Xám',5,'active');
INSERT INTO product_details VALUES (31,1,'Đen',10,'active');
INSERT INTO product_details VALUES (31,2,'Đen',5,'active');
INSERT INTO product_details VALUES (31,3,'Đen',5,'active');
INSERT INTO product_details VALUES (32,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (32,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (32,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (33,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (33,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (33,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (34,1,'Xanh Lá',10,'active');
INSERT INTO product_details VALUES (34,2,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (34,3,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (35,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (35,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (35,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (36,1,'Xám',10,'active');
INSERT INTO product_details VALUES (36,2,'Xám',5,'active');
INSERT INTO product_details VALUES (36,3,'Xám',5,'active');
INSERT INTO product_details VALUES (37,1,'Đen',10,'active');
INSERT INTO product_details VALUES (37,2,'Đen',5,'active');
INSERT INTO product_details VALUES (37,3,'Đen',5,'active');
INSERT INTO product_details VALUES (38,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (38,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (38,3,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (39,1,'Đỏ',10,'active');
INSERT INTO product_details VALUES (39,2,'Đỏ',5,'active');
INSERT INTO product_details VALUES (39,3,'Đỏ',5,'active');
INSERT INTO product_details VALUES (40,1,'Xanh Lá',10,'active');
INSERT INTO product_details VALUES (40,2,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (40,3,'Xanh Lá',5,'active');
INSERT INTO product_details VALUES (41,1,'Nâu',10,'active');
INSERT INTO product_details VALUES (41,2,'Nâu',5,'active');
INSERT INTO product_details VALUES (41,3,'Nâu',5,'active');
INSERT INTO product_details VALUES (42,1,'Xám',10,'active');
INSERT INTO product_details VALUES (42,2,'Xám',5,'active');
INSERT INTO product_details VALUES (42,3,'Xám',5,'active');
INSERT INTO product_details VALUES (43,1,'Đen',10,'active');
INSERT INTO product_details VALUES (43,2,'Đen',5,'active');
INSERT INTO product_details VALUES (43,3,'Đen',5,'active');
INSERT INTO product_details VALUES (44,1,'Xanh Dương',10,'active');
INSERT INTO product_details VALUES (44,2,'Xanh Dương',5,'active');
INSERT INTO product_details VALUES (44,3,'Xanh Dương',5,'active');

--
-- Dumping data for table `product_features`
--

INSERT INTO product_features VALUES (1,1);
INSERT INTO product_features VALUES (2,1);
INSERT INTO product_features VALUES (3,1);
INSERT INTO product_features VALUES (4,1);
INSERT INTO product_features VALUES (5,1);
INSERT INTO product_features VALUES (6,1);
INSERT INTO product_features VALUES (7,1);
INSERT INTO product_features VALUES (8,1);
INSERT INTO product_features VALUES (9,1);
INSERT INTO product_features VALUES (10,1);
INSERT INTO product_features VALUES (11,1);
INSERT INTO product_features VALUES (12,1);
INSERT INTO product_features VALUES (13,1);
INSERT INTO product_features VALUES (14,1);
INSERT INTO product_features VALUES (15,1);
INSERT INTO product_features VALUES (16,1);
INSERT INTO product_features VALUES (17,1);
INSERT INTO product_features VALUES (18,1);
INSERT INTO product_features VALUES (19,1);
INSERT INTO product_features VALUES (20,1);
INSERT INTO product_features VALUES (21,1);
INSERT INTO product_features VALUES (22,1);
INSERT INTO product_features VALUES (23,1);
INSERT INTO product_features VALUES (24,1);
INSERT INTO product_features VALUES (25,1);
INSERT INTO product_features VALUES (26,1);
INSERT INTO product_features VALUES (27,1);
INSERT INTO product_features VALUES (28,1);
INSERT INTO product_features VALUES (29,1);
INSERT INTO product_features VALUES (30,1);
INSERT INTO product_features VALUES (31,1);
INSERT INTO product_features VALUES (32,1);
INSERT INTO product_features VALUES (33,1);
INSERT INTO product_features VALUES (34,1);
INSERT INTO product_features VALUES (35,1);
INSERT INTO product_features VALUES (36,1);
INSERT INTO product_features VALUES (37,1);
INSERT INTO product_features VALUES (38,1);
INSERT INTO product_features VALUES (39,1);
INSERT INTO product_features VALUES (40,1);
INSERT INTO product_features VALUES (41,1);
INSERT INTO product_features VALUES (42,1);
INSERT INTO product_features VALUES (43,1);
INSERT INTO product_features VALUES (44,1);
INSERT INTO product_features VALUES (1,2);
INSERT INTO product_features VALUES (2,2);
INSERT INTO product_features VALUES (3,2);
INSERT INTO product_features VALUES (4,2);
INSERT INTO product_features VALUES (5,2);
INSERT INTO product_features VALUES (6,2);
INSERT INTO product_features VALUES (7,2);
INSERT INTO product_features VALUES (8,2);
INSERT INTO product_features VALUES (9,2);
INSERT INTO product_features VALUES (10,2);
INSERT INTO product_features VALUES (11,2);
INSERT INTO product_features VALUES (12,2);
INSERT INTO product_features VALUES (13,2);
INSERT INTO product_features VALUES (14,2);
INSERT INTO product_features VALUES (15,2);
INSERT INTO product_features VALUES (16,2);
INSERT INTO product_features VALUES (17,2);
INSERT INTO product_features VALUES (18,2);
INSERT INTO product_features VALUES (19,2);
INSERT INTO product_features VALUES (20,2);
INSERT INTO product_features VALUES (21,2);
INSERT INTO product_features VALUES (22,2);
INSERT INTO product_features VALUES (23,2);
INSERT INTO product_features VALUES (24,2);
INSERT INTO product_features VALUES (25,2);
INSERT INTO product_features VALUES (26,2);
INSERT INTO product_features VALUES (27,2);
INSERT INTO product_features VALUES (28,2);
INSERT INTO product_features VALUES (29,2);
INSERT INTO product_features VALUES (30,2);
INSERT INTO product_features VALUES (31,2);
INSERT INTO product_features VALUES (32,2);
INSERT INTO product_features VALUES (33,2);
INSERT INTO product_features VALUES (34,2);
INSERT INTO product_features VALUES (35,2);
INSERT INTO product_features VALUES (36,2);
INSERT INTO product_features VALUES (37,2);
INSERT INTO product_features VALUES (38,2);
INSERT INTO product_features VALUES (39,2);
INSERT INTO product_features VALUES (40,2);
INSERT INTO product_features VALUES (41,2);
INSERT INTO product_features VALUES (42,2);
INSERT INTO product_features VALUES (43,2);
INSERT INTO product_features VALUES (44,2);
INSERT INTO product_features VALUES (1,3);
INSERT INTO product_features VALUES (2,3);
INSERT INTO product_features VALUES (3,3);
INSERT INTO product_features VALUES (4,3);
INSERT INTO product_features VALUES (5,3);
INSERT INTO product_features VALUES (6,3);
INSERT INTO product_features VALUES (7,3);
INSERT INTO product_features VALUES (8,3);
INSERT INTO product_features VALUES (9,3);
INSERT INTO product_features VALUES (10,3);
INSERT INTO product_features VALUES (11,3);
INSERT INTO product_features VALUES (12,3);
INSERT INTO product_features VALUES (13,3);
INSERT INTO product_features VALUES (14,3);
INSERT INTO product_features VALUES (15,3);
INSERT INTO product_features VALUES (16,3);
INSERT INTO product_features VALUES (17,3);
INSERT INTO product_features VALUES (18,3);
INSERT INTO product_features VALUES (19,3);
INSERT INTO product_features VALUES (20,3);
INSERT INTO product_features VALUES (21,3);
INSERT INTO product_features VALUES (22,3);
INSERT INTO product_features VALUES (23,3);
INSERT INTO product_features VALUES (24,3);
INSERT INTO product_features VALUES (25,3);
INSERT INTO product_features VALUES (26,3);
INSERT INTO product_features VALUES (27,3);
INSERT INTO product_features VALUES (28,3);
INSERT INTO product_features VALUES (29,3);
INSERT INTO product_features VALUES (30,3);
INSERT INTO product_features VALUES (31,3);
INSERT INTO product_features VALUES (32,3);
INSERT INTO product_features VALUES (33,3);
INSERT INTO product_features VALUES (34,3);
INSERT INTO product_features VALUES (35,3);
INSERT INTO product_features VALUES (36,3);
INSERT INTO product_features VALUES (37,3);
INSERT INTO product_features VALUES (38,3);
INSERT INTO product_features VALUES (39,3);
INSERT INTO product_features VALUES (40,3);
INSERT INTO product_features VALUES (41,3);
INSERT INTO product_features VALUES (42,3);
INSERT INTO product_features VALUES (43,3);
INSERT INTO product_features VALUES (44,3);
INSERT INTO product_features VALUES (1,4);
INSERT INTO product_features VALUES (2,4);
INSERT INTO product_features VALUES (3,4);
INSERT INTO product_features VALUES (4,4);
INSERT INTO product_features VALUES (5,4);
INSERT INTO product_features VALUES (6,4);
INSERT INTO product_features VALUES (7,4);
INSERT INTO product_features VALUES (8,4);
INSERT INTO product_features VALUES (9,4);
INSERT INTO product_features VALUES (10,4);
INSERT INTO product_features VALUES (11,4);
INSERT INTO product_features VALUES (12,4);
INSERT INTO product_features VALUES (13,4);
INSERT INTO product_features VALUES (14,4);
INSERT INTO product_features VALUES (15,4);
INSERT INTO product_features VALUES (16,4);
INSERT INTO product_features VALUES (17,4);
INSERT INTO product_features VALUES (18,4);
INSERT INTO product_features VALUES (19,4);
INSERT INTO product_features VALUES (20,4);
INSERT INTO product_features VALUES (21,4);
INSERT INTO product_features VALUES (22,4);
INSERT INTO product_features VALUES (23,4);
INSERT INTO product_features VALUES (24,4);
INSERT INTO product_features VALUES (25,4);
INSERT INTO product_features VALUES (26,4);
INSERT INTO product_features VALUES (27,4);
INSERT INTO product_features VALUES (28,4);
INSERT INTO product_features VALUES (29,4);
INSERT INTO product_features VALUES (30,4);
INSERT INTO product_features VALUES (31,4);
INSERT INTO product_features VALUES (32,4);
INSERT INTO product_features VALUES (33,4);
INSERT INTO product_features VALUES (34,4);
INSERT INTO product_features VALUES (35,4);
INSERT INTO product_features VALUES (36,4);
INSERT INTO product_features VALUES (37,4);
INSERT INTO product_features VALUES (38,4);
INSERT INTO product_features VALUES (39,4);
INSERT INTO product_features VALUES (40,4);
INSERT INTO product_features VALUES (41,4);
INSERT INTO product_features VALUES (42,4);
INSERT INTO product_features VALUES (43,4);
INSERT INTO product_features VALUES (44,4);
INSERT INTO product_features VALUES (1,5);
INSERT INTO product_features VALUES (2,5);
INSERT INTO product_features VALUES (3,5);
INSERT INTO product_features VALUES (4,5);
INSERT INTO product_features VALUES (5,5);
INSERT INTO product_features VALUES (6,5);
INSERT INTO product_features VALUES (7,5);
INSERT INTO product_features VALUES (8,5);
INSERT INTO product_features VALUES (9,5);
INSERT INTO product_features VALUES (10,5);
INSERT INTO product_features VALUES (11,5);
INSERT INTO product_features VALUES (12,5);
INSERT INTO product_features VALUES (13,5);
INSERT INTO product_features VALUES (14,5);
INSERT INTO product_features VALUES (15,5);
INSERT INTO product_features VALUES (16,5);
INSERT INTO product_features VALUES (17,5);
INSERT INTO product_features VALUES (18,5);
INSERT INTO product_features VALUES (19,5);
INSERT INTO product_features VALUES (20,5);
INSERT INTO product_features VALUES (21,5);
INSERT INTO product_features VALUES (22,5);
INSERT INTO product_features VALUES (23,5);
INSERT INTO product_features VALUES (24,5);
INSERT INTO product_features VALUES (25,5);
INSERT INTO product_features VALUES (26,5);
INSERT INTO product_features VALUES (27,5);
INSERT INTO product_features VALUES (28,5);
INSERT INTO product_features VALUES (29,5);
INSERT INTO product_features VALUES (30,5);
INSERT INTO product_features VALUES (31,5);
INSERT INTO product_features VALUES (32,5);
INSERT INTO product_features VALUES (33,5);
INSERT INTO product_features VALUES (34,5);
INSERT INTO product_features VALUES (35,5);
INSERT INTO product_features VALUES (36,5);
INSERT INTO product_features VALUES (37,5);
INSERT INTO product_features VALUES (38,5);
INSERT INTO product_features VALUES (39,5);
INSERT INTO product_features VALUES (40,5);
INSERT INTO product_features VALUES (41,5);
INSERT INTO product_features VALUES (42,5);
INSERT INTO product_features VALUES (43,5);
INSERT INTO product_features VALUES (44,5);
INSERT INTO product_features VALUES (1,6);
INSERT INTO product_features VALUES (2,6);
INSERT INTO product_features VALUES (3,6);
INSERT INTO product_features VALUES (4,6);
INSERT INTO product_features VALUES (5,6);
INSERT INTO product_features VALUES (6,6);
INSERT INTO product_features VALUES (7,6);
INSERT INTO product_features VALUES (8,6);
INSERT INTO product_features VALUES (9,6);
INSERT INTO product_features VALUES (10,6);
INSERT INTO product_features VALUES (11,6);
INSERT INTO product_features VALUES (12,6);
INSERT INTO product_features VALUES (13,6);
INSERT INTO product_features VALUES (14,6);
INSERT INTO product_features VALUES (15,6);
INSERT INTO product_features VALUES (16,6);
INSERT INTO product_features VALUES (17,6);
INSERT INTO product_features VALUES (18,6);
INSERT INTO product_features VALUES (19,6);
INSERT INTO product_features VALUES (20,6);
INSERT INTO product_features VALUES (21,6);
INSERT INTO product_features VALUES (22,6);
INSERT INTO product_features VALUES (23,6);
INSERT INTO product_features VALUES (24,6);
INSERT INTO product_features VALUES (25,6);
INSERT INTO product_features VALUES (26,6);
INSERT INTO product_features VALUES (27,6);
INSERT INTO product_features VALUES (28,6);
INSERT INTO product_features VALUES (29,6);
INSERT INTO product_features VALUES (30,6);
INSERT INTO product_features VALUES (31,6);
INSERT INTO product_features VALUES (32,6);
INSERT INTO product_features VALUES (33,6);
INSERT INTO product_features VALUES (34,6);
INSERT INTO product_features VALUES (35,6);
INSERT INTO product_features VALUES (36,6);
INSERT INTO product_features VALUES (37,6);
INSERT INTO product_features VALUES (38,6);
INSERT INTO product_features VALUES (39,6);
INSERT INTO product_features VALUES (40,6);
INSERT INTO product_features VALUES (41,6);
INSERT INTO product_features VALUES (42,6);
INSERT INTO product_features VALUES (43,6);
INSERT INTO product_features VALUES (44,6);

--
-- Dumping data for table `product_images`
--

INSERT INTO product_images VALUES (1,1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734276754/x%C3%A1m_b%E1%BA%A1c_rqd1sp.jpg','xám_bạc_rqd1sp');
INSERT INTO product_images VALUES (2,1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_jk1pdd.jpg','đen_jk1pdd');
INSERT INTO product_images VALUES (3,1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_x%C3%A1m_prcgd8.jpg','đen_xám_prcgd8');
INSERT INTO product_images VALUES (4,1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_x%C3%A1m_2_mmnpzf.jpg','đen_xám_2_mmnpzf');
INSERT INTO product_images VALUES (5,1,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_v%C3%A0ng_zztpkw.jpg','đen_vàng_zztpkw');
INSERT INTO product_images VALUES (6,2,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/tr%E1%BA%AFng_b%E1%BA%A1c_hek1xt.png','trắng_bạc_hek1xt');
INSERT INTO product_images VALUES (7,2,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/h%E1%BB%93ng_n%C3%A2u_tr%E1%BA%AFng_qkb4nk.png','hồng_nâu_trắng_qkb4nk');
INSERT INTO product_images VALUES (8,2,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/jill1_rzybsf.png','jill1_rzybsf');
INSERT INTO product_images VALUES (9,2,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/jill2_g4t18f.png','jill2_g4t18f');
INSERT INTO product_images VALUES (10,2,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/h%E1%BB%93ng_xanh_ps8ind.png','hồng_xanh_ps8ind');
INSERT INTO product_images VALUES (11,3,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_4_nn66yb.jpg','đen_-_4_nn66yb');
INSERT INTO product_images VALUES (12,3,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_wlauyb.jpg','đen_wlauyb');
INSERT INTO product_images VALUES (13,3,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277269/x%C3%A1m_dzc9qr.jpg','xám_dzc9qr');
INSERT INTO product_images VALUES (14,3,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_3_q0z2ml.jpg','đen_-_3_q0z2ml');
INSERT INTO product_images VALUES (15,3,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_2_x4rueabc','đen_-_2_x4rue5');
INSERT INTO product_images VALUES (16,4,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277399/%C4%91en_v%C3%A0ng_hksppf.jpg','đen_vàng_hksppf');
INSERT INTO product_images VALUES (17,4,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_-_3_fnr8pn.jpg','đen_nâu_-_3_fnr8pn');
INSERT INTO product_images VALUES (18,4,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_j62wto.jpg','đen_nâu_j62wto');
INSERT INTO product_images VALUES (19,4,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_-_2_cxwqso.jpg','đen_nâu_-_2_cxwqso');
INSERT INTO product_images VALUES (20,4,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277396/%C4%91en_b%E1%BA%A1c_wsurvo.jpg','đen_bạc_wsurvo');
INSERT INTO product_images VALUES (21,5,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277740/x%C3%A1m_v%C3%A0ng_jeccdx.png','xám_vàng_jeccdx');
INSERT INTO product_images VALUES (22,5,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/tr%E1%BA%AFng_v8qajo.png','trắng_v8qajo');
INSERT INTO product_images VALUES (23,5,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/%C4%91en_ibdsyj.png','đen_ibdsyj');
INSERT INTO product_images VALUES (24,5,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/%C4%91en_v%C3%A0ng_rtkrow.png','đen_vàng_rtkrow');
INSERT INTO product_images VALUES (25,5,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/tr%E1%BA%AFng_-_2_zt7xyn.png','trắng_-_2_zt7xyn');
INSERT INTO product_images VALUES (26,6,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277946/v%C3%A0ng_x%C3%A1m_hmoagc.jpg','vàng_xám_hmoagc');
INSERT INTO product_images VALUES (27,6,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277945/trong_su%E1%BB%91t_er5q4r.jpg','trong_suốt_er5q4r');
INSERT INTO product_images VALUES (28,6,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277945/v%C3%A0ng_%C4%91en_gq0aht.jpg','vàng_đen_gq0aht');
INSERT INTO product_images VALUES (29,6,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277944/%C4%91%E1%BB%93i_m%E1%BB%93i_oujdfg.jpg','đồi_mồi_oujdfg');
INSERT INTO product_images VALUES (30,6,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277944/1_bazxqm.jpg','1_bazxqm');
INSERT INTO product_images VALUES (31,7,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278386/xanh_tr%E1%BA%AFng_ywvggk.jpg','xanh_trắng_ywvggk');
INSERT INTO product_images VALUES (32,7,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_v%C3%A0ng_f0wnjr.jpg','đen_vàng_f0wnjr');
INSERT INTO product_images VALUES (33,7,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_b%E1%BA%A1c_nsgjwp.jpg','đen_bạc_nsgjwp');
INSERT INTO product_images VALUES (34,7,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_lbi3vg.jpg','đen_lbi3vg');
INSERT INTO product_images VALUES (35,7,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278384/%C4%91en_-_2_h2sonp.jpg','đen_-_2_h2sonp');
INSERT INTO product_images VALUES (36,8,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278573/xanh_d%C6%B0%C6%A1ng_xslj7g.jpg','xanh_dương_xslj7g');
INSERT INTO product_images VALUES (37,8,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278571/xanh_d%C6%B0%C6%A1ng_-_2_ioeq3g.jpg','xanh_dương_-_2_ioeq3g');
INSERT INTO product_images VALUES (38,8,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278570/tr%E1%BA%AFng_-_2_thzmeg.jpg','trắng_-_2_thzmeg');
INSERT INTO product_images VALUES (39,8,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278570/tr%E1%BA%AFng_vz7igh.jpg','trắng_vz7igh');
INSERT INTO product_images VALUES (40,8,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278569/%C4%91en_bfiicm.jpg','đen_bfiicm');
INSERT INTO product_images VALUES (41,9,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278648/%C4%90en-1_atij1x.jpg','Đen-1_atij1x');
INSERT INTO product_images VALUES (42,9,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278647/%C4%90en_bnspxj.jpg','Đen_bnspxj');
INSERT INTO product_images VALUES (43,9,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278645/%C4%90en_b%E1%BA%A1c-1_jasabk.jpg','Đen_bạc-1_jasabk');
INSERT INTO product_images VALUES (44,9,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278644/%C4%90en_b%E1%BA%A1c_ejodch.jpg','Đen_bạc_ejodch');
INSERT INTO product_images VALUES (45,9,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278644/B%E1%BA%A1c_iful8l.jpg','Bạc_iful8l');
INSERT INTO product_images VALUES (46,10,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/x%C3%A1m_ze1n4r.jpg','xám_ze1n4r');
INSERT INTO product_images VALUES (47,10,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/%C4%91en_h%E1%BB%93ng_o8cwwl.jpg','đen_hồng_o8cwwl');
INSERT INTO product_images VALUES (48,10,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278807/%C4%91en_b%E1%BA%A1c_owdjwd.jpg','đen_bạc_owdjwd');
INSERT INTO product_images VALUES (49,10,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278806/2_asa680.jpg','2_asa680');
INSERT INTO product_images VALUES (50,10,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278805/1_c0kzfp.jpg','1_c0kzfp');
INSERT INTO product_images VALUES (51,11,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444808/v%C3%A0ng_2_lowuyi.jpg','vàng_2_lowuyi');
INSERT INTO product_images VALUES (52,11,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444809/v%C3%A0ng_cqttxx.jpg','vàng_cqttxx');
INSERT INTO product_images VALUES (53,11,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/x%C3%A1m_ze1n4r.jpg','xám_ze1n4r');
INSERT INTO product_images VALUES (54,11,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/%C4%91en_h%E1%BB%93ng_o8cwwl.jpg','đen_hồng_o8cwwl');
INSERT INTO product_images VALUES (55,11,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278807/%C4%91en_b%E1%BA%A1c_owdjwd.jpg','đen_bạc_owdjwd');
INSERT INTO product_images VALUES (56,12,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444872/%C4%91en_niirmv.jpg','đen_niirmv');
INSERT INTO product_images VALUES (57,12,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/1_fybqms.jpg','1_fybqms');
INSERT INTO product_images VALUES (58,12,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/4_yjv7yj.jpg','4_yjv7yj');
INSERT INTO product_images VALUES (59,12,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/3_njkjlo.jpg','3_njkjlo');
INSERT INTO product_images VALUES (60,12,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/2_fmjc5a.jpg','2_fmjc5a');
INSERT INTO product_images VALUES (61,13,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/v%C3%A0ng_eaehas.jpg','vàng_eaehas');
INSERT INTO product_images VALUES (62,13,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/b%E1%BA%A1c_hh3erh.jpg','bạc_hh3erh');
INSERT INTO product_images VALUES (63,13,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/%C4%91en_b%E1%BA%A1c_nl7j1a.jpg','đen_bạc_nl7j1a');
INSERT INTO product_images VALUES (64,13,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/%C4%91en_ojogjn.jpg','đen_ojogjn');
INSERT INTO product_images VALUES (65,13,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/1_ogamwx.jpg','1_ogamwx');
INSERT INTO product_images VALUES (66,14,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445054/x%C3%A1m_fourxb.jpg','xám_fourxb');
INSERT INTO product_images VALUES (67,14,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445053/olive_chhv2m.jpg','olive_chhv2m');
INSERT INTO product_images VALUES (68,14,'14_ahttps://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445052/havana_xlrzgp.jpgbc','havana_xlrzgp');
INSERT INTO product_images VALUES (69,14,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445052/2_mftjlc.jpg','2_mftjlc');
INSERT INTO product_images VALUES (70,14,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445051/1_uwickw.jpg','1_uwickw');
INSERT INTO product_images VALUES (71,15,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445305/xanh_tr%E1%BA%AFng_vgijii.jpg','xanh_trắng_vgijii');
INSERT INTO product_images VALUES (72,15,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445304/%C4%91en_uc61ji.jpg','đen_uc61ji');
INSERT INTO product_images VALUES (73,15,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445303/%C4%91en_v%C3%A0ng_nkvxnh.jpg','đen_vàng_nkvxnh');
INSERT INTO product_images VALUES (74,15,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445303/%C4%91en_b%E1%BA%A1c_cijkob.jpg','đen_bạc_cijkob');
INSERT INTO product_images VALUES (75,15,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445302/%C4%91en_-_2_ult5bw.jpg','đen_-_2_ult5bw');
INSERT INTO product_images VALUES (76,16,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445393/x%C3%A1m-2_kapwkc.jpg','xám-2_kapwkc');
INSERT INTO product_images VALUES (77,16,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445392/x%C3%A1m_blnlkc.jpg','xám_blnlkc');
INSERT INTO product_images VALUES (78,16,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445390/v%C3%A0ng_rjolv0.jpg','vàng_rjolv0');
INSERT INTO product_images VALUES (79,16,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445390/v%C3%A0ng_2_cgw2ng.jpg','vàng_2_cgw2ng');
INSERT INTO product_images VALUES (80,16,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445389/%C4%91en_nh%C3%A1m_ehw7n3.jpg','đen_nhám_ehw7n3');
INSERT INTO product_images VALUES (81,17,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445487/v%C3%A0ng_yw3dmk.jpg','vàng_yw3dmk');
INSERT INTO product_images VALUES (82,17,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445486/%C4%91en_byxzmx.jpg','đen_byxzmx');
INSERT INTO product_images VALUES (83,17,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445485/%C4%91en_b%E1%BA%A1c_fprlwa.jpg','đen_bạc_fprlwa');
INSERT INTO product_images VALUES (84,17,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445484/b%E1%BA%A1c_thor4x.jpg','bạc_thor4x');
INSERT INTO product_images VALUES (85,17,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445483/1_wuborb.jpg','1_wuborb');
INSERT INTO product_images VALUES (86,18,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445541/x%C3%A1m_d4whlf.jpg','xám_d4whlf');
INSERT INTO product_images VALUES (87,18,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445539/havana_q15vxo.jpg','havana_q15vxo');
INSERT INTO product_images VALUES (88,18,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445540/olive_ty7qgw.jpg','olive_ty7qgw');
INSERT INTO product_images VALUES (89,18,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445538/2_xxobdk.jpg','2_xxobdk');
INSERT INTO product_images VALUES (90,18,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445537/1_ezdyvo.jpg','1_ezdyvo');
INSERT INTO product_images VALUES (91,19,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445617/Xanh_r%C3%AAu_q90k7v.jpg','Xanh_rêu_q90k7v');
INSERT INTO product_images VALUES (92,19,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445616/Xanh_d%C6%B0%C6%A1ng_m0pcro.jpg','Xanh_dương_m0pcro');
INSERT INTO product_images VALUES (93,19,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445615/X%C3%A1m_h2xziv.jpg','Xám_h2xziv');
INSERT INTO product_images VALUES (94,19,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445614/V%C3%A0ng_yuzwgd.jpg','Vàng_yuzwgd');
INSERT INTO product_images VALUES (95,19,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445612/N%C3%A2u_jhvu4e.jpg','Nâu_jhvu4e');
INSERT INTO product_images VALUES (96,20,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445695/%C4%90%E1%BB%93i_m%E1%BB%93i_bfraxr.jpg','Đồi_mồi_bfraxr');
INSERT INTO product_images VALUES (97,20,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445693/%C4%90%E1%BB%93i_m%E1%BB%93i_v%C3%A0ng_yqcqpi.jpg','Đồi_mồi_vàng_yqcqpi');
INSERT INTO product_images VALUES (98,20,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445693/%C4%90en_ysc0wo.jpg','Đen_ysc0wo');
INSERT INTO product_images VALUES (99,20,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445691/%C4%90en_v%C3%A0ng_uh5yjb.jpg','Đen_vàng_uh5yjb');
INSERT INTO product_images VALUES (100,20,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445690/%C4%90en_b%C3%B3ng_wxjzph.jpg','Đen_bóng_wxjzph');
INSERT INTO product_images VALUES (101,21,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445757/Tr%E1%BA%AFng_x%C3%A1m_rjmays.jpg','Trắng_xám_rjmays');
INSERT INTO product_images VALUES (102,21,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445756/H%E1%BB%93ng_t%C3%ADm_x%C3%A1m_xq0ham.jpg','Hồng_tím_xám_xq0ham');
INSERT INTO product_images VALUES (103,21,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445754/%C4%90en_t%C3%ADm_kjxwaa.jpg','Đen_xám_o7h2jm');
INSERT INTO product_images VALUES (104,21,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445753/%C4%90en_t%C3%ADm_h%E1%BB%93ng_fcq7zd.jpg','Đen_tím_kjxwaa');
INSERT INTO product_images VALUES (105,21,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445753/%C4%90en_t%C3%ADm_h%E1%BB%93ng_fcq7zd.jpg','Đen_tím_hồng_fcq7zd');
INSERT INTO product_images VALUES (106,22,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448530/x%C3%A1m_qex702.jpg','xám_qex702');
INSERT INTO product_images VALUES (107,22,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448529/gradient_ja7bdn.jpg','gradient_ja7bdn');
INSERT INTO product_images VALUES (108,22,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448529/v%C3%A0ng_aqjm9f.jpg','vàng_aqjm9f');
INSERT INTO product_images VALUES (109,23,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448592/xanh_x%C3%A1m_ctucvt.jpg','xanh_xám_ctucvt');
INSERT INTO product_images VALUES (110,23,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448591/xanh_t%C3%ADm_fkgkea.jpg','xanh_tím_fkgkea');
INSERT INTO product_images VALUES (111,23,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448589/%C4%91en_x%C3%A1m_ozytem.jpg','đen_xám_ozytem');
INSERT INTO product_images VALUES (112,23,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448588/b%E1%BA%A1c_x%C3%A1m_vj5xvg.jpg','bạc_xám_vj5xvg');
INSERT INTO product_images VALUES (113,24,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448675/Xanh_ombre_iykzqg.jpg','Xanh_ombre_iykzqg');
INSERT INTO product_images VALUES (114,24,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448673/X%C3%A1m_br35cp.jpg','Xám_br35cp');
INSERT INTO product_images VALUES (115,24,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448674/Xanh_l%C3%A1_w0jpym.jpg','Xanh_lá_w0jpym');
INSERT INTO product_images VALUES (116,24,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448671/Tr%E1%BA%AFng_dpety6.jpg','Trắng_dpety6');
INSERT INTO product_images VALUES (117,24,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448670/%C4%90en_guojjd.jpg','Đen_guojjd');
INSERT INTO product_images VALUES (118,25,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448746/X%C3%A1m_ous1nv.jpg','Xám_ous1nv');
INSERT INTO product_images VALUES (119,25,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448745/R%C3%AAu_jxkrho.jpg','Rêu_jxkrho');
INSERT INTO product_images VALUES (120,25,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448743/%C4%90%E1%BB%93i_m%E1%BB%93i_naryll.jpg','Đồi_mồi_naryll');
INSERT INTO product_images VALUES (121,25,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448741/%C4%90en_zforoz.jpg','Đen_zforoz');
INSERT INTO product_images VALUES (122,25,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448740/%C4%90en_Tr%E1%BA%AFng_trong_vvm741.jpg','Đen_Trắng_trong_vvm741');
INSERT INTO product_images VALUES (123,26,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448830/V%C3%A0ng_xanh_tr%E1%BB%9Di_qyvks3.jpg','Vàng_xanh_trời_qyvks3');
INSERT INTO product_images VALUES (124,26,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448828/V%C3%A0ng_xanh_l%C3%A1_hcsiqq.jpg','Vàng_xanh_lá_hcsiqq');
INSERT INTO product_images VALUES (125,26,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448826/V%C3%A0ng_xanh_b%E1%BA%A1c_kjflis.jpg','Vàng_xanh_bạc_kjflis');
INSERT INTO product_images VALUES (126,26,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448825/v%C3%A0ng_b%E1%BA%A1c_n%C3%A2u_bttzu2.jpg','vàng_bạc_nâu_bttzu2');
INSERT INTO product_images VALUES (127,26,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448823/%C4%90en_x%C3%A1m_wxqpmz.jpg','Đen_xám_wxqpmz');
INSERT INTO product_images VALUES (128,27,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449132/Xanh_d%C6%B0%C6%A1ng_xsgzmb.jpg','Xanh_dương_xsgzmb');
INSERT INTO product_images VALUES (129,27,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449131/X%C3%A1m_fc4mnp.jpg','Xám_fc4mnp');
INSERT INTO product_images VALUES (130,27,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449129/V%C3%A0ng_exqtln.jpg','Vàng_exqtln');
INSERT INTO product_images VALUES (131,27,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449127/N%C3%A2u_dgxxp5.jpg','Nâu_dgxxp5');
INSERT INTO product_images VALUES (132,27,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449126/%C4%90en_b%C3%B3ng_ze0saz.jpg','Đen_bóng_ze0saz');
INSERT INTO product_images VALUES (133,28,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449267/%C4%90%E1%BB%93i_m%E1%BB%93i_zetqyi.jpg','Đồi_mồi_zetqyi');
INSERT INTO product_images VALUES (134,28,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449265/%C4%90%E1%BB%93i_m%E1%BB%93i_v%C3%A0ng_b42cvk.jpg','Đồi_mồi_vàng_b42cvk');
INSERT INTO product_images VALUES (135,28,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449263/%C4%90en_gb0yzi.jpg','Đen_gb0yzi');
INSERT INTO product_images VALUES (136,28,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449261/%C4%90en_v%C3%A0ng_zycbwi.jpg','Đen_vàng_zycbwi');
INSERT INTO product_images VALUES (137,28,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449260/%C4%90en_b%C3%B3ng_nyfw0u.jpg','Đen_bóng_nyfw0u');
INSERT INTO product_images VALUES (138,29,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449346/X%C3%A1m_imxdik.jpg','Xám_imxdik');
INSERT INTO product_images VALUES (139,29,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449344/R%C3%AAu_yr2qhb.jpg','Rêu_yr2qhb');
INSERT INTO product_images VALUES (140,29,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449342/%C4%90%E1%BB%93i_m%E1%BB%93i_f435cj.jpg','Đồi_mồi_f435cj');
INSERT INTO product_images VALUES (141,29,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449340/%C4%90en_kmj8en.jpg','Đen_kmj8en');
INSERT INTO product_images VALUES (142,29,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449338/%C4%90en_Tr%E1%BA%AFng_trong_weqkz2.jpg','Đen_Trắng_trong_weqkz2');
INSERT INTO product_images VALUES (143,30,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450154/V%C3%A0ng_xanh_tr%E1%BB%9Di_kzukym.jpg','Vàng_xanh_trời_kzukym');
INSERT INTO product_images VALUES (144,30,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450152/V%C3%A0ng_xanh_b%E1%BA%A1c_bzq4c6.jpg','Vàng_xanh_bạc_bzq4c6');
INSERT INTO product_images VALUES (145,30,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450149/v%C3%A0ng_b%E1%BA%A1c_n%C3%A2u_r4clei.jpg','vàng_bạc_nâu_r4clei');
INSERT INTO product_images VALUES (146,30,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450148/%C4%90en_x%C3%A1m_ewpbed.jpg','Đen_xám_ewpbed');
INSERT INTO product_images VALUES (147,30,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450147/B%E1%BA%A1c_xanh_da_tr%E1%BB%9Di_vszyzr.jpg','Bạc_xanh_da_trời_vszyzr');
INSERT INTO product_images VALUES (148,31,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450346/Tr%C3%B2ng_k%C3%ADnh_si%C3%AAu_m%E1%BB%8Fng_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_Chemi_Perfect_UV_U6_1.74_wbxp46.jpg','Tròng_kính_siêu_mỏng_kiểm_soát_ánh_sáng_xanh_Chemi_Perfect_UV_U6_1.74_wbxp46');
INSERT INTO product_images VALUES (149,31,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450344/Tr%C3%B2ng_k%C3%ADnh_Phi_C%E1%BA%A7u_Hai_M%E1%BA%B7t_Chemi_Double_Aspheric_dsyzvl.jpg','Tròng_kính_Phi_Cầu_Hai_Mặt_Chemi_Double_Aspheric_dsyzvl');
INSERT INTO product_images VALUES (150,31,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450342/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_%C4%91%C3%A1nh_Chemi_Single_Vision_Lens_klp9n0.jpg','Tròng_kính_Đơn_tròng_đánh_Chemi_Single_Vision_Lens_klp9n0');
INSERT INTO product_images VALUES (151,31,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450340/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_%C4%91%C3%A1nh_Chemi_RX_1.74_ASP_U6_lvz94k.jpg','Tròng_kính_Đơn_tròng_đánh_Chemi_RX_1.74_ASP_U6_lvz94k');
INSERT INTO product_images VALUES (152,31,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450338/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_olg6qk.jpg','Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Kiểm_soát_ánh_sáng_xanh_olg6qk');
INSERT INTO product_images VALUES (153,32,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450449/Tr%C3%B2ng_k%C3%ADnh_Phi_C%E1%BA%A7u_Hai_M%E1%BA%B7t_Chemi_Double_Aspheric_jqszhf.jpg','Tròng_kính_Phi_Cầu_Hai_Mặt_Chemi_Double_Aspheric_jqszhf');
INSERT INTO product_images VALUES (154,32,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450447/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_Chemi_1.67_ASP_PhotoBlue_isiak6.jpg','Tròng_kính_đổi_màu_kiểm_soát_ánh_sáng_xanh_Chemi_1.67_ASP_PhotoBlue_isiak6');
INSERT INTO product_images VALUES (155,32,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450444/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_yinobx.jpg','Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Kiểm_soát_ánh_sáng_xanh_yinobx');
INSERT INTO product_images VALUES (156,32,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450442/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_haaoyp.jpg','Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.60_Kiểm_soát_ánh_sáng_xanh_haaoyp');
INSERT INTO product_images VALUES (157,32,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450440/Tr%C3%B2ng_K%C3%ADnh_CHEMI_1.56_ASP_BLUE_BLOCK_UV420_SPIN_PHOTOGREY_SHMC_vfbyyx.jpg','Tròng_Kính_CHEMI_1.56_ASP_BLUE_BLOCK_UV420_SPIN_PHOTOGREY_SHMC_vfbyyx');
INSERT INTO product_images VALUES (158,33,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450546/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_5_rrepv4.jpg','Tròng_kính_Elements_Blue_UV_Cut_5_rrepv4');
INSERT INTO product_images VALUES (159,33,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450544/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_3_nemosn.jpg','Tròng_kính_Elements_Blue_UV_Cut_3_nemosn');
INSERT INTO product_images VALUES (160,33,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450542/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_2_s1xme0.jpg','Tròng_kính_Elements_Blue_UV_Cut_2_s1xme0');
INSERT INTO product_images VALUES (161,33,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450540/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_1_diazz9.jpg','Tròng_kính_Elements_Blue_UV_Cut_1_diazz9');
INSERT INTO product_images VALUES (162,33,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450538/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_sdwylv.jpg','Tròng_kính_Elements_Blue_UV_Cut_sdwylv');
INSERT INTO product_images VALUES (163,34,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450646/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_5_gvgdch.jpg','Tròng_kính_Elements_Blue_UV_Cut_Night_AR_5_gvgdch');
INSERT INTO product_images VALUES (164,34,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450644/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_3_khq6xe.jpg','Tròng_kính_Elements_Blue_UV_Cut_Night_AR_3_khq6xe');
INSERT INTO product_images VALUES (165,34,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450641/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_2_sqw0gw.jpg','Tròng_kính_Elements_Blue_UV_Cut_Night_AR_2_sqw0gw');
INSERT INTO product_images VALUES (166,34,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450639/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_1_vhjw1v.jpg','Tròng_kính_Elements_Blue_UV_Cut_Night_AR_1_vhjw1v');
INSERT INTO product_images VALUES (167,34,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450637/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_6_pnn2n0.jpg','Tròng_kính_Elements_Blue_UV_Cut_6_pnn2n0');
INSERT INTO product_images VALUES (168,35,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450755/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_qwxqi4.jpg','Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_4_qwxqi4');
INSERT INTO product_images VALUES (169,35,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450753/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_bbamtd.jpg','Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_3_bbamtd');
INSERT INTO product_images VALUES (170,35,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450751/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_zm80eq.jpg','Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_2_zm80eq');
INSERT INTO product_images VALUES (171,35,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450749/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_latzat.jpg','Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_1_latzat');
INSERT INTO product_images VALUES (172,36,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450829/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_4_tf3b0d.jpg','Tròng_kính_Essilor_Eyezen_Max_Az_4_tf3b0d');
INSERT INTO product_images VALUES (173,36,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450826/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_3_terjtd.jpg','Tròng_kính_Essilor_Eyezen_Max_Az_3_terjtd');
INSERT INTO product_images VALUES (174,36,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450824/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_2_q7nrjj.jpg','Tròng_kính_Essilor_Eyezen_Max_Az_2_q7nrjj');
INSERT INTO product_images VALUES (175,37,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450913/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_ti9vjq.jpg','Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_3_ti9vjq');
INSERT INTO product_images VALUES (176,37,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450910/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_zcoquh.jpg','Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_2_zcoquh');
INSERT INTO product_images VALUES (177,37,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450908/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_qemh23.jpg','Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_1_qemh23');
INSERT INTO product_images VALUES (178,38,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450971/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_vhb6az.jpg','Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_4_vhb6az');
INSERT INTO product_images VALUES (179,38,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450968/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_rcuvc7.jpg','Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_3_rcuvc7');
INSERT INTO product_images VALUES (180,38,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450966/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_m24xkf.jpg','Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_2_m24xkf');
INSERT INTO product_images VALUES (181,38,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450963/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_swsp4g.jpg','Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_1_swsp4g');
INSERT INTO product_images VALUES (182,39,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451030/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_w7ofxh.jpg','Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_3_w7ofxh');
INSERT INTO product_images VALUES (183,39,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451028/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_voor2g.jpg','Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_2_voor2g');
INSERT INTO product_images VALUES (184,39,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451026/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_m6asyw.jpg','Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_1_m6asyw');
INSERT INTO product_images VALUES (185,40,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451074/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_5_jnp7qv.jpg','Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_5_jnp7qv');
INSERT INTO product_images VALUES (186,40,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451071/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_ibvbks.jpg','Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_4_ibvbks');
INSERT INTO product_images VALUES (187,41,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451126/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_5_jccgjw.jpg','Tròng_kính_đổi_màu_VisionX_Singapore_5_jccgjw');
INSERT INTO product_images VALUES (188,41,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451124/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_4_o2pq90.jpg','Tròng_kính_đổi_màu_VisionX_Singapore_4_o2pq90');
INSERT INTO product_images VALUES (189,42,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451121/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_3_jkral4.jpg','Tròng_kính_đổi_màu_VisionX_Singapore_3_jkral4');
INSERT INTO product_images VALUES (190,42,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451118/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_2_xu4dla.jpg','Tròng_kính_đổi_màu_VisionX_Singapore_2_xu4dla');
INSERT INTO product_images VALUES (191,42,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451116/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_1_kmkonu.jpg','Tròng_kính_đổi_màu_VisionX_Singapore_1_kmkonu');
INSERT INTO product_images VALUES (192,43,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451211/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_5_zo8euz.jpg','Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_5_zo8euz');
INSERT INTO product_images VALUES (193,43,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451208/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_4_erarqm.jpg','Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_4_erarqm');
INSERT INTO product_images VALUES (194,43,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451206/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_3_zzc91z.jpg','Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_3_zzc91z');
INSERT INTO product_images VALUES (195,43,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451203/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_1_fimd62.jpg','Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_1_fimd62');
INSERT INTO product_images VALUES (196,44,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451200/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_4_rwhcys.jpg','Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_4_rwhcys');
INSERT INTO product_images VALUES (197,44,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451198/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_3_n2rqsu.jpg','Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_3_n2rqsu');
INSERT INTO product_images VALUES (198,44,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451195/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_2_m5nee6.jpg','Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_2_m5nee6');
INSERT INTO product_images VALUES (199,44,'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451192/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_1_hvhx3m.jpg','Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_1_hvhx3m');

--
-- Dumping data for table `product_reviews`
--

INSERT INTO product_reviews VALUES (1,1,5,'5','Chất lượng tuyệt vời, rất hài lòng!','active');
INSERT INTO product_reviews VALUES (2,1,6,'4','Thiết kế đẹp, giá hợp lý.','active');
INSERT INTO product_reviews VALUES (3,1,7,'3','Hơi nặng nhưng ổn.','active');
INSERT INTO product_reviews VALUES (4,2,8,'5','Đeo rất thoải mái.','active');
INSERT INTO product_reviews VALUES (5,2,9,'4','Hàng tốt, giao hơi chậm.','active');
INSERT INTO product_reviews VALUES (6,3,10,'5','Nhẹ và dễ chịu khi đeo cả ngày.','active');
INSERT INTO product_reviews VALUES (7,3,11,'4','Thiết kế ổn nhưng hơi đơn giản.','active');
INSERT INTO product_reviews VALUES (8,4,12,'5','Thiết kế hiện đại, đáng giá.','active');
INSERT INTO product_reviews VALUES (9,4,13,'4','Bền nhưng hơi cứng.','active');
INSERT INTO product_reviews VALUES (10,5,14,'5','Hơn cả mong đợi!','active');
INSERT INTO product_reviews VALUES (11,5,15,'4','Đáng tiền, thời trang.','active');
INSERT INTO product_reviews VALUES (12,6,16,'5','Sang trọng, rất hài lòng.','active');
INSERT INTO product_reviews VALUES (13,6,17,'3','Không hợp với mặt tôi.','active');
INSERT INTO product_reviews VALUES (14,7,18,'5','Rất phù hợp cho mọi dịp.','active');
INSERT INTO product_reviews VALUES (15,7,19,'4','Thời trang, chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (16,8,20,'5','Giá tốt, chất lượng ổn.','active');
INSERT INTO product_reviews VALUES (17,8,21,'4','Đeo rất thoải mái.','active');
INSERT INTO product_reviews VALUES (18,9,22,'4','Tạm ổn, mong cải thiện.','active');
INSERT INTO product_reviews VALUES (19,9,23,'5','Rất đáng mua.','active');
INSERT INTO product_reviews VALUES (20,10,24,'5','Đẹp, thoải mái khi đeo.','active');
INSERT INTO product_reviews VALUES (21,10,25,'4','Ổn, nhưng hơi đắt.','active');
INSERT INTO product_reviews VALUES (22,11,26,'5','Thời trang, phù hợp giá.','active');
INSERT INTO product_reviews VALUES (23,11,27,'4','Bền và nhẹ, rất ưng.','active');
INSERT INTO product_reviews VALUES (24,12,28,'5','Bền, xứng giá tiền.','active');
INSERT INTO product_reviews VALUES (25,12,29,'4','Thiết kế đẹp nhưng hơi nặng.','active');
INSERT INTO product_reviews VALUES (26,13,30,'5','Tuyệt vời khi đeo lâu.','active');
INSERT INTO product_reviews VALUES (27,13,31,'4','Chất lượng ổn.','active');
INSERT INTO product_reviews VALUES (28,14,32,'5','Sang trọng, nhẹ.','active');
INSERT INTO product_reviews VALUES (29,14,33,'4','Vừa vặn, chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (30,15,5,'5','Thời trang, thoải mái.','active');
INSERT INTO product_reviews VALUES (31,15,6,'4','Giá ổn, chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (32,16,7,'5','Tuyệt vời, rất hài lòng.','active');
INSERT INTO product_reviews VALUES (33,16,8,'4','Nhẹ, dễ chịu.','active');
INSERT INTO product_reviews VALUES (34,17,9,'5','Khuyên dùng, rất đáng giá.','active');
INSERT INTO product_reviews VALUES (35,17,10,'4','Đẹp nhưng hơi đắt.','active');
INSERT INTO product_reviews VALUES (36,18,11,'5','Rất đẹp, phù hợp.','active');
INSERT INTO product_reviews VALUES (37,18,12,'4','Ổn, giá hợp lý.','active');
INSERT INTO product_reviews VALUES (38,19,13,'5','Sang trọng và chắc chắn.','active');
INSERT INTO product_reviews VALUES (39,19,14,'4','Giá cao nhưng đáng tiền.','active');
INSERT INTO product_reviews VALUES (40,20,15,'5','Nhẹ và thời trang.','active');
INSERT INTO product_reviews VALUES (41,20,16,'4','Thiết kế đẹp.','active');
INSERT INTO product_reviews VALUES (42,21,17,'5','Sản phẩm tốt, hài lòng.','active');
INSERT INTO product_reviews VALUES (43,21,18,'4','Đẹp, nhưng hơi nặng.','active');
INSERT INTO product_reviews VALUES (44,22,19,'5','Đeo rất thích, chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (45,22,20,'4','Giá ổn.','active');
INSERT INTO product_reviews VALUES (46,23,21,'5','Rất tốt, rất thích.','active');
INSERT INTO product_reviews VALUES (47,23,22,'4','Ổn trong tầm giá.','active');
INSERT INTO product_reviews VALUES (48,24,23,'5','Tuyệt vời cho mùa hè.','active');
INSERT INTO product_reviews VALUES (49,24,24,'4','Bền và đẹp.','active');
INSERT INTO product_reviews VALUES (50,25,25,'5','Chất lượng cao, rất hài lòng.','active');
INSERT INTO product_reviews VALUES (51,25,26,'4','Giá hơi cao nhưng ổn.','active');
INSERT INTO product_reviews VALUES (52,26,27,'5','Nhẹ, phù hợp cho mọi dịp.','active');
INSERT INTO product_reviews VALUES (53,26,28,'4','Hợp lý trong tầm giá.','active');
INSERT INTO product_reviews VALUES (54,27,29,'5','Đeo cả ngày không mỏi.','active');
INSERT INTO product_reviews VALUES (55,27,30,'4','Đẹp, giá ổn.','active');
INSERT INTO product_reviews VALUES (56,28,31,'5','Sang trọng, bền.','active');
INSERT INTO product_reviews VALUES (57,28,32,'4','Ổn định và chất lượng.','active');
INSERT INTO product_reviews VALUES (58,29,33,'5','Đẹp và nhẹ.','active');
INSERT INTO product_reviews VALUES (59,29,5,'4','Chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (60,30,6,'5','Phù hợp cho công việc.','active');
INSERT INTO product_reviews VALUES (61,30,7,'4','Đẹp và chắc chắn.','active');
INSERT INTO product_reviews VALUES (62,31,8,'5','Chất lượng vượt mong đợi.','active');
INSERT INTO product_reviews VALUES (63,31,9,'4','Thiết kế đẹp.','active');
INSERT INTO product_reviews VALUES (64,32,10,'5','Tốt, rất hài lòng.','active');
INSERT INTO product_reviews VALUES (65,32,11,'4','Bền và nhẹ.','active');
INSERT INTO product_reviews VALUES (66,33,12,'5','Đáng mua.','active');
INSERT INTO product_reviews VALUES (67,33,13,'4','Đẹp nhưng hơi đắt.','active');
INSERT INTO product_reviews VALUES (68,34,14,'5','Nhẹ và bền.','active');
INSERT INTO product_reviews VALUES (69,34,15,'4','Chất lượng hợp lý.','active');
INSERT INTO product_reviews VALUES (70,35,16,'5','Tốt, phù hợp.','active');
INSERT INTO product_reviews VALUES (71,35,17,'4','Giá hơi cao.','active');
INSERT INTO product_reviews VALUES (72,36,18,'5','Thiết kế tuyệt vời.','active');
INSERT INTO product_reviews VALUES (73,36,19,'4','Ổn trong tầm giá.','active');
INSERT INTO product_reviews VALUES (74,37,20,'5','Rất hài lòng.','active');
INSERT INTO product_reviews VALUES (75,37,21,'4','Chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (76,38,22,'5','Sản phẩm chất lượng.','active');
INSERT INTO product_reviews VALUES (77,38,23,'4','Đáng tiền.','active');
INSERT INTO product_reviews VALUES (78,39,24,'5','Nhẹ, đẹp.','active');
INSERT INTO product_reviews VALUES (79,39,25,'4','Rất tốt.','active');
INSERT INTO product_reviews VALUES (80,40,26,'5','Đáng giá.','active');
INSERT INTO product_reviews VALUES (81,40,27,'4','Ổn định.','active');
INSERT INTO product_reviews VALUES (82,41,28,'5','Tuyệt vời.','active');
INSERT INTO product_reviews VALUES (83,41,29,'4','Chất lượng cao.','active');
INSERT INTO product_reviews VALUES (84,42,30,'5','Tốt, rất thích.','active');
INSERT INTO product_reviews VALUES (85,42,31,'4','Hài lòng.','active');
INSERT INTO product_reviews VALUES (86,43,32,'5','Chất lượng tốt.','active');
INSERT INTO product_reviews VALUES (87,43,33,'4','Hơi nặng.','active');
INSERT INTO product_reviews VALUES (88,44,5,'5','Đáng mua, rất đẹp.','active');
INSERT INTO product_reviews VALUES (89,44,6,'4','Ổn.','active');

--
-- Dumping data for table `products`
--

INSERT INTO products VALUES (1,'Gọng kính BOLON BT1529','Viền trên đậm - Nhựa và kim loại',1,1,1,1,'unisex',500000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (2,'Gọng kính JILL STUART JL33072','Đa giác - Titanium',1,2,2,2,'unisex',550000.00,500000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (3,'Gọng kính MERCURY LV87137','Vuông - Kim loại Titan',1,3,3,3,'unisex',600000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (4,'Gọng kính MERCURY LV87297','Viền trên đậm - Kim loại Titan',1,4,3,1,'unisex',650000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (5,'Gọng kính MOLSON MJ6119','Đa giác - Nhựa và kim loại',1,1,1,2,'unisex',700000.00,650000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (6,'Gọng kính MOLSON MJ6156','Tròn - Titanium',1,2,2,3,'unisex',750000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (7,'Gọng kính MOLSON MJ7272','Đa giác - Kim loại Titan',1,3,3,2,'unisex',1100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (8,'Gọng kính NEWBALANCE NB09361X','Chữ nhật - Nhựa và kim loại',1,4,1,1,'unisex',1200000.00,1150000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (9,'Gọng kính POVINO PO6843','Nhựa và kim loại - Vuông',1,1,2,3,'unisex',1300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (10,'Gọng kính POVINO PO22641','Tròn - Nhựa và kim loại',1,2,1,2,'unisex',1400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (11,'Gọng kính POVINO ST9380','Chữ nhật - Kim loại Titan',1,3,3,1,'unisex',1500000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (12,'Gọng kính RAYBAN RB6346-2904','Viền trên đậm - Titanium',1,4,2,3,'unisex',1600000.00,1550000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (13,'Gọng kính RAYBAN RX8763','Titanium - Chữ nhật',1,1,2,1,'unisex',2100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (14,'Gọng kính SEESON THE CLASSICS JIL','Vuông - Nhựa acetate',1,2,1,3,'unisex',2200000.00,2100000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (15,'Gọng kính MOLSON MJ7272','Đa giác - Kim loại Titan',1,3,3,2,'unisex',2300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (16,'Gọng kính POVINO ST9380','Chữ nhật - Kim loại Titan',1,4,3,1,'unisex',2400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (17,'Gọng kính RAYBAN RX8763','Titanium - Chữ nhật',1,1,2,1,'unisex',2500000.00,2400000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (18,'Gọng kính SEESON THE CLASSICS JIL','Vuông - Nhựa acetate',1,2,1,3,'unisex',2500000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (19,'Kính mát BOLON BL3038','Nhựa Acetate',2,1,1,1,'unisex',500000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (20,'Kính mát BOLON BL3100','Nhựa và Kim loại',2,2,2,2,'unisex',600000.00,550000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (21,'Kính mát BOLON BL5078','TR90',2,3,3,1,'unisex',700000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (22,'Kính mát BOLON BL7191','Hợp kim',2,4,2,2,'unisex',800000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (23,'Kính mát BOLON BV1025','Titanium',2,1,3,3,'unisex',1100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (24,'Kính mát POVINO NN107','Nhựa Acetate',2,2,1,1,'unisex',1200000.00,1150000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (25,'Kính mát POVINO SJMM68RX','Nhựa và Kim loại',2,3,2,2,'unisex',1300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (26,'Kính mát RAYBAN RB3625','Kim loại',2,4,3,3,'unisex',1400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (27,'Kính mát BOLON BL3038','Nhựa Acetate',2,1,1,1,'unisex',2100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (28,'Kính mát BOLON BL3100','Nhựa và Kim loại',2,2,2,2,'unisex',2200000.00,2100000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (29,'Kính mát POVINO SJMM68RX','Nhựa và Kim loại',2,3,2,3,'unisex',2300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (30,'Kính mát RAYBAN RB3625','Kim loại',2,4,3,2,'unisex',2400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (31,'Tròng kính Chemi - Siêu mỏng','Siêu mỏng - Tròng kính cao cấp',3,1,1,1,'unisex',500000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (32,'Tròng kính Chemi - Chống UV','Chống tia UV - Tròng kính chính hãng',3,1,2,2,'unisex',600000.00,550000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (33,'Tròng kính Element - Siêu mỏng','Siêu mỏng - Bảo vệ mắt tối đa',3,2,1,3,'unisex',700000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (34,'Tròng kính Element - Chống UV','Chống UV - Đảm bảo an toàn mắt',3,2,3,1,'unisex',800000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (35,'Tròng kính ESSILOR - Đổi màu','Đổi màu - Chống ánh sáng xanh',3,3,3,2,'unisex',1100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (36,'Tròng kính ESSILOR - Siêu mỏng','Siêu mỏng - Tròng kính nhẹ và bền',3,3,2,3,'unisex',1200000.00,1150000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (37,'Tròng kính HOYA - Đổi màu','Đổi màu - Bảo vệ mắt khỏi ánh sáng xanh',3,4,3,1,'unisex',1300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (38,'Tròng kính HOYA - Chống UV','Chống tia UV - Đảm bảo an toàn',3,4,2,2,'unisex',1400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (39,'Tròng kính Kodax - Chống UV','Chống UV - Độ bền cao',3,2,3,3,'unisex',2100000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (40,'Tròng kính Kodax - Đổi màu','Đổi màu thông minh, bảo vệ mắt',3,1,1,1,'unisex',2200000.00,2100000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (41,'Tròng kính VisionX - Siêu mỏng','Siêu mỏng, nhẹ, tiện dụng',3,3,2,2,'unisex',2300000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (42,'Tròng kính VisionX - Đổi màu','Đổi màu linh hoạt, chống chói',3,2,3,3,'unisex',2400000.00,NULL,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (43,'Tròng kính ZEISS - Chống UV','Công nghệ chống UV tiên tiến',3,1,1,2,'unisex',2500000.00,2400000.00,'2024-12-19 04:04:02','active');
INSERT INTO products VALUES (44,'Tròng kính ZEISS - Siêu mỏng','Siêu mỏng, sang trọng và bền bỉ',3,3,3,3,'unisex',2600000.00,NULL,'2024-12-19 04:04:02','active');

--
-- Dumping data for table `role`
--

INSERT INTO role VALUES (1,'admin',NULL,NULL);
INSERT INTO role VALUES (2,'manager',NULL,NULL);
INSERT INTO role VALUES (3,'customer',NULL,NULL);

--
-- Dumping data for table `sessions`
--


--
-- Dumping data for table `shapes`
--

INSERT INTO shapes VALUES (1,'Đa giác','active');
INSERT INTO shapes VALUES (2,'Vuông','active');
INSERT INTO shapes VALUES (3,'Chữ nhật','active');
INSERT INTO shapes VALUES (4,'Browline','active');
INSERT INTO shapes VALUES (5,'Oval','active');
INSERT INTO shapes VALUES (6,'Phi công','active');

--
-- Dumping data for table `users`
--

INSERT INTO users VALUES (1,'$2y$12$e4Pk2kJkVYO6ytjS9mrEve5IvR6oM921eJ9VYaJ2oJLTx7miMdqfO','Hoàng','Quốc Khánh','admin@gmail.com','2024-12-18 21:03:51',1,'0323456789','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:51','active',NULL);
INSERT INTO users VALUES (2,'$2y$12$FDIvInilKgYVEyPk6QWOT.6oF.68Jbltr9eMgmdT7ZCHoqrzgVVQW','Hồ','Bình Minh','managerhcm@gmail.com','2024-12-18 21:03:51',2,'0323456788','3569 Đường Phạm Văn Đồng, Phường Linh Đông, Thành phố Thủ Đức, Hồ Chí Minh','2024-12-18 21:03:51','active',NULL);
INSERT INTO users VALUES (3,'$2y$12$00pxdPUXmhLs0uXIi88UAOl8EPHXFVBx35Ph2jfUj8EEtZEjPOMcm','Phạm','Bình Minh','managerdn@gmail.com','2024-12-18 21:03:51',2,'0323456787','123 Đường Nguyễn Văn Linh, Phường Hải Châu 1, Quận Hải Châu, Đà Nẵng','2024-12-18 21:03:51','active',NULL);
INSERT INTO users VALUES (4,'$2y$12$D8z9.txN17LkgYZXoq7X9O9daSWHM.Ze0tYz0Z9New9Y1gRy9jQbW','Nguyễn','Đức Hùng','managerhn@gmail.com','2024-12-18 21:03:52',2,'0323456786','80 Đường Láng Hạ, Phường Thành Công, Quận Ba Đình, Hà Nội','2024-12-18 21:03:52','active',NULL);
INSERT INTO users VALUES (5,'$2y$12$FX9k/jVwaMKkIVRqhr/aSetPUpHf5Ly6w.YBA1D0sSLcj1i7TY.7G','Đào','Quang Bảo','customer1@gmail.com','2024-12-18 21:03:52',3,'0976337232','40 Đường Phan Bội Châu, Thị trấn Gia Nghĩa, Thành phố Gia Nghĩa, Đắk Nông','2024-12-18 21:03:52','active',NULL);
INSERT INTO users VALUES (6,'$2y$12$Nhv.60jORvOOgSpQH.Pq6esJ/YECVeneSxPDvl48lw5aQANPP4gGK','Hồ','Bình Minh','customer2@gmail.com','2024-12-18 21:03:52',3,'0863520414','30 Đường Quang Trung, Thị trấn Krông Klang, Huyện Đakrông, Quảng Trị','2024-12-18 21:03:52','active',NULL);
INSERT INTO users VALUES (7,'$2y$12$NO8YNWR09quiSc0.RfkQjuMU4CNpapXGS6uEYZeSdPjsOu0x7zv.a','Đặng','Quang Bảo','customer3@gmail.com','2024-12-18 21:03:52',3,'0525375693','60 Đường Hoàng Diệu, Thị trấn Tân Phú, Huyện Tân Phú, Đồng Nai','2024-12-18 21:03:52','active',NULL);
INSERT INTO users VALUES (8,'$2y$12$VpPSTNfXQ5BCFPmMczRRfuVYQH0NqHtn6fY8A0MMDmGzyCssuZRk2','Đào','Minh Chính','customer4@gmail.com','2024-12-18 21:03:53',3,'0724152432','20 Đường Trần Phú, Thị trấn Đức An, Huyện Đăk Song, Đắk Nông','2024-12-18 21:03:53','active',NULL);
INSERT INTO users VALUES (9,'$2y$12$ekVH3hHfC7xnP2q0A5WK2uNM9tRN0TvxaXnGFEp8Fr2aN/XEmyWsy','Đặng','Ánh Sao','customer5@gmail.com','2024-12-18 21:03:53',3,'0720309495','150 Đường Hùng Vương, Thị trấn Đông Hà, Huyện Cam Lộ, Quảng Trị','2024-12-18 21:03:53','active',NULL);
INSERT INTO users VALUES (10,'$2y$12$Bec7YBa47dt8V4cOyb9qleNBe/TT8TavJij6XlQhWNx8/YGwdrega','Phạm','Quang Bảo','customer6@gmail.com','2024-12-18 21:03:53',3,'0974092596','120 Đường Trần Phú, Phường Máy Chai, Quận Ngô Quyền, Hải Phòng','2024-12-18 21:03:53','active',NULL);
INSERT INTO users VALUES (11,'$2y$12$fJDPeDX3tYKeLTdu947J9.0.LBWkFvcKY4ZlCaD9DvBQ5DZc3ujZa','Nguyễn','Minh Chính','customer7@gmail.com','2024-12-18 21:03:54',3,'0539707644','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:54','active',NULL);
INSERT INTO users VALUES (12,'$2y$12$2.ZSIKgjs2BOmw1VT2yN3utv5DJA7xXgcTOPPEsyOWVvxctzORrVC','Phạm','Tiến Đạt','customer8@gmail.com','2024-12-18 21:03:54',3,'0889790596','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:54','active',NULL);
INSERT INTO users VALUES (13,'$2y$12$PyqG/vmqvMpE59i9LfF6k.Ne3N0UNPaOxQqoIFh8i5j52tjAVVQwW','Nguyễn','Phương Tuấn','customer9@gmail.com','2024-12-18 21:03:54',3,'0749720861','40 Đường Phan Bội Châu, Thị trấn Gia Nghĩa, Thành phố Gia Nghĩa, Đắk Nông','2024-12-18 21:03:54','active',NULL);
INSERT INTO users VALUES (14,'$2y$12$QMr8uTxfdywLolDztEEEf.YLB/HCcVGWP.mCwfPGOvRdZRcUvMZNK','Phạm','Tiến Đạt','customer10@gmail.com','2024-12-18 21:03:54',3,'0361887933','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:54','active',NULL);
INSERT INTO users VALUES (15,'$2y$12$ch8XtN8UiFZMA5jIFgnlSOFFd6qnad4WaWSYYLgPvL2zzzChA9l8C','Nguyễn','Đức Hùng','customer11@gmail.com','2024-12-18 21:03:55',3,'0983434607','3569 Đường Phạm Văn Đồng, Phường Linh Đông, Thành phố Thủ Đức, Hồ Chí Minh','2024-12-18 21:03:55','active',NULL);
INSERT INTO users VALUES (16,'$2y$12$fR4YoYqzgticWJnMVQNeO.oGADc5DPBb8fmNUvALSdlovfpSuRlmK','Phạm','Minh Chính','customer12@gmail.com','2024-12-18 21:03:55',3,'0827949487','60 Đường Hoàng Diệu, Thị trấn Tân Phú, Huyện Tân Phú, Đồng Nai','2024-12-18 21:03:55','active',NULL);
INSERT INTO users VALUES (17,'$2y$12$QM6hEjWFTALTnLPJ4BCXa.uJ4WOuEwmEOrq.Cp5lBkmZkMmSfoy2K','Phạm','Quang Bảo','customer13@gmail.com','2024-12-18 21:03:55',3,'0782038996','120 Đường Trần Phú, Phường Máy Chai, Quận Ngô Quyền, Hải Phòng','2024-12-18 21:03:55','active',NULL);
INSERT INTO users VALUES (18,'$2y$12$voXAZ6RsY61dtRxo0dA22ulTbLCK8TNqMVnmxqPDM3YXPCXzX6dUq','Hồ','Bình Minh','customer14@gmail.com','2024-12-18 21:03:55',3,'0301901761','60 Đường Hoàng Diệu, Thị trấn Tân Phú, Huyện Tân Phú, Đồng Nai','2024-12-18 21:03:55','active',NULL);
INSERT INTO users VALUES (19,'$2y$12$znNBLsMeF7XCWVPaEV/chOcwhhpogT.vqKb1x/NmqtgBKHuutjHiG','Phạm','Đức Hùng','customer15@gmail.com','2024-12-18 21:03:56',3,'0816607809','40 Đường Phan Bội Châu, Thị trấn Gia Nghĩa, Thành phố Gia Nghĩa, Đắk Nông','2024-12-18 21:03:56','active',NULL);
INSERT INTO users VALUES (20,'$2y$12$EYjIEhdDRp1s8mJ7oZXABef6Ss/ARgUTL/6.08m5U85DNNZ/zOD/a','Hoàng','Minh Chính','customer16@gmail.com','2024-12-18 21:03:56',3,'0791164175','70 Đường Nguyễn Trãi, Thị trấn Phước Bửu, Huyện Xuyên Mộc, Bà Rịa - Vũng Tàu','2024-12-18 21:03:56','active',NULL);
INSERT INTO users VALUES (21,'$2y$12$EQSjH33LBg0fND1i95cBF.lnMH4p0zqMr8NUXQ8ETXS.fFWaMLhaG','Đào','Bình Minh','customer17@gmail.com','2024-12-18 21:03:56',3,'0383875080','150 Đường Hùng Vương, Thị trấn Đông Hà, Huyện Cam Lộ, Quảng Trị','2024-12-18 21:03:56','active',NULL);
INSERT INTO users VALUES (22,'$2y$12$BvFwqwYouAOUV7d0IHSlGuAwHf.ppqxfHi5XSFpwsVL97QbvEjyD6','Nguyễn','Đức Hùng','customer18@gmail.com','2024-12-18 21:03:56',3,'0597157945','150 Đường Hùng Vương, Thị trấn Đông Hà, Huyện Cam Lộ, Quảng Trị','2024-12-18 21:03:56','active',NULL);
INSERT INTO users VALUES (23,'$2y$12$z9QudyD8hEieyiK9Yp.md.cg2KtnFs8ewIKE3NHZQbENq0fuUGOei','Nguyễn','Tiến Đạt','customer19@gmail.com','2024-12-18 21:03:57',3,'0857215060','60 Đường Hoàng Diệu, Thị trấn Tân Phú, Huyện Tân Phú, Đồng Nai','2024-12-18 21:03:57','active',NULL);
INSERT INTO users VALUES (24,'$2y$12$LrYmr8rP/mwPJnaJp43U4.NI9Zgj8kdJZ2XnY70KtJEeX963vLjti','Hồ','Minh Chính','customer20@gmail.com','2024-12-18 21:03:57',3,'0837934846','2072 Đường Quách Thị Trang, Xã Vĩnh Thanh, Huyện Nhơn Trạch, Đồng Nai','2024-12-18 21:03:57','active',NULL);
INSERT INTO users VALUES (25,'$2y$12$OV6yteheGeBY8d//d3qN9OxdDvW37fviErGXSTJjjg9K6cjIsdK2.','Nguyễn','Ánh Sao','customer21@gmail.com','2024-12-18 21:03:57',3,'0993538894','3569 Đường Phạm Văn Đồng, Phường Linh Đông, Thành phố Thủ Đức, Hồ Chí Minh','2024-12-18 21:03:57','active',NULL);
INSERT INTO users VALUES (26,'$2y$12$9Ub2ahxIZRQsKBZKHAH0aegIPyXnog7/P1EYvBJPuGHm2yUX67i3u','Trần','Đức Hùng','customer22@gmail.com','2024-12-18 21:03:57',3,'0563467908','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:57','active',NULL);
INSERT INTO users VALUES (27,'$2y$12$4HMdksY9OizeJhjDw1Sg7On439KFYoAwlKokTu8shmvjliZk/EIaW','Nguyễn','Ánh Sao','customer23@gmail.com','2024-12-18 21:03:58',3,'0335080325','70 Đường Nguyễn Trãi, Thị trấn Phước Bửu, Huyện Xuyên Mộc, Bà Rịa - Vũng Tàu','2024-12-18 21:03:58','active',NULL);
INSERT INTO users VALUES (28,'$2y$12$1nJWt9aEnMHF64OVuQGIJu0ENfV4GkcNy9k6lpJ52EWvBUUEkgly.','Trần','Phương Tuấn','customer24@gmail.com','2024-12-18 21:03:58',3,'0377159542','50 Đường Lý Thái Tổ, Thị trấn Đồng Văn, Huyện Duy Tiên, Hà Nam','2024-12-18 21:03:58','active',NULL);
INSERT INTO users VALUES (29,'$2y$12$waZokL9BmnHE2Szy8IgvcuJeQPdeH5J2GxssWLIFP9GAkhkI8DQBS','Đặng','Bình Minh','customer25@gmail.com','2024-12-18 21:03:58',3,'0996157492','120 Đường Trần Phú, Phường Máy Chai, Quận Ngô Quyền, Hải Phòng','2024-12-18 21:03:58','active',NULL);
INSERT INTO users VALUES (30,'$2y$12$.7Wu.wv2YCbAYk1H2HEiBuV0feb0mkim/cqEnVTN7xWh/U5bA9PPS','Đào','Ánh Sao','customer26@gmail.com','2024-12-18 21:03:58',3,'0777789007','40 Đường Phan Bội Châu, Thị trấn Gia Nghĩa, Thành phố Gia Nghĩa, Đắk Nông','2024-12-18 21:03:58','active',NULL);
INSERT INTO users VALUES (31,'$2y$12$2azs.9IUDc3k0QkO/v4nVuUVaAk8R2KlK7IPQIc/1g1TCsyYwJMT2','Hoàng','Quang Bảo','customer27@gmail.com','2024-12-18 21:03:59',3,'0941465378','20 Đường Trần Phú, Thị trấn Đức An, Huyện Đăk Song, Đắk Nông','2024-12-18 21:03:59','active',NULL);
INSERT INTO users VALUES (32,'$2y$12$jGjXrOhqG28wc3BtkmNGK.lOqbv8naQLXRY5Ak9VEVBglT.xBUfLa','Đào','Ánh Sao','customer28@gmail.com','2024-12-18 21:03:59',3,'0959492951','150 Đường Hùng Vương, Thị trấn Đông Hà, Huyện Cam Lộ, Quảng Trị','2024-12-18 21:03:59','active',NULL);
INSERT INTO users VALUES (33,'$2y$12$msU138TI55i1hf8jY66eIeK/prPZrOu6IX9FCFr5Q8o1/oMqoHtJu','Hoàng','Minh Chính','customer29@gmail.com','2024-12-18 21:03:59',3,'0750513558','30 Đường Quang Trung, Thị trấn Krông Klang, Huyện Đakrông, Quảng Trị','2024-12-18 21:03:59','active',NULL);
INSERT INTO users VALUES (34,'$2y$12$O.oCEVVDu/PT3L4tI7EcTOTLwv/tgs.XPI1NtkzuELqb6dkjEj5se','Đào','Tiến Đạt','customer30@gmail.com','2024-12-18 21:03:59',3,'0961306699','70 Đường Nguyễn Trãi, Thị trấn Phước Bửu, Huyện Xuyên Mộc, Bà Rịa - Vũng Tàu','2024-12-18 21:03:59','active',NULL);
INSERT INTO users VALUES (35,'$2y$12$Jt3MnCttRD2BwoyB0ucqBeV8h6M4nAMluCCkJ1Hr5AoKsukVfm3/m','Đào','Ánh Sao','customer31@gmail.com','2024-12-18 21:04:00',3,'0776393759','120 Đường Trần Phú, Phường Máy Chai, Quận Ngô Quyền, Hải Phòng','2024-12-18 21:04:00','active',NULL);

--
-- Dumping data for table `wishlist_details`
--

INSERT INTO wishlist_details VALUES (1,1,1);
INSERT INTO wishlist_details VALUES (2,1,2);
INSERT INTO wishlist_details VALUES (3,2,3);
INSERT INTO wishlist_details VALUES (4,2,4);
INSERT INTO wishlist_details VALUES (5,2,5);
INSERT INTO wishlist_details VALUES (6,3,6);
INSERT INTO wishlist_details VALUES (7,3,7);
INSERT INTO wishlist_details VALUES (8,4,8);
INSERT INTO wishlist_details VALUES (9,4,9);
INSERT INTO wishlist_details VALUES (10,4,10);

--
-- Dumping data for table `wishlists`
--

INSERT INTO wishlists VALUES (1,5);
INSERT INTO wishlists VALUES (2,6);
INSERT INTO wishlists VALUES (3,7);
INSERT INTO wishlists VALUES (4,8);
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2024-12-19 11:13:33
