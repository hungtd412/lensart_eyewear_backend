/*-- 1. INSERT INTO role
INSERT INTO role (id, name) VALUES (1, 'admin'), (2, 'manager'), (3, 'customer');
--seed role

-- 2. INSERT INTO users
--seed user
*/

-- 3. INSERT INTO branches
INSERT INTO branches VALUES
(1, 'Hồ Chí Minh', '123 Nguyễn Trãi, Quận 1, TP. Hồ Chí Minh', 2, 1.0, 'active'),
(2, 'Đà Nẵng', '456 Trần Phú, Quận Hải Châu, TP. Đà Nẵng', 3, 0.8, 'active'),
(3, 'Hà Nội', '789 Đường Láng, Quận Đống Đa, TP. Hà Nội', 4, 0.9, 'active');

-- 4. INSERT INTO brands
INSERT INTO brands (id, name, status)
VALUES
(1, 'Bevis', 'active'),
(2, 'Chemi', 'active'),
(3, 'Essilor', 'active'),
(4, 'Kodak', 'active'),
(5, 'Zeiss', 'active'),
(6, 'Hoya', 'active');


-- 5. INSERT INTO materials
INSERT INTO materials (id, name, status)
VALUES
(1, 'Tổng hợp', 'active'),
(2, 'Acetate', 'active'),
(3, 'Titanium', 'active'),
(4, 'Kim loại', 'active'),
(5, 'Nhựa', 'active'),
(6, 'TR90', 'active');


-- 6. INSERT INTO shapes
INSERT INTO shapes (id, name, status)
VALUES
(1, 'Đa giác', 'active'),
(2, 'Vuông', 'active'),
(3, 'Chữ nhật', 'active'),
(4, 'Browline', 'active'),
(5, 'Oval', 'active'),
(6, 'Phi công', 'active');

-- 7. INSERT INTO features
INSERT INTO features (id, name, status)
VALUES
(1, 'Lọc ánh sáng xanh', 'active'),
(2, 'Đổi màu', 'active'),
(3, 'Râm cận', 'active'),
(4, 'Siêu mỏng', 'active'),
(5, 'Chống UV', 'active'),
(6, 'Chống hơi nước', 'active');

-- 8. INSERT INTO category
INSERT INTO category (id, name, description, status)
VALUES
(1, 'Gọng kính', 'Dòng sản phẩm gọng kính cao cấp', 'active'),
(2, 'Kính mát', 'Kính mát thời trang', 'active'),
(3, 'Tròng kính', 'Tròng kính chính hãng', 'active');

-- 9. INSERT INTO products
-- Gọng kính khung giá 500-1tr
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(1, 'Gọng kính BOLON BT1529', 'Viền trên đậm - Nhựa và kim loại', 1, 1, 1, 1, 'Unisex', 500000, NULL, NOW(), 'active'),
(2, 'Gọng kính JILL STUART JL33072', 'Đa giác - Titanium', 2, 1, 2, 2, 'Unisex', 550000, 500000, NOW(), 'active'),
(3, 'Gọng kính MERCURY LV87137', 'Vuông - Kim loại Titan', 3, 1, 3, 3, 'Unisex', 600000, NULL, NOW(), 'active'),
(4, 'Gọng kính MERCURY LV87297', 'Viền trên đậm - Kim loại Titan', 4, 1, 1, 3, 'Unisex', 650000, NULL, NOW(), 'active'),
(5, 'Gọng kính MOLSON MJ6119', 'Đa giác - Nhựa và kim loại', 1, 1, 2, 1, 'Unisex', 700000, 650000, NOW(), 'active'),
(6, 'Gọng kính MOLSON MJ6156', 'Tròn - Titanium', 2, 1, 3, 2, 'Unisex', 750000, NULL, NOW(), 'active');

-- Gọng kính khung giá 1tr-2tr
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(7, 'Gọng kính MOLSON MJ7272', 'Đa giác - Kim loại Titan', 3, 1, 2, 3, 'Unisex', 1100000, NULL, NOW(), 'active'),
(8, 'Gọng kính NEWBALANCE NB09361X', 'Chữ nhật - Nhựa và kim loại', 4, 1, 1, 1, 'Unisex', 1200000, 1150000, NOW(), 'active'),
(9, 'Gọng kính POVINO PO6843', 'Nhựa và kim loại - Vuông', 1, 1, 3, 2, 'Unisex', 1300000, NULL, NOW(), 'active'),
(10, 'Gọng kính POVINO PO22641', 'Tròn - Nhựa và kim loại', 2, 1, 2, 1, 'Unisex', 1400000, NULL, NOW(), 'active'),
(11, 'Gọng kính POVINO ST9380', 'Chữ nhật - Kim loại Titan', 3, 1, 1, 3, 'Unisex', 1500000, NULL, NOW(), 'active'),
(12, 'Gọng kính RAYBAN RB6346-2904', 'Viền trên đậm - Titanium', 4, 1, 3, 2, 'Unisex', 1600000, 1550000, NOW(), 'active');

-- Gọng kính khung giá 2tr-2tr5
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(13, 'Gọng kính RAYBAN RX8763', 'Titanium - Chữ nhật', 1, 1, 1, 2, 'Unisex', 2100000, NULL, NOW(), 'active'),
(14, 'Gọng kính SEESON THE CLASSICS JIL', 'Vuông - Nhựa acetate', 2, 1, 3, 1, 'Unisex', 2200000, 2100000, NOW(), 'active'),
(15, 'Gọng kính MOLSON MJ7272', 'Đa giác - Kim loại Titan', 3, 1, 2, 3, 'Unisex', 2300000, NULL, NOW(), 'active'),
(16, 'Gọng kính POVINO ST9380', 'Chữ nhật - Kim loại Titan', 4, 1, 1, 3, 'Unisex', 2400000, NULL, NOW(), 'active'),
(17, 'Gọng kính RAYBAN RX8763', 'Titanium - Chữ nhật', 1, 1, 1, 2, 'Unisex', 2500000, 2400000, NOW(), 'active'),
(18, 'Gọng kính SEESON THE CLASSICS JIL', 'Vuông - Nhựa acetate', 2, 1, 3, 1, 'Unisex', 2500000, NULL, NOW(), 'active');

-- Kính mát khung giá 500,000 - 1,000,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(19, 'Kính mát BOLON BL3038', 'Nhựa Acetate', 1, 2, 1, 1, 'Unisex', 500000, NULL, NOW(), 'active'),
(20, 'Kính mát BOLON BL3100', 'Nhựa và Kim loại', 2, 2, 2, 2, 'Unisex', 600000, 550000, NOW(), 'active'),
(21, 'Kính mát BOLON BL5078', 'TR90', 3, 2, 1, 3, 'Unisex', 700000, NULL, NOW(), 'active'),
(22, 'Kính mát BOLON BL7191', 'Hợp kim', 4, 2, 2, 2, 'Unisex', 800000, NULL, NOW(), 'active');

-- Kính mát khung giá 1,000,000 - 2,000,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(23, 'Kính mát BOLON BV1025', 'Titanium', 1, 2, 3, 3, 'Unisex', 1100000, NULL, NOW(), 'active'),
(24, 'Kính mát POVINO NN107', 'Nhựa Acetate', 2, 2, 1, 1, 'Unisex', 1200000, 1150000, NOW(), 'active'),
(25, 'Kính mát POVINO SJMM68RX', 'Nhựa và Kim loại', 3, 2, 2, 2, 'Unisex', 1300000, NULL, NOW(), 'active'),
(26, 'Kính mát RAYBAN RB3625', 'Kim loại', 4, 2, 3, 3, 'Unisex', 1400000, NULL, NOW(), 'active');

-- Kính mát khung giá 2,000,000 - 2,500,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(27, 'Kính mát BOLON BL3038', 'Nhựa Acetate', 1, 2, 1, 1, 'Unisex', 2100000, NULL, NOW(), 'active'),
(28, 'Kính mát BOLON BL3100', 'Nhựa và Kim loại', 2, 2, 2, 2, 'Unisex', 2200000, 2100000, NOW(), 'active'),
(29, 'Kính mát POVINO SJMM68RX', 'Nhựa và Kim loại', 3, 2, 3, 2, 'Unisex', 2300000, NULL, NOW(), 'active'),
(30, 'Kính mát RAYBAN RB3625', 'Kim loại', 4, 2, 2, 3, 'Unisex', 2400000, NULL, NOW(), 'active');

-- Tròng kính khung giá 500,000 - 1,000,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(31, 'Tròng kính Chemi - Siêu mỏng', 'Siêu mỏng - Tròng kính cao cấp', 1, 3, 1, 1, 'Unisex', 500000, NULL, NOW(), 'active'),
(32, 'Tròng kính Chemi - Chống UV', 'Chống tia UV - Tròng kính chính hãng', 1, 3, 2, 2, 'Unisex', 600000, 550000, NOW(), 'active'),
(33, 'Tròng kính Element - Siêu mỏng', 'Siêu mỏng - Bảo vệ mắt tối đa', 2, 3, 3, 1, 'Unisex', 700000, NULL, NOW(), 'active'),
(34, 'Tròng kính Element - Chống UV', 'Chống UV - Đảm bảo an toàn mắt', 2, 3, 1, 3, 'Unisex', 800000, NULL, NOW(), 'active');

-- Tròng kính khung giá 1,000,000 - 2,000,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(35, 'Tròng kính ESSILOR - Đổi màu', 'Đổi màu - Chống ánh sáng xanh', 3, 3, 2, 3, 'Unisex', 1100000, NULL, NOW(), 'active'),
(36, 'Tròng kính ESSILOR - Siêu mỏng', 'Siêu mỏng - Tròng kính nhẹ và bền', 3, 3, 3, 2, 'Unisex', 1200000, 1150000, NOW(), 'active'),
(37, 'Tròng kính HOYA - Đổi màu', 'Đổi màu - Bảo vệ mắt khỏi ánh sáng xanh', 4, 3, 1, 3, 'Unisex', 1300000, NULL, NOW(), 'active'),
(38, 'Tròng kính HOYA - Chống UV', 'Chống tia UV - Đảm bảo an toàn', 4, 3, 2, 2, 'Unisex', 1400000, NULL, NOW(), 'active');

-- Tròng kính khung giá 2,000,000 - 2,500,000
INSERT INTO products (id, name, description, brand_id, category_id, shape_id, material_id, gender, price, offer_price, created_time, status)
VALUES
(39, 'Tròng kính Kodax - Chống UV', 'Chống UV - Độ bền cao', 2, 3, 3, 3, 'Unisex', 2100000, NULL, NOW(), 'active'),
(40, 'Tròng kính Kodax - Đổi màu', 'Đổi màu thông minh, bảo vệ mắt', 1, 3, 1, 1, 'Unisex', 2200000, 2100000, NOW(), 'active'),
(41, 'Tròng kính VisionX - Siêu mỏng', 'Siêu mỏng, nhẹ, tiện dụng', 3, 3, 2, 2, 'Unisex', 2300000, NULL, NOW(), 'active'),
(42, 'Tròng kính VisionX - Đổi màu', 'Đổi màu linh hoạt, chống chói', 2, 3, 3, 3, 'Unisex', 2400000, NULL, NOW(), 'active'),
(43, 'Tròng kính ZEISS - Chống UV', 'Công nghệ chống UV tiên tiến', 1, 3, 2, 1, 'Unisex', 2500000, 2400000, NOW(), 'active'),
(44, 'Tròng kính ZEISS - Siêu mỏng', 'Siêu mỏng, sang trọng và bền bỉ', 3, 3, 3, 3, 'Unisex', 2600000, NULL, NOW(), 'active');

