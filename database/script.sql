use lensart_eyewear

-- insert into role(name) values
-- ('admin'),
-- ('manager'),
-- ('user')

--insert into users(username, password, email, role_id, phone, address) values ('hungadmin', '$2y$12$nIcH4UPbX4.4B5XDr602Eux79q2vRyYEelNyOoLMJAHm0oIbe2yqq', 'tdhung41204@gmail.com', 1, '0987654321', '246 Trần Hưng Đạo, thị xã Quảng Trị, tỉnh Quảng Trị')
--mk: 123456 for all users
select product_id, name from product_features inner join features on product_features.feature_id = features.id where product_id=1
select * from users
select * from product_images
select * from brands

desc products
SELECT * FROM information_schema.columns WHERE table_schema = 'lensart_eyewear';




