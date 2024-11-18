
--mk: 123456 for all users

SELECT product_details.quantity, product_details.color, product_details.product_id, (branches.index * products.price) as mainprice
from products, branches, product_details
where products.id = product_details.product_id
and product_details.branch_id = branches.id
and branches.id = 2
and ((product_details.product_id = 35 and product_details.color = "Đen") or (product_details.product_id = 11 and product_details.color = "Xám"))
--50 50

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



select * from users
select * from orders, order_details
where orders.id=49 and order_details.order_id = orders.id

SELECT orders.id, user_id, branch_id
FROM orders
WHERE orders.id = 45;

SELECT orders.id, orders.order_status, orders.user_id, orders.branch_id from orders
where order_status <> 'Đã hủy';

SELECT orders.id, orders.order_status, orders.user_id, orders.branch_id from orders

select * from carts, cart_details
where carts.user_id=6 and cart_details.cart_id = carts.id

INSERT INTO cart_details (cart_id, product_id, branch_id, color, quantity, total_price)
VALUES
(2, 35, 2, 'Đen', 3, 1821259.2),
(2, 11, 2, 'Xám', 1, 572532.8);


select id, discount_price, quantity from coupons where status = 'active'

select * from branches




