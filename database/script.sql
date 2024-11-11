
--mk: 123456 for all users
select product_id, name from product_features inner join features on product_features.feature_id = features.id where product_id=1
select products.name, branches.name, quantity, price from products, product_details, branches
where products.id = product_details.product_id
and branches.id = product_details.branch_id
and branch_id = 1
select * from product_details where product_id=1
select * from shapes ORDER BY product_id
select * from category
select * from brands

desc features
SELECT * FROM information_schema.columns WHERE table_schema = 'lensart_eyewear';