-- 10. INSERT INTO products
INSERT INTO product_features (product_id, feature_id)
VALUES
-- Sản phẩm 1
(1, 1), (1, 2), (1, 3), (1, 4), (1, 5), (1, 6),
-- Sản phẩm 2
(2, 1), (2, 2), (2, 3), (2, 4), (2, 5), (2, 6),
-- Sản phẩm 3
(3, 1), (3, 2), (3, 3), (3, 4), (3, 5), (3, 6),
-- Sản phẩm 4
(4, 1), (4, 2), (4, 3), (4, 4), (4, 5), (4, 6),
-- Sản phẩm 5
(5, 1), (5, 2), (5, 3), (5, 4), (5, 5), (5, 6),
-- Sản phẩm 6
(6, 1), (6, 2), (6, 3), (6, 4), (6, 5), (6, 6),
-- Sản phẩm 7
(7, 1), (7, 2), (7, 3), (7, 4), (7, 5), (7, 6),
-- Sản phẩm 8
(8, 1), (8, 2), (8, 3), (8, 4), (8, 5), (8, 6),
-- Sản phẩm 9
(9, 1), (9, 2), (9, 3), (9, 4), (9, 5), (9, 6),
-- Sản phẩm 10
(10, 1), (10, 2), (10, 3), (10, 4), (10, 5), (10, 6),
-- Sản phẩm 11
(11, 1), (11, 2), (11, 3), (11, 4), (11, 5), (11, 6),
-- Sản phẩm 12
(12, 1), (12, 2), (12, 3), (12, 4), (12, 5), (12, 6),
-- Sản phẩm 13
(13, 1), (13, 2), (13, 3), (13, 4), (13, 5), (13, 6),
-- Sản phẩm 14
(14, 1), (14, 2), (14, 3), (14, 4), (14, 5), (14, 6),
-- Sản phẩm 15
(15, 1), (15, 2), (15, 3), (15, 4), (15, 5), (15, 6),
-- Sản phẩm 16
(16, 1), (16, 2), (16, 3), (16, 4), (16, 5), (16, 6),
-- Sản phẩm 17
(17, 1), (17, 2), (17, 3), (17, 4), (17, 5), (17, 6),
-- Sản phẩm 18
(18, 1), (18, 2), (18, 3), (18, 4), (18, 5), (18, 6),
-- Sản phẩm 19
(19, 1), (19, 2), (19, 3), (19, 4), (19, 5), (19, 6),
-- Sản phẩm 20
(20, 1), (20, 2), (20, 3), (20, 4), (20, 5), (20, 6),
-- Sản phẩm 21
(21, 1), (21, 2), (21, 3), (21, 4), (21, 5), (21, 6),
-- Sản phẩm 22
(22, 1), (22, 2), (22, 3), (22, 4), (22, 5), (22, 6),
-- Sản phẩm 23
(23, 1), (23, 2), (23, 3), (23, 4), (23, 5), (23, 6),
-- Sản phẩm 24
(24, 1), (24, 2), (24, 3), (24, 4), (24, 5), (24, 6),
-- Sản phẩm 25
(25, 1), (25, 2), (25, 3), (25, 4), (25, 5), (25, 6),
-- Sản phẩm 26
(26, 1), (26, 2), (26, 3), (26, 4), (26, 5), (26, 6),
-- Sản phẩm 27
(27, 1), (27, 2), (27, 3), (27, 4), (27, 5), (27, 6),
-- Sản phẩm 28
(28, 1), (28, 2), (28, 3), (28, 4), (28, 5), (28, 6),
-- Sản phẩm 29
(29, 1), (29, 2), (29, 3), (29, 4), (29, 5), (29, 6),
-- Sản phẩm 30
(30, 1), (30, 2), (30, 3), (30, 4), (30, 5), (30, 6),
-- Sản phẩm 31
(31, 1), (31, 2), (31, 3), (31, 4), (31, 5), (31, 6),
-- Sản phẩm 32
(32, 1), (32, 2), (32, 3), (32, 4), (32, 5), (32, 6),
-- Sản phẩm 33
(33, 1), (33, 2), (33, 3), (33, 4), (33, 5), (33, 6),
-- Sản phẩm 34
(34, 1), (34, 2), (34, 3), (34, 4), (34, 5), (34, 6),
-- Sản phẩm 35
(35, 1), (35, 2), (35, 3), (35, 4), (35, 5), (35, 6),
-- Sản phẩm 36
(36, 1), (36, 2), (36, 3), (36, 4), (36, 5), (36, 6),
-- Sản phẩm 37
(37, 1), (37, 2), (37, 3), (37, 4), (37, 5), (37, 6),
-- Sản phẩm 38
(38, 1), (38, 2), (38, 3), (38, 4), (38, 5), (38, 6),
-- Sản phẩm 39
(39, 1), (39, 2), (39, 3), (39, 4), (39, 5), (39, 6),
-- Sản phẩm 40
(40, 1), (40, 2), (40, 3), (40, 4), (40, 5), (40, 6),
-- Sản phẩm 41
(41, 1), (41, 2), (41, 3), (41, 4), (41, 5), (41, 6),
-- Sản phẩm 42
(42, 1), (42, 2), (42, 3), (42, 4), (42, 5), (42, 6),
-- Sản phẩm 43
(43, 1), (43, 2), (43, 3), (43, 4), (43, 5), (43, 6),
-- Sản phẩm 44
(44, 1), (44, 2), (44, 3), (44, 4), (44, 5), (44, 6);

