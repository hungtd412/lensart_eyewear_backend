
--mk: 123456 for all users

SELECT product_details.quantity, product_details.color, product_details.product_id, (branches.index * products.price) as mainprice
from products, branches, product_details
where products.id = product_details.product_id
and product_details.branch_id = branches.id
and branches.id = 2
and ((product_details.product_id = 35 and product_details.color = "Đen") or (product_details.product_id = 11 and product_details.color = "Xám"))
--44 32
--khi order 43 29

update table

update product_details
set quantity = 50
where product_details.product_id = 35 and product_details.color = "Đen"
and product_details.branch_id = 2

SELECT cart_details.quantity, products.id as proId, cart_details.color, (branches.index * products.price), carts.user_id
from products, branches, carts, cart_details
where products.id = cart_details.product_id
and cart_details.branch_id = branches.id
and branches.id = 2
and carts.user_id=6
and cart_details.cart_id = carts.id
and ((cart_details.product_id = 35 and cart_details.color = "Đen") or (cart_details.product_id = 11 and cart_details.color = "Xám"))


select * from product_details
select * from users where id = 10
delete from users where id = 10
delete from carts where user_id = 10
select * from orders, order_details
where orders.id=66 and order_details.order_id = orders.id

SELECT orders.id, user_id, branch_id
FROM orders
WHERE orders.id = 45;

SELECT orders.id, orders.order_status, orders.user_id, orders.branch_id from orders
where status = 'active' and orders.branch_id=3;

SELECT * from users

SELECT users.username, orders.id, orders.total_price, orders.branch_id  from orders, users
where orders.payment_status = 'Chưa thanh toán'
and users.id = orders.user_id

insert into payos_transactions (orderCode, order_id, amount) VALUES (1, 2, 0);
insert into payos_transactions (orderCode, order_id, amount) VALUES (2, 2, 0);

SELECT sum(amount)
FROM payos_transactions
WHERE order_id = 4;

UPDATE payos_transactions
SET amount = 108447.00
WHERE orderCode = 45291;

select id, user_id, total_price, payment_status, branch_id from orders where id = 13

select * from payos_transactions, cart_details
where carts.user_id=6 and cart_details.cart_id = carts.id

INSERT INTO cart_details (cart_id, product_id, branch_id, color, quantity, total_price)
VALUES
(2, 35, 2, 'Đen', 3, 1821259.2),
(2, 11, 2, 'Xám', 1, 572532.8);

select * from orders, order_details, product_details
where orders.id = order_details.order_id
and product_details.branch_id = orders.branch_id
and order_details.product_id = product_details.product_id
and orders.id =1


select * from products

select * from cart_details where cart_details.cart_id = 2
select * from coupons