-- 11. INSERT INTO product_images
INSERT INTO product_images (product_id, image_url, image_public_id)
VALUES
-- Sản phẩm 1
(1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273140/x%C3%A1m_b%E1%BA%A1c_spprbv.jpg', 'xám_bạc_rqd1sp'),
(1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_jk1pdd.jpg', 'đen_jk1pdd'),
(1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_x%C3%A1m_prcgd8.jpg', 'đen_xám_prcgd8'),
(1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_x%C3%A1m_2_mmnpzf.jpg', 'đen_xám_2_mmnpzf'),
(1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734273139/%C4%91en_v%C3%A0ng_zztpkw.jpg', 'đen_vàng_zztpkw'),
-- Sản phẩm 2
(2, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/tr%E1%BA%AFng_b%E1%BA%A1c_hek1xt.png', 'trắng_bạc_hek1xt'),
(2, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/h%E1%BB%93ng_n%C3%A2u_tr%E1%BA%AFng_qkb4nk.png', 'hồng_nâu_trắng_qkb4nk'),
(2, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/jill1_rzybsf.png', 'jill1_rzybsf'),
(2, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/jill2_g4t18f.png', 'jill2_g4t18f'),
(2, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277159/h%E1%BB%93ng_xanh_ps8ind.png', 'hồng_xanh_ps8ind'),
-- Sản phẩm 3
(3, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_4_nn66yb.jpg', 'đen_-_4_nn66yb'),
(3, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_wlauyb.jpg', 'đen_wlauyb'),
(3, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277269/x%C3%A1m_dzc9qr.jpg', 'xám_dzc9qr'),
(3, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_3_q0z2ml.jpg', 'đen_-_3_q0z2ml'),
(3, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277268/%C4%91en_-_2_x4rueabc', 'đen_-_2_x4rue5'),
-- Sản phẩm 4
(4, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277399/%C4%91en_v%C3%A0ng_hksppf.jpg', 'đen_vàng_hksppf'),
(4, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_-_3_fnr8pn.jpg', 'đen_nâu_-_3_fnr8pn'),
(4, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_j62wto.jpg', 'đen_nâu_j62wto'),
(4, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277397/%C4%91en_n%C3%A2u_-_2_cxwqso.jpg', 'đen_nâu_-_2_cxwqso'),
(4, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277396/%C4%91en_b%E1%BA%A1c_wsurvo.jpg', 'đen_bạc_wsurvo'),
-- Sản phẩm 5
(5, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277740/x%C3%A1m_v%C3%A0ng_jeccdx.png', 'xám_vàng_jeccdx'),
(5, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/tr%E1%BA%AFng_v8qajo.png', 'trắng_v8qajo'),
(5, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/%C4%91en_ibdsyj.png', 'đen_ibdsyj'),
(5, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/%C4%91en_v%C3%A0ng_rtkrow.png', 'đen_vàng_rtkrow'),
(5, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277739/tr%E1%BA%AFng_-_2_zt7xyn.png', 'trắng_-_2_zt7xyn'),
-- Sản phẩm 6
(6, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277946/v%C3%A0ng_x%C3%A1m_hmoagc.jpg', 'vàng_xám_hmoagc'),
(6, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277945/trong_su%E1%BB%91t_er5q4r.jpg', 'trong_suốt_er5q4r'),
(6, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277945/v%C3%A0ng_%C4%91en_gq0aht.jpg', 'vàng_đen_gq0aht'),
(6, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277944/%C4%91%E1%BB%93i_m%E1%BB%93i_oujdfg.jpg', 'đồi_mồi_oujdfg'),
(6, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734277944/1_bazxqm.jpg', '1_bazxqm'),
-- Sản phẩm 7
(7, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278386/xanh_tr%E1%BA%AFng_ywvggk.jpg', 'xanh_trắng_ywvggk'),
(7, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_v%C3%A0ng_f0wnjr.jpg', 'đen_vàng_f0wnjr'),
(7, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_b%E1%BA%A1c_nsgjwp.jpg', 'đen_bạc_nsgjwp'),
(7, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278385/%C4%91en_lbi3vg.jpg', 'đen_lbi3vg'),
(7, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278384/%C4%91en_-_2_h2sonp.jpg', 'đen_-_2_h2sonp'),
-- Sản phẩm 8
(8, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278573/xanh_d%C6%B0%C6%A1ng_xslj7g.jpg', 'xanh_dương_xslj7g'),
(8, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278571/xanh_d%C6%B0%C6%A1ng_-_2_ioeq3g.jpg', 'xanh_dương_-_2_ioeq3g'),
(8, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278570/tr%E1%BA%AFng_-_2_thzmeg.jpg', 'trắng_-_2_thzmeg'),
(8, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278570/tr%E1%BA%AFng_vz7igh.jpg', 'trắng_vz7igh'),
(8, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278569/%C4%91en_bfiicm.jpg', 'đen_bfiicm'),
-- Sản phẩm 9
(9, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278648/%C4%90en-1_atij1x.jpg', 'Đen-1_atij1x'),
(9, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278647/%C4%90en_bnspxj.jpg', 'Đen_bnspxj'),
(9, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278645/%C4%90en_b%E1%BA%A1c-1_jasabk.jpg', 'Đen_bạc-1_jasabk'),
(9, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278644/%C4%90en_b%E1%BA%A1c_ejodch.jpg', 'Đen_bạc_ejodch'),
(9, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278644/B%E1%BA%A1c_iful8l.jpg', 'Bạc_iful8l'),
-- Sản phẩm 10
(10, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/x%C3%A1m_ze1n4r.jpg', 'xám_ze1n4r'),
(10, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/%C4%91en_h%E1%BB%93ng_o8cwwl.jpg', 'đen_hồng_o8cwwl'),
(10, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278807/%C4%91en_b%E1%BA%A1c_owdjwd.jpg', 'đen_bạc_owdjwd'),
(10, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278806/2_asa680.jpg', '2_asa680'),
(10, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278805/1_c0kzfp.jpg', '1_c0kzfp'),
-- Sản phẩm 11
(11, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444808/v%C3%A0ng_2_lowuyi.jpg', 'vàng_2_lowuyi'),
(11, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444809/v%C3%A0ng_cqttxx.jpg', 'vàng_cqttxx'),
(11, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/x%C3%A1m_ze1n4r.jpg', 'xám_ze1n4r'),
(11, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278809/%C4%91en_h%E1%BB%93ng_o8cwwl.jpg', 'đen_hồng_o8cwwl'),
(11, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734278807/%C4%91en_b%E1%BA%A1c_owdjwd.jpg', 'đen_bạc_owdjwd'),
-- Sản phẩm 12
(12, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444872/%C4%91en_niirmv.jpg', 'đen_niirmv'),
(12, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/1_fybqms.jpg', '1_fybqms'),
(12, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/4_yjv7yj.jpg', '4_yjv7yj'),
(12, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/3_njkjlo.jpg', '3_njkjlo'),
(12, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444871/2_fmjc5a.jpg', '2_fmjc5a'),
-- Sản phẩm 13
(13, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/v%C3%A0ng_eaehas.jpg', 'vàng_eaehas'),
(13, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/b%E1%BA%A1c_hh3erh.jpg', 'bạc_hh3erh'),
(13, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/%C4%91en_b%E1%BA%A1c_nl7j1a.jpg', 'đen_bạc_nl7j1a'),
(13, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/%C4%91en_ojogjn.jpg', 'đen_ojogjn'),
(13, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734444954/1_ogamwx.jpg', '1_ogamwx'),
-- Sản phẩm 14
(14, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445054/x%C3%A1m_fourxb.jpg', 'xám_fourxb'),
(14, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445053/olive_chhv2m.jpg', 'olive_chhv2m'),
(14, '14_ahttps://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445052/havana_xlrzgp.jpgbc', 'havana_xlrzgp'),
(14, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445052/2_mftjlc.jpg', '2_mftjlc'),
(14, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445051/1_uwickw.jpg', '1_uwickw'),
-- Sản phẩm 15
(15, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445305/xanh_tr%E1%BA%AFng_vgijii.jpg', 'xanh_trắng_vgijii'),
(15, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445304/%C4%91en_uc61ji.jpg', 'đen_uc61ji'),
(15, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445303/%C4%91en_v%C3%A0ng_nkvxnh.jpg', 'đen_vàng_nkvxnh'),
(15, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445303/%C4%91en_b%E1%BA%A1c_cijkob.jpg', 'đen_bạc_cijkob'),
(15, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445302/%C4%91en_-_2_ult5bw.jpg', 'đen_-_2_ult5bw'),
-- Sản phẩm 16
(16, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445393/x%C3%A1m-2_kapwkc.jpg', 'xám-2_kapwkc'),
(16, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445392/x%C3%A1m_blnlkc.jpg', 'xám_blnlkc'),
(16, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445390/v%C3%A0ng_rjolv0.jpg', 'vàng_rjolv0'),
(16, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445390/v%C3%A0ng_2_cgw2ng.jpg', 'vàng_2_cgw2ng'),
(16, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445389/%C4%91en_nh%C3%A1m_ehw7n3.jpg', 'đen_nhám_ehw7n3'),
-- Sản phẩm 17
(17, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445487/v%C3%A0ng_yw3dmk.jpg', 'vàng_yw3dmk'),
(17, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445486/%C4%91en_byxzmx.jpg', 'đen_byxzmx'),
(17, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445485/%C4%91en_b%E1%BA%A1c_fprlwa.jpg', 'đen_bạc_fprlwa'),
(17, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445484/b%E1%BA%A1c_thor4x.jpg', 'bạc_thor4x'),
(17, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445483/1_wuborb.jpg', '1_wuborb'),
-- Sản phẩm 18
(18, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445541/x%C3%A1m_d4whlf.jpg', 'xám_d4whlf'),
(18, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445539/havana_q15vxo.jpg', 'havana_q15vxo'),
(18, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445540/olive_ty7qgw.jpg', 'olive_ty7qgw'),
(18, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445538/2_xxobdk.jpg', '2_xxobdk'),
(18, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445537/1_ezdyvo.jpg', '1_ezdyvo'),
-- Sản phẩm 19
(19, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445617/Xanh_r%C3%AAu_q90k7v.jpg', 'Xanh_rêu_q90k7v'),
(19, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445616/Xanh_d%C6%B0%C6%A1ng_m0pcro.jpg', 'Xanh_dương_m0pcro'),
(19, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445615/X%C3%A1m_h2xziv.jpg', 'Xám_h2xziv'),
(19, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445614/V%C3%A0ng_yuzwgd.jpg', 'Vàng_yuzwgd'),
(19, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445612/N%C3%A2u_jhvu4e.jpg', 'Nâu_jhvu4e'),
-- Sản phẩm 20
(20, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445695/%C4%90%E1%BB%93i_m%E1%BB%93i_bfraxr.jpg', 'Đồi_mồi_bfraxr'),
(20, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445693/%C4%90%E1%BB%93i_m%E1%BB%93i_v%C3%A0ng_yqcqpi.jpg', 'Đồi_mồi_vàng_yqcqpi'),
(20, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445693/%C4%90en_ysc0wo.jpg', 'Đen_ysc0wo'),
(20, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445691/%C4%90en_v%C3%A0ng_uh5yjb.jpg', 'Đen_vàng_uh5yjb'),
(20, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445690/%C4%90en_b%C3%B3ng_wxjzph.jpg', 'Đen_bóng_wxjzph'),
-- Sản phẩm 21
(21, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445757/Tr%E1%BA%AFng_x%C3%A1m_rjmays.jpg', 'Trắng_xám_rjmays'),
(21, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445756/H%E1%BB%93ng_t%C3%ADm_x%C3%A1m_xq0ham.jpg', 'Hồng_tím_xám_xq0ham'),
(21, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445754/%C4%90en_t%C3%ADm_kjxwaa.jpg', 'Đen_xám_o7h2jm'),
(21, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445753/%C4%90en_t%C3%ADm_h%E1%BB%93ng_fcq7zd.jpg', 'Đen_tím_kjxwaa'),
(21, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734445753/%C4%90en_t%C3%ADm_h%E1%BB%93ng_fcq7zd.jpg', 'Đen_tím_hồng_fcq7zd'),
-- Sản phẩm 22
(22, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448530/x%C3%A1m_qex702.jpg', 'xám_qex702'),
(22, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448529/gradient_ja7bdn.jpg', 'gradient_ja7bdn'),
(22, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448529/v%C3%A0ng_aqjm9f.jpg', 'vàng_aqjm9f'),
-- Sản phẩm 23
(23, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448592/xanh_x%C3%A1m_ctucvt.jpg', 'xanh_xám_ctucvt'),
(23, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448591/xanh_t%C3%ADm_fkgkea.jpg', 'xanh_tím_fkgkea'),
(23, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448589/%C4%91en_x%C3%A1m_ozytem.jpg', 'đen_xám_ozytem'),
(23, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448588/b%E1%BA%A1c_x%C3%A1m_vj5xvg.jpg', 'bạc_xám_vj5xvg'),
-- Sản phẩm 24
(24, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448675/Xanh_ombre_iykzqg.jpg', 'Xanh_ombre_iykzqg'),
(24, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448673/X%C3%A1m_br35cp.jpg', 'Xám_br35cp'),
(24, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448674/Xanh_l%C3%A1_w0jpym.jpg', 'Xanh_lá_w0jpym'),
(24, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448671/Tr%E1%BA%AFng_dpety6.jpg', 'Trắng_dpety6'),
(24, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448670/%C4%90en_guojjd.jpg', 'Đen_guojjd'),
-- Sản phẩm 25
(25, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448746/X%C3%A1m_ous1nv.jpg', 'Xám_ous1nv'),
(25, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448745/R%C3%AAu_jxkrho.jpg', 'Rêu_jxkrho'),
(25, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448743/%C4%90%E1%BB%93i_m%E1%BB%93i_naryll.jpg', 'Đồi_mồi_naryll'),
(25, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448741/%C4%90en_zforoz.jpg', 'Đen_zforoz'),
(25, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448740/%C4%90en_Tr%E1%BA%AFng_trong_vvm741.jpg', 'Đen_Trắng_trong_vvm741'),
-- Sản phẩm 26
(26, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448830/V%C3%A0ng_xanh_tr%E1%BB%9Di_qyvks3.jpg', 'Vàng_xanh_trời_qyvks3'),
(26, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448828/V%C3%A0ng_xanh_l%C3%A1_hcsiqq.jpg', 'Vàng_xanh_lá_hcsiqq'),
(26, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448826/V%C3%A0ng_xanh_b%E1%BA%A1c_kjflis.jpg', 'Vàng_xanh_bạc_kjflis'),
(26, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448825/v%C3%A0ng_b%E1%BA%A1c_n%C3%A2u_bttzu2.jpg', 'vàng_bạc_nâu_bttzu2'),
(26, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734448823/%C4%90en_x%C3%A1m_wxqpmz.jpg', 'Đen_xám_wxqpmz'),
-- Sản phẩm 27
(27, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449132/Xanh_d%C6%B0%C6%A1ng_xsgzmb.jpg', 'Xanh_dương_xsgzmb'),
(27, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449131/X%C3%A1m_fc4mnp.jpg', 'Xám_fc4mnp'),
(27, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449129/V%C3%A0ng_exqtln.jpg', 'Vàng_exqtln'),
(27, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449127/N%C3%A2u_dgxxp5.jpg', 'Nâu_dgxxp5'),
(27, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449126/%C4%90en_b%C3%B3ng_ze0saz.jpg', 'Đen_bóng_ze0saz'),
-- Sản phẩm 28
(28, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449267/%C4%90%E1%BB%93i_m%E1%BB%93i_zetqyi.jpg', 'Đồi_mồi_zetqyi'),
(28, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449265/%C4%90%E1%BB%93i_m%E1%BB%93i_v%C3%A0ng_b42cvk.jpg', 'Đồi_mồi_vàng_b42cvk'),
(28, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449263/%C4%90en_gb0yzi.jpg', 'Đen_gb0yzi'),
(28, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449261/%C4%90en_v%C3%A0ng_zycbwi.jpg', 'Đen_vàng_zycbwi'),
(28, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449260/%C4%90en_b%C3%B3ng_nyfw0u.jpg', 'Đen_bóng_nyfw0u'),
-- Sản phẩm 29
(29, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449346/X%C3%A1m_imxdik.jpg', 'Xám_imxdik'),
(29, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449344/R%C3%AAu_yr2qhb.jpg', 'Rêu_yr2qhb'),
(29, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449342/%C4%90%E1%BB%93i_m%E1%BB%93i_f435cj.jpg', 'Đồi_mồi_f435cj'),
(29, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449340/%C4%90en_kmj8en.jpg', 'Đen_kmj8en'),
(29, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734449338/%C4%90en_Tr%E1%BA%AFng_trong_weqkz2.jpg', 'Đen_Trắng_trong_weqkz2'),
-- Sản phẩm 30
(30, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450154/V%C3%A0ng_xanh_tr%E1%BB%9Di_kzukym.jpg', 'Vàng_xanh_trời_kzukym'),
(30, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450152/V%C3%A0ng_xanh_b%E1%BA%A1c_bzq4c6.jpg', 'Vàng_xanh_bạc_bzq4c6'),
(30, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450149/v%C3%A0ng_b%E1%BA%A1c_n%C3%A2u_r4clei.jpg', 'vàng_bạc_nâu_r4clei'),
(30, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450148/%C4%90en_x%C3%A1m_ewpbed.jpg', 'Đen_xám_ewpbed'),
(30, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450147/B%E1%BA%A1c_xanh_da_tr%E1%BB%9Di_vszyzr.jpg', 'Bạc_xanh_da_trời_vszyzr'),
-- Sản phẩm 31
(31, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450346/Tr%C3%B2ng_k%C3%ADnh_si%C3%AAu_m%E1%BB%8Fng_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_Chemi_Perfect_UV_U6_1.74_wbxp46.jpg', 'Tròng_kính_siêu_mỏng_kiểm_soát_ánh_sáng_xanh_Chemi_Perfect_UV_U6_1.74_wbxp46'),
(31, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450344/Tr%C3%B2ng_k%C3%ADnh_Phi_C%E1%BA%A7u_Hai_M%E1%BA%B7t_Chemi_Double_Aspheric_dsyzvl.jpg', 'Tròng_kính_Phi_Cầu_Hai_Mặt_Chemi_Double_Aspheric_dsyzvl'),
(31, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450342/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_%C4%91%C3%A1nh_Chemi_Single_Vision_Lens_klp9n0.jpg', 'Tròng_kính_Đơn_tròng_đánh_Chemi_Single_Vision_Lens_klp9n0'),
(31, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450340/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_%C4%91%C3%A1nh_Chemi_RX_1.74_ASP_U6_lvz94k.jpg', 'Tròng_kính_Đơn_tròng_đánh_Chemi_RX_1.74_ASP_U6_lvz94k'),
(31, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450338/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_olg6qk.jpg', 'Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Kiểm_soát_ánh_sáng_xanh_olg6qk'),
-- Sản phẩm 32
(32, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450449/Tr%C3%B2ng_k%C3%ADnh_Phi_C%E1%BA%A7u_Hai_M%E1%BA%B7t_Chemi_Double_Aspheric_jqszhf.jpg', 'Tròng_kính_Phi_Cầu_Hai_Mặt_Chemi_Double_Aspheric_jqszhf'),
(32, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450447/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_Chemi_1.67_ASP_PhotoBlue_isiak6.jpg', 'Tròng_kính_đổi_màu_kiểm_soát_ánh_sáng_xanh_Chemi_1.67_ASP_PhotoBlue_isiak6'),
(32, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450444/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_yinobx.jpg', 'Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.67_Kiểm_soát_ánh_sáng_xanh_yinobx'),
(32, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450442/Tr%C3%B2ng_k%C3%ADnh_Chemi_Perfect_UV_Crystal_U6_Coated_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_haaoyp.jpg', 'Tròng_kính_Chemi_Perfect_UV_Crystal_U6_Coated_1.60_Kiểm_soát_ánh_sáng_xanh_haaoyp'),
(32, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450440/Tr%C3%B2ng_K%C3%ADnh_CHEMI_1.56_ASP_BLUE_BLOCK_UV420_SPIN_PHOTOGREY_SHMC_vfbyyx.jpg', 'Tròng_Kính_CHEMI_1.56_ASP_BLUE_BLOCK_UV420_SPIN_PHOTOGREY_SHMC_vfbyyx'),
-- Sản phẩm 33
(33, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450546/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_5_rrepv4.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_5_rrepv4'),
(33, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450544/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_3_nemosn.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_3_nemosn'),
(33, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450542/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_2_s1xme0.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_2_s1xme0'),
(33, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450540/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_1_diazz9.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_1_diazz9'),
(33, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450538/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_sdwylv.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_sdwylv'),
-- Sản phẩm 34
(34, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450646/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_5_gvgdch.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_Night_AR_5_gvgdch'),
(34, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450644/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_3_khq6xe.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_Night_AR_3_khq6xe'),
(34, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450641/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_2_sqw0gw.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_Night_AR_2_sqw0gw'),
(34, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450639/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_Night_AR_1_vhjw1v.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_Night_AR_1_vhjw1v'),
(34, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450637/Tr%C3%B2ng_k%C3%ADnh_Elements_Blue_UV_Cut_6_pnn2n0.jpg', 'Tròng_kính_Elements_Blue_UV_Cut_6_pnn2n0'),
-- Sản phẩm 35
(35, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450755/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_qwxqi4.jpg', 'Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_4_qwxqi4'),
(35, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450753/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_bbamtd.jpg', 'Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_3_bbamtd'),
(35, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450751/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_zm80eq.jpg', 'Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_2_zm80eq'),
(35, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450749/Tr%C3%B2ng_k%C3%ADnh_Essilor_Crizal_Prevencia_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_latzat.jpg', 'Tròng_kính_Essilor_Crizal_Prevencia_kiểm_soát_ánh_sáng_xanh_1_latzat'),
-- Sản phẩm 36
(36, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450829/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_4_tf3b0d.jpg', 'Tròng_kính_Essilor_Eyezen_Max_Az_4_tf3b0d'),
(36, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450826/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_3_terjtd.jpg', 'Tròng_kính_Essilor_Eyezen_Max_Az_3_terjtd'),
(36, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450824/Tr%C3%B2ng_k%C3%ADnh_Essilor_Eyezen_Max_Az_2_q7nrjj.jpg', 'Tròng_kính_Essilor_Eyezen_Max_Az_2_q7nrjj'),
-- Sản phẩm 37
(37, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450913/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_ti9vjq.jpg', 'Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_3_ti9vjq'),
(37, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450910/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_zcoquh.jpg', 'Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_2_zcoquh'),
(37, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450908/Tr%C3%B2ng_k%C3%ADnh_HOYA_Stellify_Blue_Control_1.60_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_qemh23.jpg', 'Tròng_kính_HOYA_Stellify_Blue_Control_1.60_Kiểm_soát_ánh_sáng_xanh_1_qemh23'),
-- Sản phẩm 38
(38, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450971/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_vhb6az.jpg', 'Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_4_vhb6az'),
(38, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450968/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_rcuvc7.jpg', 'Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_3_rcuvc7'),
(38, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450966/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_m24xkf.jpg', 'Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_2_m24xkf'),
(38, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734450963/Tr%C3%B2ng_k%C3%ADnh_HOYA_Nulux_Full_Control_Ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_swsp4g.jpg', 'Tròng_kính_HOYA_Nulux_Full_Control_Kiểm_soát_ánh_sáng_xanh_1_swsp4g'),
-- Sản phẩm 39
(39, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451030/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_3_w7ofxh.jpg', 'Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_3_w7ofxh'),
(39, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451028/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_2_voor2g.jpg', 'Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_2_voor2g'),
(39, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451026/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_1_m6asyw.jpg', 'Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_1_m6asyw'),
-- Sản phẩm 40
(40, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451074/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_5_jnp7qv.jpg', 'Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_5_jnp7qv'),
(40, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451071/Tr%C3%B2ng_k%C3%ADnh_Kodak_UVBlue_Lens_ki%E1%BB%83m_so%C3%A1t_%C3%A1nh_s%C3%A1ng_xanh_4_ibvbks.jpg', 'Tròng_kính_Kodak_UVBlue_Lens_kiểm_soát_ánh_sáng_xanh_4_ibvbks'),
-- Sản phẩm 41
(41, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451126/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_5_jccgjw.jpg', 'Tròng_kính_đổi_màu_VisionX_Singapore_5_jccgjw'),
(41, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451124/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_4_o2pq90.jpg', 'Tròng_kính_đổi_màu_VisionX_Singapore_4_o2pq90'),
-- Sản phẩm 42
(42, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451121/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_3_jkral4.jpg', 'Tròng_kính_đổi_màu_VisionX_Singapore_3_jkral4'),
(42, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451118/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_2_xu4dla.jpg', 'Tròng_kính_đổi_màu_VisionX_Singapore_2_xu4dla'),
(42, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451116/Tr%C3%B2ng_k%C3%ADnh_%C4%91%E1%BB%95i_m%C3%A0u_VisionX_Singapore_1_kmkonu.jpg', 'Tròng_kính_đổi_màu_VisionX_Singapore_1_kmkonu'),
-- Sản phẩm 43
(43, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451211/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_5_zo8euz.jpg', 'Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_5_zo8euz'),
(43, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451208/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_4_erarqm.jpg', 'Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_4_erarqm'),
(43, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451206/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_3_zzc91z.jpg', 'Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_3_zzc91z'),
(43, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451203/Tr%C3%B2ng_k%C3%ADnh_%C4%90%C6%A1n_tr%C3%B2ng_ZEISS_SmartLife_Individual_1_fimd62.jpg', 'Tròng_kính_Đơn_tròng_ZEISS_SmartLife_Individual_1_fimd62'),
-- Sản phẩm 44
(44, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451200/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_4_rwhcys.jpg', 'Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_4_rwhcys'),
(44, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451198/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_3_n2rqsu.jpg', 'Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_3_n2rqsu'),
(44, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451195/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_2_m5nee6.jpg', 'Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_2_m5nee6'),
(44, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734451192/Tr%C3%B2ng_k%C3%ADnh_%C4%91a_tr%C3%B2ng_ZEISS_EnergizeMeTM_1_hvhx3m.jpg', 'Tròng_kính_đa_tròng_ZEISS_EnergizeMeTM_1_hvhx3m');

-- 12. INSERT INTO product_details
INSERT INTO product_details (product_id, branch_id, color, quantity, status)
VALUES
-- TP.HCM (Branch 1)
(1, 1, 'Đen', 10, 'active'),
(2, 1, 'Xanh Dương', 8, 'active'),
(3, 1, 'Đỏ', 12, 'active'),
(4, 1, 'Xanh Lá', 9, 'active'),
(5, 1, 'Nâu', 7, 'active'),
(6, 1, 'Xám', 11, 'active'),
(7, 1, 'Đen', 10, 'active'),
(8, 1, 'Xanh Dương', 10, 'active'),
(9, 1, 'Đỏ', 10, 'active'),
(10, 1, 'Xanh Lá', 10, 'active'),
(11, 1, 'Nâu', 10, 'active'),
(12, 1, 'Xám', 10, 'active'),
(13, 1, 'Đen', 10, 'active'),
(14, 1, 'Xanh Dương', 10, 'active'),
(15, 1, 'Đỏ', 10, 'active'),
(16, 1, 'Xanh Lá', 10, 'active'),
(17, 1, 'Nâu', 10, 'active'),
(18, 1, 'Xám', 10, 'active'),
(19, 1, 'Đen', 10, 'active'),
(20, 1, 'Xanh Dương', 10, 'active'),
(21, 1, 'Đỏ', 10, 'active'),
(22, 1, 'Xanh Lá', 10, 'active'),
(23, 1, 'Nâu', 10, 'active'),
(24, 1, 'Xám', 10, 'active'),
(25, 1, 'Đen', 10, 'active'),
(26, 1, 'Xanh Dương', 10, 'active'),
(27, 1, 'Đỏ', 10, 'active'),
(28, 1, 'Xanh Lá', 10, 'active'),
(29, 1, 'Nâu', 10, 'active'),
(30, 1, 'Xám', 10, 'active'),
(31, 1, 'Đen', 10, 'active'),
(32, 1, 'Xanh Dương', 10, 'active'),
(33, 1, 'Đỏ', 10, 'active'),
(34, 1, 'Xanh Lá', 10, 'active'),
(35, 1, 'Nâu', 10, 'active'),
(36, 1, 'Xám', 10, 'active'),
(37, 1, 'Đen', 10, 'active'),
(38, 1, 'Xanh Dương', 10, 'active'),
(39, 1, 'Đỏ', 10, 'active'),
(40, 1, 'Xanh Lá', 10, 'active'),
(41, 1, 'Nâu', 10, 'active'),
(42, 1, 'Xám', 10, 'active'),
(43, 1, 'Đen', 10, 'active'),
(44, 1, 'Xanh Dương', 10, 'active'),

-- Đà Nẵng (Branch 2)
(1, 2, 'Đen', 5, 'active'),
(2, 2, 'Xanh Dương', 5, 'active'),
(3, 2, 'Đỏ', 5, 'active'),
(4, 2, 'Xanh Lá', 5, 'active'),
(5, 2, 'Nâu', 5, 'active'),
(6, 2, 'Xám', 5, 'active'),
(7, 2, 'Đen', 5, 'active'),
(8, 2, 'Xanh Dương', 5, 'active'),
(9, 2, 'Đỏ', 5, 'active'),
(10, 2, 'Xanh Lá', 5, 'active'),
(11, 2, 'Nâu', 5, 'active'),
(12, 2, 'Xám', 5, 'active'),
(13, 2, 'Đen', 5, 'active'),
(14, 2, 'Xanh Dương', 5, 'active'),
(15, 2, 'Đỏ', 5, 'active'),
(16, 2, 'Xanh Lá', 5, 'active'),
(17, 2, 'Nâu', 5, 'active'),
(18, 2, 'Xám', 5, 'active'),
(19, 2, 'Đen', 5, 'active'),
(20, 2, 'Xanh Dương', 5, 'active'),
(21, 2, 'Đỏ', 5, 'active'),
(22, 2, 'Xanh Lá', 5, 'active'),
(23, 2, 'Nâu', 5, 'active'),
(24, 2, 'Xám', 5, 'active'),
(25, 2, 'Đen', 5, 'active'),
(26, 2, 'Xanh Dương', 5, 'active'),
(27, 2, 'Đỏ', 5, 'active'),
(28, 2, 'Xanh Lá', 5, 'active'),
(29, 2, 'Nâu', 5, 'active'),
(30, 2, 'Xám', 5, 'active'),
(31, 2, 'Đen', 5, 'active'),
(32, 2, 'Xanh Dương', 5, 'active'),
(33, 2, 'Đỏ', 5, 'active'),
(34, 2, 'Xanh Lá', 5, 'active'),
(35, 2, 'Nâu', 5, 'active'),
(36, 2, 'Xám', 5, 'active'),
(37, 2, 'Đen', 5, 'active'),
(38, 2, 'Xanh Dương', 5, 'active'),
(39, 2, 'Đỏ', 5, 'active'),
(40, 2, 'Xanh Lá', 5, 'active'),
(41, 2, 'Nâu', 5, 'active'),
(42, 2, 'Xám', 5, 'active'),
(43, 2, 'Đen', 5, 'active'),
(44, 2, 'Xanh Dương', 5, 'active'),

-- Hà Nội (Branch 3)
(1, 3, 'Đen', 5, 'active'),
(2, 3, 'Xanh Dương', 5, 'active'),
(3, 3, 'Đỏ', 5, 'active'),
(4, 3, 'Xanh Lá', 5, 'active'),
(5, 3, 'Nâu', 5, 'active'),
(6, 3, 'Xám', 5, 'active'),
(7, 3, 'Đen', 5, 'active'),
(8, 3, 'Xanh Dương', 5, 'active'),
(9, 3, 'Đỏ', 5, 'active'),
(10, 3, 'Xanh Lá', 5, 'active'),
(11, 3, 'Nâu', 5, 'active'),
(12, 3, 'Xám', 5, 'active'),
(13, 3, 'Đen', 5, 'active'),
(14, 3, 'Xanh Dương', 5, 'active'),
(15, 3, 'Đỏ', 5, 'active'),
(16, 3, 'Xanh Lá', 5, 'active'),
(17, 3, 'Nâu', 5, 'active'),
(18, 3, 'Xám', 5, 'active'),
(19, 3, 'Đen', 5, 'active'),
(20, 3, 'Xanh Dương', 5, 'active'),
(21, 3, 'Đỏ', 5, 'active'),
(22, 3, 'Xanh Lá', 5, 'active'),
(23, 3, 'Nâu', 5, 'active'),
(24, 3, 'Xám', 5, 'active'),
(25, 3, 'Đen', 5, 'active'),
(26, 3, 'Xanh Dương', 5, 'active'),
(27, 3, 'Đỏ', 5, 'active'),
(28, 3, 'Xanh Lá', 5, 'active'),
(29, 3, 'Nâu', 5, 'active'),
(30, 3, 'Xám', 5, 'active'),
(31, 3, 'Đen', 5, 'active'),
(32, 3, 'Xanh Dương', 5, 'active'),
(33, 3, 'Đỏ', 5, 'active'),
(34, 3, 'Xanh Lá', 5, 'active'),
(35, 3, 'Nâu', 5, 'active'),
(36, 3, 'Xám', 5, 'active'),
(37, 3, 'Đen', 5, 'active'),
(38, 3, 'Xanh Dương', 5, 'active'),
(39, 3, 'Đỏ', 5, 'active'),
(40, 3, 'Xanh Lá', 5, 'active'),
(41, 3, 'Nâu', 5, 'active'),
(42, 3, 'Xám', 5, 'active'),
(43, 3, 'Đen', 5, 'active'),
(44, 3, 'Xanh Dương', 5, 'active');

-- 13. INSERT INTO carts
INSERT INTO carts (id, user_id)
VALUES
(1, 5),   -- Cart cho khách hàng 1
(2, 6),   -- Cart cho khách hàng 2
(3, 7),   -- Cart cho khách hàng 3
(4, 8),   -- Cart cho khách hàng 4
(5, 9),   -- Cart cho khách hàng 5
(6, 10),  -- Cart cho khách hàng 6
(7, 11),  -- Cart cho khách hàng 7
(8, 12),  -- Cart cho khách hàng 8
(9, 13),  -- Cart cho khách hàng 9
(10, 14), -- Cart cho khách hàng 10
(11, 15), -- Cart cho khách hàng 11
(12, 16), -- Cart cho khách hàng 12
(13, 17), -- Cart cho khách hàng 13
(14, 18), -- Cart cho khách hàng 14
(15, 19), -- Cart cho khách hàng 15
(16, 20), -- Cart cho khách hàng 16
(17, 21), -- Cart cho khách hàng 17
(18, 22), -- Cart cho khách hàng 18
(19, 23), -- Cart cho khách hàng 19
(20, 24), -- Cart cho khách hàng 20
(21, 25), -- Cart cho khách hàng 21
(22, 26), -- Cart cho khách hàng 22
(23, 27), -- Cart cho khách hàng 23
(24, 28), -- Cart cho khách hàng 24
(25, 29), -- Cart cho khách hàng 25
(26, 30), -- Cart cho khách hàng 26
(27, 31), -- Cart cho khách hàng 27
(28, 32), -- Cart cho khách hàng 28
(29, 33), -- Cart cho khách hàng 29
(30, 34); -- Cart cho khách hàng 30

-- 14. INSERT INTO cart_details
INSERT INTO cart_details (id, cart_id, product_id, branch_id, color, quantity, total_price)
VALUES
-- Khách hàng 1 (cart_id = 1, 1 sản phẩm)
(1, 1, 1, 1, 'Đen', 1, 450000),

-- Khách hàng 2 (cart_id = 2, 2 sản phẩm)
(2, 2, 2, 1, 'Xanh Dương', 1, 500000),
(3, 2, 3, 1, 'Đỏ', 2, 1200000),

-- Khách hàng 3 (cart_id = 3, 3 sản phẩm)
(4, 3, 4, 1, 'Xanh Lá', 1, 650000),
(5, 3, 5, 1, 'Nâu', 1, 700000),
(6, 3, 6, 1, 'Xám', 1, 750000),

-- Khách hàng 4 (cart_id = 4, 1 sản phẩm)
(7, 4, 7, 2, 'Đen', 1, 1050000),

-- Khách hàng 5 (cart_id = 5, 2 sản phẩm)
(8, 5, 8, 2, 'Xanh Dương', 1, 1150000),
(9, 5, 9, 2, 'Đỏ', 1, 1250000),

-- Khách hàng 6 (cart_id = 6, 3 sản phẩm)
(10, 6, 10, 2, 'Xanh Lá', 1, 1350000),
(11, 6, 11, 2, 'Nâu', 1, 1450000),
(12, 6, 12, 2, 'Xám', 1, 1550000),

-- Khách hàng 7 (cart_id = 7, 1 sản phẩm)
(13, 7, 13, 3, 'Đen', 1, 2000000),

-- Khách hàng 8 (cart_id = 8, 2 sản phẩm)
(14, 8, 14, 3, 'Xanh Dương', 1, 2100000),
(15, 8, 15, 3, 'Đỏ', 1, 2200000),

-- Khách hàng 9 (cart_id = 9, 3 sản phẩm)
(16, 9, 16, 3, 'Xanh Lá', 1, 2300000),
(17, 9, 17, 3, 'Nâu', 1, 2400000),
(18, 9, 18, 3, 'Xám', 1, 2500000),

-- Khách hàng 10 (cart_id = 10, 1 sản phẩm)
(19, 10, 19, 1, 'Đen', 1, 450000);

-- 15. INSERT INTO wishlists
-- Tạo wishlist cho 4 khách hàng
INSERT INTO wishlists (id, user_id)
VALUES
(1, 5),   -- Wishlist cho khách hàng 1
(2, 6),   -- Wishlist cho khách hàng 2
(3, 7),   -- Wishlist cho khách hàng 3
(4, 8);   -- Wishlist cho khách hàng 4

-- 16. INSERT INTO wishlist_details
-- Thêm sản phẩm vào wishlist
INSERT INTO wishlist_details (id, wishlist_id, product_id)
VALUES
-- Khách hàng 1 (wishlist_id = 1, 2 sản phẩm)
(1, 1, 1),  -- Sản phẩm 1
(2, 1, 2),  -- Sản phẩm 2

-- Khách hàng 2 (wishlist_id = 2, 3 sản phẩm)
(3, 2, 3),  -- Sản phẩm 3
(4, 2, 4),  -- Sản phẩm 4
(5, 2, 5),  -- Sản phẩm 5

-- Khách hàng 3 (wishlist_id = 3, 2 sản phẩm)
(6, 3, 6),  -- Sản phẩm 6
(7, 3, 7),  -- Sản phẩm 7

-- Khách hàng 4 (wishlist_id = 4, 3 sản phẩm)
(8, 4, 8),  -- Sản phẩm 8
(9, 4, 9),  -- Sản phẩm 9
(10, 4, 10); -- Sản phẩm 10

-- 20. INSERT INTO coupons
INSERT INTO coupons (id, code, name, quantity, discount_price, status)
VALUES
(1, 'COUPON10', '10% Discount', 10, 10000, 'active'),
(2, 'COUPON15', '15% Discount', 8, 15000, 'active'),
(3, 'COUPON20', '20% Discount', 6, 20000, 'active'),
(4, 'COUPON25', '25% Discount', 5, 25000, 'active'),
(5, 'COUPON30', '30% Discount', 4, 30000, 'active'),
(6, 'COUPON35', '35% Discount', 3, 35000, 'active'),
(7, 'COUPON40', '40% Discount', 3, 40000, 'active'),
(8, 'COUPON50', '50% Discount', 2, 50000, 'active'),
(9, 'COUPON75', '75% Discount', 1, 75000, 'active'),
(10, 'COUPON100', '100% Discount', 1, 100000, 'active');

-- 17. INSERT INTO orders
INSERT INTO orders (id, user_id, date, branch_id, address, note, coupon_id, total_price, payment_status, order_status, payment_method, status)
VALUES
-- HCM (34 đơn hàng)
(1, 5, NOW(), 1, '123 Nguyen Trai, HCM', 'Deliver fast', 1, 490000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(2, 5, NOW(), 1, '123 Nguyen Trai, HCM', 'Handle with care', NULL, 1500000, 'Đã thanh toán', 'Đã giao', 'Momo QR', 'active'),
(3, 6, NOW(), 1, '456 Le Loi, HCM', 'Fast delivery', 2, 1985000, 'Đã thanh toán', 'Đang xử lý', 'Tiền mặt', 'active'),
(4, 6, NOW(), 1, '456 Le Loi, HCM', 'Important', NULL, 1200000, 'Chưa thanh toán', 'Đã hủy', 'Napas 247', 'active'),
(5, 7, NOW(), 1, '789 Tran Hung Dao, HCM', 'Fragile', NULL, 1400000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(6, 8, NOW(), 1, '56 Vo Van Kiet, HCM', 'Gift wrap', NULL, 900000, 'Chưa thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(7, 9, NOW(), 1, '35 Nguyen Hue, HCM', 'Careful packaging', NULL, 800000, 'Đã thanh toán', 'Đã giao', 'Tiền mặt', 'active'),
(8, 10, NOW(), 1, '89 Dien Bien Phu, HCM', 'Priority delivery', NULL, 1100000, 'Đã thanh toán', 'Đang giao hàng', 'Napas 247', 'active'),
(9, 11, NOW(), 1, '12 Ly Chinh Thang, HCM', 'Express delivery', NULL, 1300000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(10, 12, NOW(), 1, '23 Vo Thi Sau, HCM', 'Special request', NULL, 950000, 'Đã thanh toán', 'Đã giao', 'Napas 247', 'active'),
(11, 13, NOW(), 1, '45 Dinh Tien Hoang, HCM', 'Fragile items', NULL, 1200000, 'Chưa thanh toán', 'Đã hủy', 'Momo QR', 'active'),
(12, 14, NOW(), 1, '67 Le Duan, HCM', 'Deliver fast', NULL, 1000000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(13, 15, NOW(), 1, '89 Hai Ba Trung, HCM', 'Gift for someone', NULL, 1400000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(14, 16, NOW(), 1, '101 Pasteur, HCM', 'Handle carefully', NULL, 1550000, 'Đã thanh toán', 'Đang xử lý', 'Napas 247', 'active'),
(15, 17, NOW(), 1, '34 Nguyen Oanh, HCM', 'Quick delivery', NULL, 1450000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(16, 18, NOW(), 1, '56 Tran Quang Khai, HCM', 'Special care', NULL, 1250000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(17, 19, NOW(), 1, '78 Ton Duc Thang, HCM', 'Urgent', NULL, 950000, 'Đã thanh toán', 'Đang xử lý', 'Tiền mặt', 'active'),
(18, 20, NOW(), 1, '101 Nguyen Dinh Chieu, HCM', 'Priority', NULL, 1200000, 'Chưa thanh toán', 'Đã giao', 'Napas 247', 'active'),
(19, 21, NOW(), 1, '67 Cach Mang Thang Tam, HCM', 'Deliver now', NULL, 1100000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(20, 22, NOW(), 1, '34 Truong Dinh, HCM', 'Handle carefully', NULL, 1350000, 'Đã thanh toán', 'Đã giao', 'Momo QR', 'active'),
(21, 23, NOW(), 1, '56 Le Van Sy, HCM', 'Careful packaging', NULL, 1250000, 'Đã thanh toán', 'Đang giao hàng', 'Napas 247', 'active'),
(22, 24, NOW(), 1, '78 Pham Van Dong, HCM', 'Quick service', NULL, 950000, 'Chưa thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(23, 25, NOW(), 1, '101 Dien Bien Phu, HCM', 'Urgent request', NULL, 1450000, 'Đã thanh toán', 'Đang xử lý', 'Tiền mặt', 'active'),
(24, 26, NOW(), 1, '67 Nguyen Van Cu, HCM', 'Deliver with care', NULL, 1200000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(25, 27, NOW(), 1, '34 Vo Van Tan, HCM', 'Handle with caution', NULL, 1300000, 'Đã thanh toán', 'Đã giao', 'Napas 247', 'active'),
(26, 28, NOW(), 1, '56 Ly Thuong Kiet, HCM', 'Deliver fast', NULL, 1550000, 'Đã thanh toán', 'Đang xử lý', 'Momo ATM', 'active'),
(27, 29, NOW(), 1, '78 Bach Dang, HCM', 'Gift item', NULL, 1400000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(28, 30, NOW(), 1, '101 Hoang Sa, HCM', 'Quick handling', NULL, 1250000, 'Chưa thanh toán', 'Đã giao', 'Napas 247', 'active'),
(29, 31, NOW(), 1, '34 Truong Chinh, HCM', 'Careful packaging', NULL, 1450000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(30, 32, NOW(), 1, '56 Nguyen Trai, HCM', 'Fragile item', NULL, 1100000, 'Đã thanh toán', 'Đang xử lý', 'Momo ATM', 'active'),
(31, 33, NOW(), 1, '78 Dong Khoi, HCM', 'Handle urgently', NULL, 1250000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(32, 34, NOW(), 1, '101 Pasteur, HCM', 'Deliver fast', NULL, 1300000, 'Đã thanh toán', 'Đã giao', 'Napas 247', 'active');

-- HN (19 đơn hàng)
INSERT INTO orders (id, user_id, date, branch_id, address, note, coupon_id, total_price, payment_status, order_status, payment_method, status)
VALUES
(40, 5, NOW(), 3, '123 Nguyen Trai, Ha Noi', 'Deliver fast', NULL, 1000000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(41, 6, NOW(), 3, '456 Le Loi, Ha Noi', 'Handle with care', NULL, 1500000, 'Đã thanh toán', 'Đã giao', 'Momo QR', 'active'),
(42, 7, NOW(), 3, '789 Tran Hung Dao, Ha Noi', 'Urgent delivery', NULL, 2000000, 'Đã thanh toán', 'Đang xử lý', 'Tiền mặt', 'active'),
(43, 8, NOW(), 3, '12 Ly Chinh Thang, Ha Noi', 'Gift wrap', NULL, 1200000, 'Chưa thanh toán', 'Đã hủy', 'Napas 247', 'active'),
(44, 9, NOW(), 3, '35 Nguyen Hue, Ha Noi', 'Fragile', NULL, 1400000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(45, 10, NOW(), 3, '89 Dien Bien Phu, Ha Noi', 'Priority delivery', NULL, 900000, 'Chưa thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(46, 11, NOW(), 3, '23 Vo Thi Sau, Ha Noi', 'Special request', NULL, 800000, 'Đã thanh toán', 'Đã giao', 'Tiền mặt', 'active'),
(47, 12, NOW(), 3, '101 Pasteur, Ha Noi', 'Quick service', NULL, 1100000, 'Đã thanh toán', 'Đang giao hàng', 'Napas 247', 'active'),
(48, 13, NOW(), 3, '45 Dinh Tien Hoang, Ha Noi', 'Careful packaging', NULL, 1300000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(49, 14, NOW(), 3, '67 Le Duan, Ha Noi', 'Express delivery', NULL, 950000, 'Đã thanh toán', 'Đã giao', 'Napas 247', 'active'),
(50, 15, NOW(), 3, '89 Hai Ba Trung, Ha Noi', 'Urgent', NULL, 1200000, 'Chưa thanh toán', 'Đã giao', 'Momo QR', 'active'),
(51, 16, NOW(), 3, '101 Nguyen Dinh Chieu, Ha Noi', 'Gift for someone', NULL, 1400000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(52, 17, NOW(), 3, '34 Nguyen Oanh, Ha Noi', 'Handle carefully', NULL, 1500000, 'Đã thanh toán', 'Đang xử lý', 'Napas 247', 'active'),
(53, 18, NOW(), 3, '56 Tran Quang Khai, Ha Noi', 'Deliver fast', NULL, 1250000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(54, 19, NOW(), 3, '78 Ton Duc Thang, Ha Noi', 'Special care', NULL, 950000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(55, 20, NOW(), 3, '67 Cach Mang Thang Tam, Ha Noi', 'Deliver with care', NULL, 1100000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(56, 21, NOW(), 3, '34 Truong Dinh, Ha Noi', 'Handle urgently', NULL, 1450000, 'Đã thanh toán', 'Đang giao hàng', 'Napas 247', 'active'),
(57, 22, NOW(), 3, '56 Le Van Sy, Ha Noi', 'Priority delivery', NULL, 1350000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(58, 23, NOW(), 3, '78 Bach Dang, Ha Noi', 'Urgent request', NULL, 1200000, 'Đã thanh toán', 'Đã giao', 'Napas 247', 'active'),
(59, 24, NOW(), 3, '101 Nguyen Van Cu, Ha Noi', 'Quick delivery', NULL, 1400000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active');

-- Đà Nẵng (19 đơn hàng)
INSERT INTO orders (id, user_id, date, branch_id, address, note, coupon_id, total_price, payment_status, order_status, payment_method, status)
VALUES
(60, 5, NOW(), 2, '123 Nguyen Trai, Da Nang', 'Deliver fast', NULL, 800000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(61, 6, NOW(), 2, '456 Le Loi, Da Nang', 'Handle with care', NULL, 1200000, 'Đã thanh toán', 'Đã giao', 'Momo QR', 'active'),
(62, 7, NOW(), 2, '789 Tran Hung Dao, Da Nang', 'Urgent delivery', NULL, 1600000, 'Đã thanh toán', 'Đang xử lý', 'Tiền mặt', 'active'),
(63, 8, NOW(), 2, '12 Ly Chinh Thang, Da Nang', 'Gift wrap', NULL, 960000, 'Chưa thanh toán', 'Đã hủy', 'Napas 247', 'active'),
(64, 9, NOW(), 2, '35 Nguyen Hue, Da Nang', 'Fragile', NULL, 1120000, 'Đã thanh toán', 'Đã giao', 'Momo ATM', 'active'),
(65, 10, NOW(), 2, '89 Dien Bien Phu, Da Nang', 'Priority delivery', NULL, 720000, 'Chưa thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(66, 11, NOW(), 2, '23 Vo Thi Sau, Da Nang', 'Special request', NULL, 640000, 'Đã thanh toán', 'Đã giao', 'Tiền mặt', 'active'),
(67, 12, NOW(), 2, '101 Pasteur, Da Nang', 'Quick service', NULL, 880000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(68, 13, NOW(), 2, '45 Dinh Tien Hoang, Da Nang', 'Careful packaging', NULL, 1040000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(69, 14, NOW(), 2, '67 Le Duan, Da Nang', 'Express delivery', NULL, 760000, 'Đã thanh toán', 'Đã giao', 'Tiền mặt', 'active'),
(70, 15, NOW(), 2, '89 Hai Ba Trung, Da Nang', 'Urgent', NULL, 960000, 'Chưa thanh toán', 'Đã giao', 'Napas 247', 'active'),
(71, 16, NOW(), 2, '101 Nguyen Dinh Chieu, Da Nang', 'Gift for someone', NULL, 1120000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(72, 17, NOW(), 2, '34 Nguyen Oanh, Da Nang', 'Handle carefully', NULL, 1200000, 'Đã thanh toán', 'Đang xử lý', 'Momo ATM', 'active'),
(73, 18, NOW(), 2, '56 Tran Quang Khai, Da Nang', 'Deliver fast', NULL, 1000000, 'Đã thanh toán', 'Đã giao', 'Momo QR', 'active'),
(74, 19, NOW(), 2, '78 Ton Duc Thang, Da Nang', 'Special care', NULL, 760000, 'Đã thanh toán', 'Đang giao hàng', 'Tiền mặt', 'active'),
(75, 20, NOW(), 2, '67 Cach Mang Thang Tam, Da Nang', 'Deliver with care', NULL, 880000, 'Đã thanh toán', 'Đang giao hàng', 'Napas 247', 'active'),
(76, 21, NOW(), 2, '34 Truong Dinh, Da Nang', 'Handle urgently', NULL, 1160000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active'),
(77, 22, NOW(), 2, '56 Le Van Sy, Da Nang', 'Priority delivery', NULL, 1080000, 'Đã thanh toán', 'Đang giao hàng', 'Momo QR', 'active'),
(78, 23, NOW(), 2, '78 Bach Dang, Da Nang', 'Urgent request', NULL, 960000, 'Đã thanh toán', 'Đã giao', 'Tiền mặt', 'active'),
(79, 24, NOW(), 2, '101 Nguyen Van Cu, Da Nang', 'Quick delivery', NULL, 1120000, 'Đã thanh toán', 'Đang giao hàng', 'Momo ATM', 'active');

-- 18. INSERT INTO order_details
-- HCM
INSERT INTO order_details (id, order_id, product_id, color, quantity, total_price) VALUES
(1, 1, 1, 'Đen', 1, 500000),
(2, 1, 2, 'Xanh', 1, 500000),
(3, 2, 3, 'Đỏ', 2, 1500000),
(4, 3, 4, 'Xanh lá', 1, 1000000),
(5, 3, 5, 'Đen', 1, 1000000),
(6, 4, 6, 'Trắng', 1, 1200000),
(7, 5, 7, 'Xanh', 2, 1400000),
(8, 6, 8, 'Vàng', 1, 900000),
(9, 7, 9, 'Đen', 1, 800000),
(10, 8, 10, 'Xanh lá', 2, 1100000),
(11, 9, 11, 'Xanh', 1, 1300000),
(12, 10, 12, 'Đỏ', 1, 950000),
(13, 11, 13, 'Vàng', 1, 1200000),
(14, 12, 14, 'Đen', 2, 1000000),
(15, 13, 15, 'Xanh', 1, 1400000),
(16, 14, 16, 'Xanh lá', 1, 1550000),
(17, 15, 17, 'Đỏ', 2, 1450000),
(18, 16, 18, 'Trắng', 1, 1250000),
(19, 17, 19, 'Đen', 1, 950000),
(20, 18, 20, 'Xanh', 1, 1200000),
(21, 19, 21, 'Vàng', 2, 1100000),
(22, 20, 22, 'Xanh lá', 1, 1350000),
(23, 21, 23, 'Đen', 2, 1250000),
(24, 22, 24, 'Xanh', 1, 950000),
(25, 23, 25, 'Đỏ', 1, 1450000),
(26, 24, 26, 'Xanh lá', 1, 1200000),
(27, 25, 27, 'Trắng', 1, 1300000),
(28, 26, 28, 'Vàng', 2, 1550000),
(29, 27, 29, 'Xanh', 1, 1400000),
(30, 28, 30, 'Đen', 1, 1250000),
(31, 29, 31, 'Xanh lá', 1, 1450000),
(32, 30, 32, 'Đỏ', 2, 1100000),
(33, 31, 33, 'Vàng', 1, 1250000),
(34, 32, 34, 'Đen', 1, 1300000);

-- HN
INSERT INTO order_details (id, order_id, product_id, color, quantity, total_price) VALUES
(35, 40, 1, 'Đen', 1, 500000),
(36, 40, 2, 'Xanh', 1, 500000),
(37, 41, 3, 'Đỏ', 2, 1500000),
(38, 42, 4, 'Xanh lá', 1, 1000000),
(39, 42, 5, 'Đen', 1, 1000000),
(40, 43, 6, 'Trắng', 1, 1200000),
(41, 44, 7, 'Xanh', 2, 1400000),
(42, 45, 8, 'Vàng', 1, 900000),
(43, 46, 9, 'Đen', 1, 800000),
(44, 47, 10, 'Xanh lá', 2, 1100000),
(45, 48, 11, 'Xanh', 1, 1300000),
(46, 49, 12, 'Đỏ', 1, 950000),
(47, 50, 13, 'Vàng', 1, 1200000),
(48, 51, 14, 'Đen', 2, 1400000),
(49, 52, 15, 'Xanh', 1, 1500000),
(50, 53, 16, 'Xanh lá', 1, 1250000),
(51, 54, 17, 'Đỏ', 2, 950000),
(52, 55, 18, 'Trắng', 1, 1100000),
(53, 56, 19, 'Đen', 1, 1450000),
(54, 57, 20, 'Xanh', 1, 1350000),
(55, 58, 21, 'Vàng', 2, 1200000),
(56, 59, 22, 'Xanh lá', 1, 1400000);

-- Đà Nẵng (19 đơn hàng) - order_details
INSERT INTO order_details (id, order_id, product_id, color, quantity, total_price) VALUES
(60, 60, 1, 'Đen', 1, 500000),
(61, 60, 2, 'Xanh', 1, 500000),
(62, 61, 3, 'Đỏ', 2, 1500000),
(63, 62, 4, 'Xanh lá', 1, 1000000),
(64, 62, 5, 'Đen', 1, 1000000),
(65, 63, 6, 'Trắng', 1, 1200000),
(66, 64, 7, 'Xanh', 2, 1400000),
(67, 65, 8, 'Vàng', 1, 900000),
(68, 66, 9, 'Đen', 1, 800000),
(69, 67, 10, 'Xanh lá', 2, 1100000),
(70, 68, 11, 'Xanh', 1, 1300000),
(71, 69, 12, 'Đỏ', 1, 950000),
(72, 70, 13, 'Vàng', 1, 1200000),
(73, 71, 14, 'Đen', 2, 1400000),
(74, 72, 15, 'Xanh', 1, 1500000),
(75, 73, 16, 'Xanh lá', 1, 1250000),
(76, 74, 17, 'Đỏ', 2, 950000),
(77, 75, 18, 'Trắng', 1, 1100000),
(78, 76, 19, 'Đen', 1, 1450000),
(79, 77, 20, 'Xanh', 1, 1350000),
(80, 78, 21, 'Vàng', 2, 1200000),
(81, 79, 22, 'Xanh lá', 1, 1400000);


-- 19. INSERT INTO payOS_transactions
INSERT INTO payOS_transactions (id, orderCode, order_id, amount)
VALUES
(1, '100001', 60, 500000),
(2, '100002', 61, 1500000),
(3, '100003', 62, 2000000),
(4, '100004', 63, 1200000),
(5, '100005', 64, 1400000),
(6, '100006', 65, 900000),
(7, '100007', 66, 800000),
(8, '100008', 67, 1100000),
(9, '100009', 68, 1300000),
(10, '100010', 69, 950000);



-- 21. INSERT INTO blogs
INSERT INTO blogs (id, title, description, image_url, image_public_id, created_time, status)
VALUES
(1, 'Bảo Vệ Đôi Mắt: Bí Quyết Chọn Kính Hoàn Hảo',
    'Tìm hiểu cách chọn kính mắt phù hợp để bảo vệ đôi mắt và nâng tầm phong cách của bạn. Bí quyết bao gồm chọn chất liệu, kiểu dáng và loại tròng kính.',
    'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508358/blog3_yqg5vr.png', 'blog3_yqg5vr', NOW(), 'active'),

(2, 'Kính Râm: Hơn Cả Một Phụ Kiện Thời Trang',
    'Khám phá lý do tại sao kính râm không chỉ giúp bạn phong cách hơn mà còn bảo vệ mắt khỏi tia UV có hại.',
    'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508346/blog2_ayn1vs.png', 'blog2_ayn1vs', NOW(), 'active'),

(3, 'Chăm Sóc Tròng Kính: Mẹo Bảo Quản Để Độ Rõ Nét Lâu Dài',
    'Khám phá các mẹo thực tế để vệ sinh và bảo quản kính mắt hoặc kính áp tròng nhằm đảm bảo hiệu suất và độ bền lâu dài.',
    'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508345/blog1_tjzv6m.jpg', 'blog1_tjzv6m', NOW(), 'active'),

(4, 'Chào Đón Năm Mới Với Ưu Đãi Lớn: Giảm Giá Đến 50%!',
    'Tưng bừng chào đón năm mới với những ưu đãi siêu hấp dẫn! Hãy sử dụng các mã giảm giá dưới đây để tiết kiệm hơn khi mua sắm:

    COUPON30: Giảm 30% cho mọi đơn hàng.
    COUPON35: Giảm 35% cho mọi đơn hàng.
    COUPON40: Giảm 40% cho mọi đơn hàng.
    COUPON50: Giảm 50% cho mọi đơn hàng.

    Nhanh tay lên! Chương trình ưu đãi chỉ diễn ra đến hết ngày 31/12/2024. Mua sắm ngay để khởi đầu năm mới với những món hời tuyệt vời!',
    'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734517321/new-year-sale_qnlpsc.png', 'new-year-sale_qnlpsc', NOW(), 'active');

-- 22. INSERT INTO banners
INSERT INTO banners (id, image_url, image_public_id, status) VALUES (1, 'https://res.cloudinary.com/dlmzsfwcf/image/upload/v1734508346/banner_qevb0o.png', 'banner_qevb0o', 'active');

-- 23. INSERT INTO product_reviews
INSERT INTO product_reviews (id, product_id, user_id, rating, review, status)
VALUES
-- Product 1
(1, 1, 5, 5, 'Chất lượng tuyệt vời, rất hài lòng!', 'active'),
(2, 1, 6, 4, 'Thiết kế đẹp, giá hợp lý.', 'active'),
(3, 1, 7, 3, 'Hơi nặng nhưng ổn.', 'active'),
-- Product 2
(4, 2, 8, 5, 'Đeo rất thoải mái.', 'active'),
(5, 2, 9, 4, 'Hàng tốt, giao hơi chậm.', 'active'),
-- Product 3
(6, 3, 10, 5, 'Nhẹ và dễ chịu khi đeo cả ngày.', 'active'),
(7, 3, 11, 4, 'Thiết kế ổn nhưng hơi đơn giản.', 'active'),
-- Product 4
(8, 4, 12, 5, 'Thiết kế hiện đại, đáng giá.', 'active'),
(9, 4, 13, 4, 'Bền nhưng hơi cứng.', 'active'),
-- Product 5
(10, 5, 14, 5, 'Hơn cả mong đợi!', 'active'),
(11, 5, 15, 4, 'Đáng tiền, thời trang.', 'active'),
-- Product 6
(12, 6, 16, 5, 'Sang trọng, rất hài lòng.', 'active'),
(13, 6, 17, 3, 'Không hợp với mặt tôi.', 'active'),
-- Product 7
(14, 7, 18, 5, 'Rất phù hợp cho mọi dịp.', 'active'),
(15, 7, 19, 4, 'Thời trang, chất lượng tốt.', 'active'),
-- Product 8
(16, 8, 20, 5, 'Giá tốt, chất lượng ổn.', 'active'),
(17, 8, 21, 4, 'Đeo rất thoải mái.', 'active'),
-- Product 9
(18, 9, 22, 4, 'Tạm ổn, mong cải thiện.', 'active'),
(19, 9, 23, 5, 'Rất đáng mua.', 'active'),
-- Product 10
(20, 10, 24, 5, 'Đẹp, thoải mái khi đeo.', 'active'),
(21, 10, 25, 4, 'Ổn, nhưng hơi đắt.', 'active'),
-- Product 11
(22, 11, 26, 5, 'Thời trang, phù hợp giá.', 'active'),
(23, 11, 27, 4, 'Bền và nhẹ, rất ưng.', 'active'),
-- Product 12
(24, 12, 28, 5, 'Bền, xứng giá tiền.', 'active'),
(25, 12, 29, 4, 'Thiết kế đẹp nhưng hơi nặng.', 'active'),
-- Product 13
(26, 13, 30, 5, 'Tuyệt vời khi đeo lâu.', 'active'),
(27, 13, 31, 4, 'Chất lượng ổn.', 'active'),
-- Product 14
(28, 14, 32, 5, 'Sang trọng, nhẹ.', 'active'),
(29, 14, 33, 4, 'Vừa vặn, chất lượng tốt.', 'active'),
-- Product 15
(30, 15, 5, 5, 'Thời trang, thoải mái.', 'active'),
(31, 15, 6, 4, 'Giá ổn, chất lượng tốt.', 'active'),
-- Product 16
(32, 16, 7, 5, 'Tuyệt vời, rất hài lòng.', 'active'),
(33, 16, 8, 4, 'Nhẹ, dễ chịu.', 'active'),
-- Product 17
(34, 17, 9, 5, 'Khuyên dùng, rất đáng giá.', 'active'),
(35, 17, 10, 4, 'Đẹp nhưng hơi đắt.', 'active'),
-- Product 18
(36, 18, 11, 5, 'Rất đẹp, phù hợp.', 'active'),
(37, 18, 12, 4, 'Ổn, giá hợp lý.', 'active'),
-- Product 19
(38, 19, 13, 5, 'Sang trọng và chắc chắn.', 'active'),
(39, 19, 14, 4, 'Giá cao nhưng đáng tiền.', 'active'),
-- Product 20
(40, 20, 15, 5, 'Nhẹ và thời trang.', 'active'),
(41, 20, 16, 4, 'Thiết kế đẹp.', 'active'),
-- Product 21
(42, 21, 17, 5, 'Sản phẩm tốt, hài lòng.', 'active'),
(43, 21, 18, 4, 'Đẹp, nhưng hơi nặng.', 'active'),
-- Product 22
(44, 22, 19, 5, 'Đeo rất thích, chất lượng tốt.', 'active'),
(45, 22, 20, 4, 'Giá ổn.', 'active'),
-- Product 23
(46, 23, 21, 5, 'Rất tốt, rất thích.', 'active'),
(47, 23, 22, 4, 'Ổn trong tầm giá.', 'active'),
-- Product 24
(48, 24, 23, 5, 'Tuyệt vời cho mùa hè.', 'active'),
(49, 24, 24, 4, 'Bền và đẹp.', 'active'),
-- Product 25
(50, 25, 25, 5, 'Chất lượng cao, rất hài lòng.', 'active'),
(51, 25, 26, 4, 'Giá hơi cao nhưng ổn.', 'active'),
-- Product 26
(52, 26, 27, 5, 'Nhẹ, phù hợp cho mọi dịp.', 'active'),
(53, 26, 28, 4, 'Hợp lý trong tầm giá.', 'active'),
-- Product 27
(54, 27, 29, 5, 'Đeo cả ngày không mỏi.', 'active'),
(55, 27, 30, 4, 'Đẹp, giá ổn.', 'active'),
-- Product 28
(56, 28, 31, 5, 'Sang trọng, bền.', 'active'),
(57, 28, 32, 4, 'Ổn định và chất lượng.', 'active'),
-- Product 29
(58, 29, 33, 5, 'Đẹp và nhẹ.', 'active'),
(59, 29, 5, 4, 'Chất lượng tốt.', 'active'),
-- Product 30
(60, 30, 6, 5, 'Phù hợp cho công việc.', 'active'),
(61, 30, 7, 4, 'Đẹp và chắc chắn.', 'active'),
-- Product 31
(62, 31, 8, 5, 'Chất lượng vượt mong đợi.', 'active'),
(63, 31, 9, 4, 'Thiết kế đẹp.', 'active'),
-- Product 32
(64, 32, 10, 5, 'Tốt, rất hài lòng.', 'active'),
(65, 32, 11, 4, 'Bền và nhẹ.', 'active'),
-- Product 33
(66, 33, 12, 5, 'Đáng mua.', 'active'),
(67, 33, 13, 4, 'Đẹp nhưng hơi đắt.', 'active'),
-- Product 34
(68, 34, 14, 5, 'Nhẹ và bền.', 'active'),
(69, 34, 15, 4, 'Chất lượng hợp lý.', 'active'),
-- Product 35
(70, 35, 16, 5, 'Tốt, phù hợp.', 'active'),
(71, 35, 17, 4, 'Giá hơi cao.', 'active'),
-- Product 36
(72, 36, 18, 5, 'Thiết kế tuyệt vời.', 'active'),
(73, 36, 19, 4, 'Ổn trong tầm giá.', 'active'),
-- Product 37
(74, 37, 20, 5, 'Rất hài lòng.', 'active'),
(75, 37, 21, 4, 'Chất lượng tốt.', 'active'),
-- Product 38
(76, 38, 22, 5, 'Sản phẩm chất lượng.', 'active'),
(77, 38, 23, 4, 'Đáng tiền.', 'active'),
-- Product 39
(78, 39, 24, 5, 'Nhẹ, đẹp.', 'active'),
(79, 39, 25, 4, 'Rất tốt.', 'active'),
-- Product 40
(80, 40, 26, 5, 'Đáng giá.', 'active'),
(81, 40, 27, 4, 'Ổn định.', 'active'),
-- Product 41
(82, 41, 28, 5, 'Tuyệt vời.', 'active'),
(83, 41, 29, 4, 'Chất lượng cao.', 'active'),
-- Product 42
(84, 42, 30, 5, 'Tốt, rất thích.', 'active'),
(85, 42, 31, 4, 'Hài lòng.', 'active'),
-- Product 43
(86, 43, 32, 5, 'Chất lượng tốt.', 'active'),
(87, 43, 33, 4, 'Hơi nặng.', 'active'),
-- Product 44
(88, 44, 5, 5, 'Đáng mua, rất đẹp.', 'active'),
(89, 44, 6, 4, 'Ổn.', 'active');

-- 24. INSERT INTO otps
INSERT INTO otps (id, user_id, otp, expires_at, created_at, updated_at)
VALUES
(1, 5, '123456', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(2, 6, '234567', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(3, 7, '345678', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(4, 8, '456789', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(5, 9, '567890', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(6, 10, '678901', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(7, 11, '789012', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(8, 12, '890123', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(9, 13, '901234', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW()),
(10, 14, '012345', DATE_ADD(NOW(), INTERVAL 10 MINUTE), NOW(), NOW());
